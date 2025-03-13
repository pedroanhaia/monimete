<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Log Entity
 *
 * @property int $id
 * @property \Cake\I18n\DateTime|null $date_time
 * @property string|null $message
 * @property string|null $status
 * @property int|null $type
 * @property int|null $device_id
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 * @property int|null $role
 * @property int|null $platform_id
 *
 * @property \App\Model\Entity\Device $device
 * @property \App\Model\Entity\Platform $platform
 */
class Log extends Entity
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
        'message' => true,
        'status' => true,
        'type' => true,
        'device_id' => true,
        'created' => true,
        'modified' => true,
        'role' => true,
        'platform_id' => true,
        'device' => true,
        'platform' => true,
    ];
}
