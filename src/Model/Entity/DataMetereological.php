<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * DataMetereological Entity
 *
 * @property int $id
 * @property \Cake\I18n\DateTime|null $date_time
 * @property float|null $temperature
 * @property float|null $humidity
 * @property float|null $precipitation
 * @property string|null $wind_direction
 * @property float|null $wind_speed
 * @property float|null $latitude
 * @property float|null $longitude
 * @property int|null $location_id
 * @property int|null $service_id
 * @property int|null $device_id
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 * @property int|null $role
 * @property int|null $type
 *
 * @property \App\Model\Entity\Location $location
 * @property \App\Model\Entity\Service $service
 * @property \App\Model\Entity\Device $device
 */
class DataMetereological extends Entity
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
        'date_time' => true,
        'temperature' => true,
        'humidity' => true,
        'precipitation' => true,
        'wind_direction' => true,
        'wind_speed' => true,
        'latitude' => true,
        'longitude' => true,
        'location_id' => true,
        'service_id' => true,
        'device_id' => true,
        'created' => true,
        'modified' => true,
        'role' => true,
        'type' => true,
        'location' => true,
        'service' => true,
        'device' => true,
    ];
}
