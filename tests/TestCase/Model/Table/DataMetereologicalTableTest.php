<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DataMetereologicalTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DataMetereologicalTable Test Case
 */
class DataMetereologicalTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\DataMetereologicalTable
     */
    protected $DataMetereological;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.DataMetereological',
        'app.Locations',
        'app.Services',
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
        $config = $this->getTableLocator()->exists('DataMetereological') ? [] : ['className' => DataMetereologicalTable::class];
        $this->DataMetereological = $this->getTableLocator()->get('DataMetereological', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->DataMetereological);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\DataMetereologicalTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\DataMetereologicalTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
