<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Platform Entity
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $type
 * @property string|null $url
 * @property \Cake\I18n\DateTime|null $last_update
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 * @property int|null $role
 * @property string|null $powered
 *
 * @property \App\Model\Entity\Log[] $logs
 * @property \App\Model\Entity\Service[] $services
 */
class Platform extends Entity
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
        'url' => true,
        'last_update' => true,
        'created' => true,
        'modified' => true,
        'role' => true,
        'powered' => true,
        'logs' => true,
        'services' => true,
    ];
}
