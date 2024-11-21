<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Platforms Model
 *
 * @property \App\Model\Table\LogsTable&\Cake\ORM\Association\HasMany $Logs
 * @property \App\Model\Table\ServicesTable&\Cake\ORM\Association\HasMany $Services
 *
 * @method \App\Model\Entity\Platform newEmptyEntity()
 * @method \App\Model\Entity\Platform newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Platform> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Platform get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Platform findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Platform patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Platform> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Platform|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Platform saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Platform>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Platform>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Platform>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Platform> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Platform>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Platform>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Platform>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Platform> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PlatformsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('platforms');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Logs', [
            'foreignKey' => 'platform_id',
        ]);
        $this->hasMany('Services', [
            'foreignKey' => 'platform_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('name')
            ->maxLength('name', 200)
            ->allowEmptyString('name');

        $validator
            ->integer('type')
            ->allowEmptyString('type');

        $validator
            ->scalar('url')
            ->maxLength('url', 255)
            ->allowEmptyString('url');

        $validator
            ->dateTime('last_update')
            ->allowEmptyDateTime('last_update');

        $validator
            ->integer('role')
            ->allowEmptyString('role');

        $validator
            ->scalar('powered')
            ->maxLength('powered', 255)
            ->allowEmptyString('powered');

        return $validator;
    }
}
