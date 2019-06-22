<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\Users2Table;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\Users2Table Test Case
 */
class Users2TableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\Users2Table
     */
    public $Users2;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Users2'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Users2') ? [] : ['className' => Users2Table::class];
        $this->Users2 = TableRegistry::getTableLocator()->get('Users2', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Users2);

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
