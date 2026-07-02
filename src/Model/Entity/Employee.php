<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Employee Entity
 *
 * @property int $id
 * @property string $employee_code
 * @property string $name
 * @property int $department_id
 * @property int $designation_id
 * @property float $base_salary
 * @property float $pf_amount
 * @property float $tds_amount
 * @property \Cake\I18n\FrozenDate $joining_date
 * @property string $email
 * @property string $mobile
 * @property string $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Department $department
 * @property \App\Model\Entity\Designation $designation
 * @property \App\Model\Entity\Attendance[] $attendances
 * @property \App\Model\Entity\Payslip[] $payslips
 */
class Employee extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'employee_code' => true,
        'name' => true,
        'department_id' => true,
        'designation_id' => true,
        'base_salary' => true,
        'pf_amount' => true,
        'tds_amount' => true,
        'joining_date' => true,
        'email' => true,
        'mobile' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'department' => true,
        'designation' => true,
        'attendances' => true,
        'bonuses' => true,
        'deductions' => true,
        'payslips' => true,
    ];
}
