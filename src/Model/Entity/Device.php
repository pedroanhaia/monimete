<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Device Entity
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $type
 * @property string|null $model
 * @property string|null $producer
 * @property string|null $description
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 * @property int|null $role
 * @property int|null $location_id
 * @property string|null $img
 *
 * @property \App\Model\Entity\Location $location
 * @property \App\Model\Entity\DataMetereological[] $data_metereological
 * @property \App\Model\Entity\DataSatellite[] $data_satellite
 * @property \App\Model\Entity\Log[] $logs
 * @property \App\Model\Entity\Setting[] $settings
 */
class Device extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'name' => true,
        'type' => true,
        'model' => true,
        'producer' => true,
        'description' => true,
        'created' => true,
        'modified' => true,
        'role' => true,
        'location_id' => true,
        'img' => true,
        'location' => true,
        'data_metereological' => true,
        'data_satellite' => true,
        'logs' => true,
        'settings' => true,
    ];
}
