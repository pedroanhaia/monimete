<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Cities Model
 *
 * @property \App\Model\Table\LocationsTable&\Cake\ORM\Association\HasMany $Locations
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\HasMany $Users
 *
 * @method \App\Model\Entity\City newEmptyEntity()
 * @method \App\Model\Entity\City newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\City> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\City get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\City findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\City patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\City> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\City|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\City saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\City>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\City>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\City>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\City> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\City>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\City>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\City>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\City> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CitiesTable extends Table
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

        $this->setTable('cities');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Locations', [
            'foreignKey' => 'city_id',
        ]);
        $this->hasMany('Users', [
            'foreignKey' => 'city_id',
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
            ->scalar('obs')
            ->allowEmptyString('obs');

        $validator
            ->scalar('cod_ibge')
            ->maxLength('cod_ibge', 255)
            ->allowEmptyString('cod_ibge');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->integer('role')
            ->allowEmptyString('role');

        return $validator;
    }
}
