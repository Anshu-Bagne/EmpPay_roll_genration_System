<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Payslip Entity
 *
 * @property int $id
 * @property int $employee_id
 * @property int $payroll_month
 * @property string $payroll_year
 * @property int $working_days
 * @property int $present_days
 * @property int $leave_days
 * @property int $absent_days
 * @property float $base_salary
 * @property float $salary_earned
 * @property float|null $bonus_total
 * @property float|null $deduction_total
 * @property float $net_salary
 * @property \Cake\I18n\FrozenDate $payment_date
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Employee $employee
 * @property \App\Model\Entity\Bonus[] $bonuses
 * @property \App\Model\Entity\Deduction[] $deductions
 */
class Payslip extends Entity
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
        'employee_id' => true,
        'payroll_month' => true,
        'payroll_year' => true,
        'working_days' => true,
        'present_days' => true,
        'leave_days' => true,
        'absent_days' => true,
        'base_salary' => true,
        'salary_earned' => true,
        'bonus_total' => true,
        'deduction_total' => true,
        'net_salary' => true,
        'payment_date' => true,
        'created' => true,
        'modified' => true,
        'employee' => true,
        'bonuses' => true,
        'deductions' => true,
    ];
}
