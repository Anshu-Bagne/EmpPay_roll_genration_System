<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Bonus Entity
 *
 * @property int $id
 * @property int $employee_id
 * @property string $type
 * @property float $amount
 * @property int $payroll_month
 * @property string $payroll_year
 * @property string|null $remarks
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Employee $employee
 */
class Bonus extends Entity
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
        'type' => true,
        'amount' => true,
        'payroll_month' => true,
        'payroll_year' => true,
        'remarks' => true,
        'created' => true,
        'modified' => true,
        'employee' => true,
    ];
}
