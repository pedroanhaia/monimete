<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PlatformsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PlatformsTable Test Case
 */
class PlatformsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PlatformsTable
     */
    protected $Platforms;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.Platforms',
        'app.Logs',
        'app.Services',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Platforms') ? [] : ['className' => PlatformsTable::class];
        $this->Platforms = $this->getTableLocator()->get('Platforms', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Platforms);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\PlatformsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
