<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BiosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BiosTable Test Case
 */
class BiosTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\BiosTable
     */
    public $Bios;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Bios',
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
        $config = TableRegistry::getTableLocator()->exists('Bios') ? [] : ['className' => BiosTable::class];
        $this->Bios = TableRegistry::getTableLocator()->get('Bios', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Bios);

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
