<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DataSatellite Model
 *
 * @property \App\Model\Table\DevicesTable&\Cake\ORM\Association\BelongsTo $Devices
 *
 * @method \App\Model\Entity\DataSatellite newEmptyEntity()
 * @method \App\Model\Entity\DataSatellite newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\DataSatellite> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DataSatellite get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\DataSatellite findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\DataSatellite patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\DataSatellite> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\DataSatellite|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\DataSatellite saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\DataSatellite>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\DataSatellite>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\DataSatellite>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\DataSatellite> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\DataSatellite>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\DataSatellite>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\DataSatellite>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\DataSatellite> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DataSatelliteTable extends Table
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

        $this->setTable('data_satellite');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

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
            ->numeric('quality_signal')
            ->allowEmptyString('quality_signal');

        $validator
            ->numeric('latitude')
            ->allowEmptyString('latitude');

        $validator
            ->numeric('longitude')
            ->allowEmptyString('longitude');

        $validator
            ->integer('type')
            ->allowEmptyString('type');

        $validator
            ->integer('device_id')
            ->allowEmptyString('device_id');

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
        $rules->add($rules->existsIn(['device_id'], 'Devices'), ['errorField' => 'device_id']);

        return $rules;
    }
}
