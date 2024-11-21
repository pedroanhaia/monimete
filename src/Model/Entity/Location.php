<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Location Entity
 *
 * @property int $id
 * @property string|null $name
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string|null $description
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 * @property int|null $role
 * @property int|null $city_id
 *
 * @property \App\Model\Entity\City $city
 * @property \App\Model\Entity\DataMetereological[] $data_metereological
 * @property \App\Model\Entity\Device[] $devices
 */
class Location extends Entity
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
        'latitude' => true,
        'longitude' => true,
        'description' => true,
        'created' => true,
        'modified' => true,
        'role' => true,
        'city_id' => true,
        'city' => true,
        'data_metereological' => true,
        'devices' => true,
    ];
}
