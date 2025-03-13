<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PlatformsFixture
 */
class PlatformsFixture extends TestFixture
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
                'type' => 1,
                'url' => 'Lorem ipsum dolor sit amet',
                'last_update' => '2024-11-21 19:15:58',
                'created' => '2024-11-21 19:15:58',
                'modified' => '2024-11-21 19:15:58',
                'role' => 1,
                'powered' => 'Lorem ipsum dolor sit amet',
            ],
        ];
        parent::init();
    }
}
