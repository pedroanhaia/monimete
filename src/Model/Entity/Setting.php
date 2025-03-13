<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Setting Entity
 *
 * @property int $id
 * @property int|null $device_id
 * @property string|null $name
 * @property string|null $value
 * @property string|null $description
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 * @property int|null $type
 * @property int|null $role
 *
 * @property \App\Model\Entity\Device $device
 */
class Setting extends Entity
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
        'device_id' => true,
        'name' => true,
        'value' => true,
        'description' => true,
        'created' => true,
        'modified' => true,
        'type' => true,
        'role' => true,
        'device' => true,
    ];
}
