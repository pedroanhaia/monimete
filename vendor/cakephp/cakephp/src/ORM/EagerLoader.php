<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         3.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\ORM;

use Cake\ORM\Query\SelectQuery;
use Closure;
use InvalidArgumentException;

/**
 * Exposes the methods for storing the associations that should be eager loaded
 * for a table once a query is provided and delegates the job of creating the
 * required joins and decorating the results so that those associations can be
 * part of the result set.
 */
class EagerLoader
{
    /**
     * Nested array describing the association to be fetched
     * and the options to apply for each of them, if any
     *
     * @var array<string, mixed>
     */
    protected array $_containments = [];

    /**
     * Contains a nested array with the compiled containments tree
     * This is a normalized version of the user provided containments array.
     *
     * @var \Cake\ORM\EagerLoadable|array<\Cake\ORM\EagerLoadable>|null
     */
    protected EagerLoadable|array|null $_normalized = null;

    /**
     * List of options accepted by associations in contain()
     * index by key for faster access.
     *
     * @var array<string, int>
     */
    protected array $_containOptions = [
        'associations' => 1,
        'foreignKey' => 1,
        'conditions' => 1,
        'fields' => 1,
        'sort' => 1,
        'matching' => 1,
        'queryBuilder' => 1,
        'finder' => 1,
        'joinType' => 1,
        'strategy' => 1,
        'negateMatch' => 1,
    ];

    /**
     * A list of associations that should be loaded with a separate query.
     *
     * @var array<\Cake\ORM\EagerLoadable>
     */
    protected array $_loadExternal = [];

    /**
     * Contains a list of the association names that are to be eagerly loaded.
     *
     * @var array
     */
    protected array $_aliasList = [];

    /**
     * Another EagerLoader instance that will be used for 'matching' associations.
     *
     * @var \Cake\ORM\EagerLoader|null
     */
    protected ?EagerLoader $_matching = null;

    /**
     * A map of table aliases pointing to the association objects they represent
     * for the query.
     *
     * @var array<string, \Cake\ORM\EagerLoadable>
     */
    protected array $_joinsMap = [];

    /**
     * Controls whether fields from associated tables will be eagerly loaded.
     * When set to false, no fields will be loaded from associations.
     *
     * @var bool
     */
    protected bool $_autoFields = true;

    /**
     * Sets the list of associations that should be eagerly loaded along for a
     * specific table using when a query is provided. The list of associated tables
     * passed to this method must have been previously set as associations using the
     * Table API.
     *
     * Associations can be arbitrarily nested using dot notation or nested arrays,
     * this allows this object to calculate joins or any additional queries that
     * must be executed to bring the required associated data.
     *
     * Accepted options per passed association:
     *
     * - `foreignKey`: Used to set a different field to match both tables, if set to false
     *   no join conditions will be generated automatically
     * - `fields`: An array with the fields that should be fetched from the association
     * - `queryBuilder`: Equivalent to passing a callback instead of an options array
     * - `matching`: Whether to inform the association class that it should filter the
     *  main query by the results fetched by that class.
     * - `joinType`: For joinable associations, the SQL join type to use.
     * - `strategy`: The loading strategy to use (join, select, subquery)
     *
     * @param array|string $associations List of table aliases to be queried.
     * When this method is called multiple times it will merge previous list with
     * the new one.
     * @param \Closure|null $queryBuilder The query builder callback.
     * @return array Containments.
     * @throws \InvalidArgumentException When using $queryBuilder with an array of $associations
     */
    public function contain(array|string $associations, ?Closure $queryBuilder = null): array
    {
        if ($queryBuilder) {
            if (!is_string($associations)) {
                throw new InvalidArgumentException(
                    'Cannot set containments. To use $queryBuilder, $associations must be a string',
                );
            }

            $associations = [
                $associations => [
                    'queryBuilder' => $queryBuilder,
                ],
            ];
        }

        $associations = (array)$associations;
        $associations = $this->_reformatContain($associations, $this->_containments);
        $this->_normalized = null;
        $this->_loadExternal = [];
        $this->_aliasList = [];

        return $this->_containments = $associations;
    }

    /**
     * Gets the list of associations that should be eagerly loaded along for a
     * specific table using when a query is provided. The list of associated tables
     * passed to this method must have been previously set as associations using the
     * Table API.
     *
     * @return array Containments.
     */
    public function getContain(): array
    {
        return $this->_containments;
    }

    /**
     * Remove any existing non-matching based containments.
     *
     * This will reset/clear out any contained associations that were not
     * added via matching().
     *
     * @return void
     */
    public function clearContain(): void
    {
        $this->_containments = [];
        $this->_normalized = null;
        $this->_loadExternal = [];
        $this->_aliasList = [];
    }

    /**
     * Sets whether contained associations will load fields automatically.
     *
     * @param bool $enable The value to set.
     * @return $this
     */
    public function enableAutoFields(bool $enable = true)
    {
        $this->_autoFields = $enable;

        return $this;
    }

    /**
     * Disable auto loading fields of contained associations.
     *
     * @return $this
     */
    public function disableAutoFields()
    {
        $this->_autoFields = false;

        return $this;
    }

    /**
     * Gets whether contained associations will load fields automatically.
     *
     * @return bool The current value.
     */
    public function isAutoFieldsEnabled(): bool
    {
        return $this->_autoFields;
    }

    /**
     * Adds a new association to the list that will be used to filter the results of
     * any given query based on the results of finding records for that association.
     * You can pass a dot separated path of associations to this method as its first
     * parameter, this will translate in setting all those associations with the
     * `matching` option.
     *
     *  ### Options
     *
     *  - `joinType`: INNER, OUTER, ...
     *  - `fields`: Fields to contain
     *  - `negateMatch`: Whether to add conditions negate match on target association
     *
     * @param string $associationPath Dot separated association path, 'Name1.Name2.Name3'.
     * @param \Closure|null $builder the callback function to be used for setting extra
     * options to the filtering query.
     * @param array<string, mixed> $options Extra options for the association matching.
     * @return $this
     */
    public function setMatching(string $associationPath, ?Closure $builder = null, array $options = [])
    {
        $this->_matching ??= new static();

        $options += ['joinType' => SelectQuery::JOIN_TYPE_INNER];
        $sharedOptions = ['negateMatch' => false, 'matching' => true] + $options;

        $contains = [];
        $nested = &$contains;
        foreach (explode('.', $associationPath) as $association) {
            // Add contain to parent contain using association name as key
            $nested[$association] = $sharedOptions;
            // Set to next nested level
            $nested = &$nested[$association];
        }

        // Add all options to target association contain which is the last in nested chain
        $nested = ['matching' => true, 'queryBuilder' => $builder] + $options;
        $this->_matching->contain($contains);

        return $this;
    }

    /**
     * Returns the current tree of associations to be matched.
     *
     * @return array The resulting containments array.
     */
    public function getMatching(): array
    {
        $this->_matching ??= new static();

        return $this->_matching->getContain();
    }

    /**
     * Returns the fully normalized array of associations that should be eagerly
     * loaded for a table. The normalized array will restructure the original array
     * by sorting all associations under one key and special options under another.
     *
     * Each of the levels of the associations tree will be converted to a {@link \Cake\ORM\EagerLoadable}
     * object, that contains all the information required for the association objects
     * to load the information from the database.
     *
     * Additionally, it will set an 'instance' key per association containing the
     * association instance from the corresponding source table
     *
     * @param \Cake\ORM\Table $repository The table containing the association that
     * will be normalized.
     * @return array
     */
    public function normalized(Table $repository): array
    {
        if ($this->_normalized !== null || $this->_containments === []) {
            return (array)$this->_normalized;
        }

        $contain = [];
        foreach ($this->_containments as $alias => $options) {
            if (!empty($options['instance'])) {
                $contain = $this->_containments;
                break;
            }
            $contain[$alias] = $this->_normalizeContain(
                $repository,
                $alias,
                $options,
                ['root' => null],
            );
        }

        return $this->_normalized = $contain;
    }

    /**
     * Formats the containments array so that associations are always set as keys
     * in the array. This function merges the original associations array with
     * the new associations provided.
     *
     * @param array $associations User provided containments array.
     * @param array $original The original containments array to merge
     * with the new one.
     * @return array
     */
    protected function _reformatContain(array $associations, array $original): array
    {
        $result = $original;

        foreach ($associations as $table => $options) {
            $pointer = &$result;
            if (is_int($table)) {
                $table = $options;
                $options = [];
            }

            if ($options instanceof EagerLoadable) {
                $options = $options->asContainArray();
                $table = key($options);
                $options = current($options);
            }

            if (isset($this->_containOptions[$table])) {
                $pointer[$table] = $options;
                continue;
            }

            if (str_contains($table, '.')) {
                $path = explode('.', $table);
                $table = array_pop($path);
                foreach ($path as $t) {
                    $pointer += [$t => []];
                    $pointer = &$pointer[$t];
                }
            }

            if (is_array($options)) {
                $options = isset($options['config']) ?
                    $options['config'] + $options['associations'] :
                    $options;
                $options = $this->_reformatContain(
                    $options,
                    $pointer[$table] ?? [],
                );
            }

            if ($options instanceof Closure) {
                $options = ['queryBuilder' => $options];
            }

            $pointer += [$table => []];

            if (isset($options['queryBuilder'], $pointer[$table]['queryBuilder'])) {
                $first = $pointer[$table]['queryBuilder'];
                $second = $options['queryBuilder'];
                $options['queryBuilder'] = fn ($query) => $second($first($query));
            }

            if (!is_array($options)) {
                /** @psalm-suppress InvalidArrayOffset */
                $options = [$options => []];
            }

            $pointer[$table] = $options + $pointer[$table];
        }

        return $result;
    }

    /**
     * Modifies the passed query to apply joins or any other transformation required
     * in order to eager load the associations described in the `contain` array.
     * This method will not modify the query for loading external associations, i.e.
     * those that cannot be loaded without executing a separate query.
     *
     * @param \Cake\ORM\Query\SelectQuery $query The query to be modified.
     * @param \Cake\ORM\Table $repository The repository containing the associations
     * @param bool $includeFields whether to append all fields from the associations
     * to the passed query. This can be overridden according to the settings defined
     * per association in the containments array.
     * @return void
     */
    public function attachAssociations(SelectQuery $query, Table $repository, bool $includeFields): void
    {
        if (!$this->_containments && $this->_matching === null) {
            return;
        }

        $attachable = $this->attachableAssociations($repository);
        $processed = [];
        do {
            foreach ($attachable as $alias => $loadable) {
                $config = $loadable->getConfig() + [
                    'aliasPath' => $loadable->aliasPath(),
                    'propertyPath' => $loadable->propertyPath(),
                    'includeFields' => $includeFields,
                ];
                $loadable->instance()->attachTo($query, $config);
                $processed[$alias] = true;
            }

            $newAttachable = $this->attachableAssociations($repository);
            $attachable = array_diff_key($newAttachable, $processed);
        } while ($attachable !== []);
    }

    /**
     * Returns an array with the associations that can be fetched using a single query,
     * the array keys are the association aliases and the values will contain an array
     * with Cake\ORM\EagerLoadable objects.
     *
     * @param \Cake\ORM\Table $repository The table containing the associations to be
     * attached.
     * @return array<\Cake\ORM\EagerLoadable>
     */
    public function attachableAssociations(Table $repository): array
    {
        $contain = $this->normalized($repository);
        $matching = $this->_matching ? $this->_matching->normalized($repository) : [];
        $this->_fixStrategies();
        $this->_loadExternal = [];

        return $this->_resolveJoins($contain, $matching);
    }

    /**
     * Returns an array with the associations that need to be fetched using a
     * separate query, each array value will contain a {@link \Cake\ORM\EagerLoadable} object.
     *
     * @param \Cake\ORM\Table $repository The table containing the associations
     * to be loaded.
     * @return array<\Cake\ORM\EagerLoadable>
     */
    public function externalAssociations(Table $repository): array
    {
        if ($this->_loadExternal) {
            return $this->_loadExternal;
        }

        $this->attachableAssociations($repository);

        return $this->_loadExternal;
    }

    /**
     * Auxiliary function responsible for fully normalizing deep associations defined
     * using `contain()`.
     *
     * @param \Cake\ORM\Table $parent Owning side of the association.
     * @param string $alias Name of the association to be loaded.
     * @param array<string, mixed> $options List of extra options to use for this association.
     * @param array<string, mixed> $paths An array with two values, the first one is a list of dot
     * separated strings representing associations that lead to this `$alias` in the
     * chain of associations to be loaded. The second value is the path to follow in
     * entities' properties to fetch a record of the corresponding association.
     * @return \Cake\ORM\EagerLoadable Object with normalized associations
     * @throws \InvalidArgumentException When containments refer to associations that do not exist.
     */
    protected function _normalizeContain(Table $parent, string $alias, array $options, array $paths): EagerLoadable
    {
        $defaults = $this->_containOptions;
        $instance = $parent->getAssociation($alias);

        $paths += ['aliasPath' => '', 'propertyPath' => '', 'root' => $alias];
        $paths['aliasPath'] .= '.' . $alias;

        if (
            isset($options['matching']) &&
            $options['matching'] === true
        ) {
            $paths['propertyPath'] = '_matchingData.' . $alias;
        } else {
            $paths['propertyPath'] .= '.' . $instance->getProperty();
        }

        $table = $instance->getTarget();

        $extra = array_diff_key($options, $defaults);
        $config = [
            'associations' => [],
            'instance' => $instance,
            'config' => array_diff_key($options, $extra),
            'aliasPath' => trim($paths['aliasPath'], '.'),
            'propertyPath' => trim($paths['propertyPath'], '.'),
            'targetProperty' => $instance->getProperty(),
        ];
        $config['canBeJoined'] = $instance->canBeJoined($config['config']);
        $eagerLoadable = new EagerLoadable($alias, $config);

        if ($config['canBeJoined']) {
            $this->_aliasList[$paths['root']][$alias][] = $eagerLoadable;
        } else {
            $paths['root'] = $config['aliasPath'];
        }

        foreach ($extra as $t => $assoc) {
            $eagerLoadable->addAssociation(
                $t,
                $this->_normalizeContain($table, $t, $assoc, $paths),
            );
        }

        return $eagerLoadable;
    }

    /**
     * Iterates over the joinable aliases list and corrects the fetching strategies
     * in order to avoid aliases collision in the generated queries.
     *
     * This function operates on the array references that were generated by the
     * _normalizeContain() function.
     *
     * @return void
     */
    protected function _fixStrategies(): void
    {
        foreach ($this->_aliasList as $aliases) {
            foreach ($aliases as $configs) {
                if (count($configs) < 2) {
                    continue;
                }
                foreach ($configs as $loadable) {
                    assert($loadable instanceof EagerLoadable);
                    if (str_contains($loadable->aliasPath(), '.')) {
                        $this->_correctStrategy($loadable);
                    }
                }
            }
        }
    }

    /**
     * Changes the association fetching strategy if required because of duplicate
     * under the same direct associations chain.
     *
     * @param \Cake\ORM\EagerLoadable $loadable The association config.
     * @return void
     */
    protected function _correctStrategy(EagerLoadable $loadable): void
    {
        $config = $loadable->getConfig();
        $currentStrategy = $config['strategy'] ?? Association::STRATEGY_JOIN;

        if (!$loadable->canBeJoined() || $currentStrategy !== Association::STRATEGY_JOIN) {
            return;
        }

        $config['strategy'] = Association::STRATEGY_SELECT;
        $loadable->setConfig($config);
        $loadable->setCanBeJoined(false);
    }

    /**
     * Helper function used to compile a list of all associations that can be
     * joined in the query.
     *
     * @param array<\Cake\ORM\EagerLoadable> $associations List of associations from which to obtain joins.
     * @param array<\Cake\ORM\EagerLoadable> $matching List of associations that should be forcibly joined.
     * @return array<\Cake\ORM\EagerLoadable>
     */
    protected function _resolveJoins(array $associations, array $matching = []): array
    {
        $result = [];
        foreach ($matching as $table => $loadable) {
            $result[$table] = $loadable;
            $result += $this->_resolveJoins($loadable->associations(), []);
        }
        foreach ($associations as $table => $loadable) {
            $inMatching = isset($matching[$table]);
            if (!$inMatching && $loadable->canBeJoined()) {
                $result[$table] = $loadable;
                $result += $this->_resolveJoins($loadable->associations(), []);
                continue;
            }

            if ($inMatching) {
                $this->_correctStrategy($loadable);
            }

            $loadable->setCanBeJoined(false);
            $this->_loadExternal[] = $loadable;
        }

        return $result;
    }

    /**
     * Inject data from associations that cannot be joined directly.
     *
     * @param \Cake\ORM\Query\SelectQuery $query The query for which to eager load external.
     * associations.
     * @param iterable $results Results.
     * @return iterable
     * @throws \RuntimeException
     */
    public function loadExternal(SelectQuery $query, iterable $results): iterable
    {
        if (!$results) {
            return $results;
        }

        $external = $this->externalAssociations($query->getRepository());
        if (!$external) {
            return $results;
        }

        if (!is_array($results)) {
            $results = iterator_to_array($results);
        }
        if (!$results) {
            return $results;
        }

        $collected = $this->_collectKeys($external, $query, $results);

        foreach ($external as $meta) {
            $contain = $meta->associations();
            $instance = $meta->instance();
            $config = $meta->getConfig();
            $alias = $instance->getSource()->getAlias();
            $path = $meta->aliasPath();

            $requiresKeys = $instance->requiresKeys($config);
            if ($requiresKeys) {
                // If the path or alias has no key the required association load will fail.
                // Nested paths are not subject to this condition because they could
                // be attached to joined associations.
                if (
                    !str_contains($path, '.') &&
                    (!array_key_exists($path, $collected) || !array_key_exists($alias, $collected[$path]))
                ) {
                    $message = "Unable to load `{$path}` association. Ensure foreign key in `{$alias}` is selected.";
                    throw new InvalidArgumentException($message);
                }

                // If the association foreign keys are missing skip loading
                // as the association could be optional.
                if (empty($collected[$path][$alias])) {
                    continue;
                }
            }

            $keys = $collected[$path][$alias] ?? null;
            $callback = $instance->eagerLoader(
                $config + [
                    'query' => $query,
                    'contain' => $contain,
                    'keys' => $keys,
                    'nestKey' => $meta->aliasPath(),
                ],
            );
            $results = array_map($callback, $results);
        }

        return $results;
    }

    /**
     * Returns an array having as keys a dotted path of associations that participate
     * in this eager loader. The values of the array will contain the following keys:
     *
     * - `alias`: The association alias
     * - `instance`: The association instance
     * - `canBeJoined`: Whether the association will be loaded using a JOIN
     * - `entityClass`: The entity that should be used for hydrating the results
     * - `nestKey`: A dotted path that can be used to correctly insert the data into the results.
     * - `matching`: Whether it is an association loaded through `matching()`.
     *
     * @param \Cake\ORM\Table $table The table containing the association that
     * will be normalized.
     * @return array
     */
    public function associationsMap(Table $table): array
    {
        $map = [];

        if (!$this->getMatching() && !$this->getContain() && $this->_joinsMap === []) {
            return $map;
        }

        assert($this->_matching !== null, 'EagerLoader not available');

        $map = $this->_buildAssociationsMap($map, $this->_matching->normalized($table), true);
        $map = $this->_buildAssociationsMap($map, $this->normalized($table));

        return $this->_buildAssociationsMap($map, $this->_joinsMap);
    }

    /**
     * An internal method to build a map which is used for the return value of the
     * associationsMap() method.
     *
     * @param array $map An initial array for the map.
     * @param array<\Cake\ORM\EagerLoadable> $level An array of EagerLoadable instances.
     * @param bool $matching Whether it is an association loaded through `matching()`.
     * @return array
     */
    protected function _buildAssociationsMap(array $map, array $level, bool $matching = false): array
    {
        foreach ($level as $assoc => $meta) {
            $canBeJoined = $meta->canBeJoined();
            $instance = $meta->instance();
            $associations = $meta->associations();
            $forMatching = $meta->forMatching();
            $map[] = [
                'alias' => $assoc,
                'instance' => $instance,
                'canBeJoined' => $canBeJoined,
                'entityClass' => $instance->getTarget()->getEntityClass(),
                'nestKey' => $canBeJoined ? $assoc : $meta->aliasPath(),
                'matching' => $forMatching ?? $matching,
                'targetProperty' => $meta->targetProperty(),
            ];
            if ($canBeJoined && $associations) {
                $map = $this->_buildAssociationsMap($map, $associations, $matching);
            }
        }

        return $map;
    }

    /**
     * Registers a table alias, typically loaded as a join in a query, as belonging to
     * an association. This helps hydrators know what to do with the columns coming
     * from such joined table.
     *
     * @param string $alias The table alias as it appears in the query.
     * @param \Cake\ORM\Association $assoc The association object the alias represents;
     * will be normalized.
     * @param bool $asMatching Whether this join results should be treated as a
     * 'matching' association.
     * @param string|null $targetProperty The property name where the results of the join should be nested at.
     * If not passed, the default property for the association will be used.
     * @return void
     */
    public function addToJoinsMap(
        string $alias,
        Association $assoc,
        bool $asMatching = false,
        ?string $targetProperty = null,
    ): void {
        $this->_joinsMap[$alias] = new EagerLoadable($alias, [
            'aliasPath' => $alias,
            'instance' => $assoc,
            'canBeJoined' => true,
            'forMatching' => $asMatching,
            'targetProperty' => $targetProperty ?: $assoc->getProperty(),
        ]);
    }

    /**
     * Helper function used to return the keys from the query records that will be used
     * to eagerly load associations.
     *
     * @param array<\Cake\ORM\EagerLoadable> $external The list of external associations to be loaded.
     * @param \Cake\ORM\Query\SelectQuery $query The query from which the results where generated.
     * @param array $results Results array.
     * @return array
     */
    protected function _collectKeys(array $external, SelectQuery $query, array $results): array
    {
        $collectKeys = [];
        foreach ($external as $meta) {
            $instance = $meta->instance();
            if (!$instance->requiresKeys($meta->getConfig())) {
                continue;
            }

            $source = $instance->getSource();
            $keys = $instance->type() === Association::MANY_TO_ONE ?
                (array)$instance->getForeignKey() :
                (array)$instance->getBindingKey();

            $alias = $source->getAlias();
            $pkFields = [];
            /** @var string $key */
            foreach ($keys as $key) {
                $pkFields[] = key($query->aliasField($key, $alias));
            }
            $collectKeys[$meta->aliasPath()] = [$alias, $pkFields, count($pkFields) === 1];
        }
        if (!$collectKeys) {
            return [];
        }

        return $this->_groupKeys($results, $collectKeys);
    }

    /**
     * Helper function used to iterate a statement and extract the columns
     * defined in $collectKeys.
     *
     * @param array $results Results array.
     * @param array<string, array> $collectKeys The keys to collect.
     * @return array
     */
    protected function _groupKeys(array $results, array $collectKeys): array
    {
        $keys = [];
        foreach ($results as $result) {
            foreach ($collectKeys as $nestKey => $parts) {
                if ($parts[2] === true) {
                    // Missed joins will have null in the results.
                    if (!array_key_exists($parts[1][0], $result)) {
                        continue;
                    }
                    // Assign empty array to avoid not found association when optional.
                    if (!isset($result[$parts[1][0]])) {
                        if (!isset($keys[$nestKey][$parts[0]])) {
                            $keys[$nestKey][$parts[0]] = [];
                        }
                    } else {
                        $value = $result[$parts[1][0]];
                        $keys[$nestKey][$parts[0]][$value] = $value;
                    }
                    continue;
                }

                // Handle composite keys.
                $collected = [];
                foreach ($parts[1] as $key) {
                    $collected[] = $result[$key];
                }
                $keys[$nestKey][$parts[0]][implode(';', $collected)] = $collected;
            }
        }

        return $keys;
    }

    /**
     * Handles cloning eager loaders and eager loadables.
     *
     * @return void
     */
    public function __clone()
    {
        if ($this->_matching) {
            $this->_matching = clone $this->_matching;
        }
    }
}
