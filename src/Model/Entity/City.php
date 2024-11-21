<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * City Entity
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $obs
 * @property string|null $cod_ibge
 * @property string|null $description
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 * @property int|null $role
 *
 * @property \App\Model\Entity\Location[] $locations
 * @property \App\Model\Entity\User[] $users
 */
class City extends Entity
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
        'obs' => true,
        'cod_ibge' => false,
        'description' => true,
        'created' => true,
        'modified' => true,
        'role' => true,
        'locations' => true,
        'users' => true,
    ];
}
