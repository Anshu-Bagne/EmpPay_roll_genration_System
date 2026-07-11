<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BonusesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BonusesTable Test Case
 */
class BonusesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\BonusesTable
     */
    public $Bonuses;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Bonuses',
        'app.Employees',
        'app.Payslips',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Bonuses') ? [] : ['className' => BonusesTable::class];
        $this->Bonuses = TableRegistry::getTableLocator()->get('Bonuses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Bonuses);

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
     * Test getmonthlyBonus method
     *
     * @return void
     */
    public function testGetmonthlyBonus()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test payrollExists method
     *
     * @return void
     */
    public function testPayrollExists()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test saveBonus method
     *
     * @return void
     */
    public function testSaveBonus()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validateBonusMonth method
     *
     * @return void
     */
    public function testValidateBonusMonth()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test getBonusTypeOptions method
     *
     * @return void
     */
    public function testGetBonusTypeOptions()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test getBonusTotal method
     *
     * @return void
     */
    public function testGetBonusTotal()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
