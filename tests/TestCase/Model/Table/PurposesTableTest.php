<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PurposesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PurposesTable Test Case
 */
class PurposesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PurposesTable
     */
    public $Purposes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Purposes',
        'app.Bios',
        'app.Headshots'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Purposes') ? [] : ['className' => PurposesTable::class];
        $this->Purposes = TableRegistry::getTableLocator()->get('Purposes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Purposes);

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
}
