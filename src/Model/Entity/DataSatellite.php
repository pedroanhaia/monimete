<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * DataSatellite Entity
 *
 * @property int $id
 * @property \Cake\I18n\DateTime|null $date_time
 * @property float|null $quality_signal
 * @property float|null $latitude
 * @property float|null $longitude
 * @property int|null $type
 * @property int|null $device_id
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 * @property int|null $role
 *
 * @property \App\Model\Entity\Device $device
 */
class DataSatellite extends Entity
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
        'quality_signal' => true,
        'latitude' => true,
        'longitude' => true,
        'type' => true,
        'device_id' => true,
        'created' => true,
        'modified' => true,
        'role' => true,
        'device' => true,
    ];
}
