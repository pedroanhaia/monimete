<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DataSatelliteTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DataSatelliteTable Test Case
 */
class DataSatelliteTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\DataSatelliteTable
     */
    protected $DataSatellite;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.DataSatellite',
        'app.Devices',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('DataSatellite') ? [] : ['className' => DataSatelliteTable::class];
        $this->DataSatellite = $this->getTableLocator()->get('DataSatellite', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->DataSatellite);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\DataSatelliteTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\DataSatelliteTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
