<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HeadshotsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HeadshotsTable Test Case
 */
class HeadshotsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\HeadshotsTable
     */
    public $Headshots;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Headshots',
        'app.Users',
        'app.Purposes'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Headshots') ? [] : ['className' => HeadshotsTable::class];
        $this->Headshots = TableRegistry::getTableLocator()->get('Headshots', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Headshots);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
