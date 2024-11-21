<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ServicesFixture
 */
class ServicesFixture extends TestFixture
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
                'endpoint' => 'Lorem ipsum dolor sit amet',
                'platform_id' => 1,
                'created' => '2024-11-21 19:16:54',
                'modified' => '2024-11-21 19:16:54',
                'role' => 1,
            ],
        ];
        parent::init();
    }
}
