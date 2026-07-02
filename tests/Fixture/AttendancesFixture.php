<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AttendancesFixture
 */
class AttendancesFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'employee_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'attendance_date' => ['type' => 'date', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'status' => ['type' => 'string', 'length' => null, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '', 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'uq_employee_attendance' => ['type' => 'unique', 'columns' => ['employee_id', 'attendance_date'], 'length' => []],
            'fk_attendance_employee' => ['type' => 'foreign', 'columns' => ['employee_id'], 'references' => ['employees', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd
    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'employee_id' => 1,
                'attendance_date' => '2026-06-29',
                'status' => 'Lorem ipsum dolor sit amet',
                'created' => 1782730592,
                'modified' => 1782730592,
            ],
        ];
        parent::init();
    }
}
