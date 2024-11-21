<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Services Model
 *
 * @property \App\Model\Table\PlatformsTable&\Cake\ORM\Association\BelongsTo $Platforms
 * @property \App\Model\Table\DataMetereologicalTable&\Cake\ORM\Association\HasMany $DataMetereological
 *
 * @method \App\Model\Entity\Service newEmptyEntity()
 * @method \App\Model\Entity\Service newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Service> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Service get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Service findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Service patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Service> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Service|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Service saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Service>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Service>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Service>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Service> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Service>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Service>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Service>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Service> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ServicesTable extends Table
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

        $this->setTable('services');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Platforms', [
            'foreignKey' => 'platform_id',
        ]);
        $this->hasMany('DataMetereological', [
            'foreignKey' => 'service_id',
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
            ->scalar('endpoint')
            ->maxLength('endpoint', 255)
            ->allowEmptyString('endpoint');

        $validator
            ->integer('platform_id')
            ->allowEmptyString('platform_id');

        $validator
            ->integer('role')
            ->allowEmptyString('role');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['platform_id'], 'Platforms'), ['errorField' => 'platform_id']);

        return $rules;
    }
}
