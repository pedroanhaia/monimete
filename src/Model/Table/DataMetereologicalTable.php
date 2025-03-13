<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DataMetereological Model
 *
 * @property \App\Model\Table\LocationsTable&\Cake\ORM\Association\BelongsTo $Locations
 * @property \App\Model\Table\ServicesTable&\Cake\ORM\Association\BelongsTo $Services
 * @property \App\Model\Table\DevicesTable&\Cake\ORM\Association\BelongsTo $Devices
 *
 * @method \App\Model\Entity\DataMetereological newEmptyEntity()
 * @method \App\Model\Entity\DataMetereological newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\DataMetereological> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DataMetereological get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\DataMetereological findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\DataMetereological patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\DataMetereological> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\DataMetereological|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\DataMetereological saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\DataMetereological>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\DataMetereological>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\DataMetereological>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\DataMetereological> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\DataMetereological>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\DataMetereological>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\DataMetereological>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\DataMetereological> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DataMetereologicalTable extends Table
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

        $this->setTable('data_metereological');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Locations', [
            'foreignKey' => 'location_id',
        ]);
        $this->belongsTo('Services', [
            'foreignKey' => 'service_id',
        ]);
        $this->belongsTo('Devices', [
            'foreignKey' => 'device_id',
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
            ->dateTime('date_time')
            ->allowEmptyDateTime('date_time');

        $validator
            ->numeric('temperature')
            ->allowEmptyString('temperature');

        $validator
            ->numeric('humidity')
            ->allowEmptyString('humidity');

        $validator
            ->numeric('precipitation')
            ->allowEmptyString('precipitation');

        $validator
            ->scalar('wind_direction')
            ->maxLength('wind_direction', 50)
            ->allowEmptyString('wind_direction');

        $validator
            ->numeric('wind_speed')
            ->allowEmptyString('wind_speed');

        $validator
            ->numeric('latitude')
            ->allowEmptyString('latitude');

        $validator
            ->numeric('longitude')
            ->allowEmptyString('longitude');

        $validator
            ->integer('location_id')
            ->allowEmptyString('location_id');

        $validator
            ->integer('service_id')
            ->allowEmptyString('service_id');

        $validator
            ->integer('device_id')
            ->allowEmptyString('device_id');

        $validator
            ->integer('role')
            ->allowEmptyString('role');

        $validator
            ->integer('type')
            ->allowEmptyString('type');

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
        $rules->add($rules->existsIn(['location_id'], 'Locations'), ['errorField' => 'location_id']);
        $rules->add($rules->existsIn(['service_id'], 'Services'), ['errorField' => 'service_id']);
        $rules->add($rules->existsIn(['device_id'], 'Devices'), ['errorField' => 'device_id']);

        return $rules;
    }
}
