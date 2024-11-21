<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * DataSatelliteFixture
 */
class DataSatelliteFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'data_satellite';
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
                'date_time' => '2024-11-21 19:19:13',
                'quality_signal' => 1,
                'latitude' => 1,
                'longitude' => 1,
                'type' => 1,
                'device_id' => 1,
                'created' => '2024-11-21 19:19:13',
                'modified' => '2024-11-21 19:19:13',
                'role' => 1,
            ],
        ];
        parent::init();
    }
}
