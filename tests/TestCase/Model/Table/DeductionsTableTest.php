<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DeductionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DeductionsTable Test Case
 */
class DeductionsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\DeductionsTable
     */
    public $Deductions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Deductions',
        'app.Payslips',
        'app.Employees',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Deductions') ? [] : ['className' => DeductionsTable::class];
        $this->Deductions = TableRegistry::getTableLocator()->get('Deductions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Deductions);

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

    /**
     * Test getDeductionTypeOptions method
     *
     * @return void
     */
    public function testGetDeductionTypeOptions()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test getDeductionTotal method
     *
     * @return void
     */
    public function testGetDeductionTotal()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
