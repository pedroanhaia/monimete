<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * DataMetereologicalFixture
 */
class DataMetereologicalFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'data_metereological';
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
                'date_time' => '2024-11-21 19:18:56',
                'temperature' => 1,
                'humidity' => 1,
                'precipitation' => 1,
                'wind_direction' => 'Lorem ipsum dolor sit amet',
                'wind_speed' => 1,
                'latitude' => 1,
                'longitude' => 1,
                'location_id' => 1,
                'service_id' => 1,
                'device_id' => 1,
                'created' => '2024-11-21 19:18:56',
                'modified' => '2024-11-21 19:18:56',
                'role' => 1,
                'type' => 1,
            ],
        ];
        parent::init();
    }
}
