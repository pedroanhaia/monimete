<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Service Entity
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $type
 * @property string|null $endpoint
 * @property int|null $platform_id
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 * @property int|null $role
 *
 * @property \App\Model\Entity\Platform $platform
 * @property \App\Model\Entity\DataMetereological[] $data_metereological
 */
class Service extends Entity
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
        'endpoint' => true,
        'platform_id' => true,
        'created' => true,
        'modified' => true,
        'role' => true,
        'platform' => true,
        'data_metereological' => true,
    ];
}
