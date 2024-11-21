<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'name' => 'Lorem ipsum dolor sit amet',
                'email' => 'Lorem ipsum dolor sit amet',
                'passwotd' => 'Lorem ipsum dolor sit amet',
                'type' => 1,
                'created' => '2024-11-21 19:17:36',
                'modified' => '2024-11-21 19:17:36',
                'role' => 1,
                'city_id' => 1,
            ],
        ];
        parent::init();
    }
}
