<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Deduction Entity
 *
 * @property int $id
 * @property int $payslip_id
 * @property string $type
 * @property float $amount
 * @property string|null $remarks
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Payslip $payslip
 * @property \App\Model\Entity\Employee $employee
 */
class Deduction extends Entity
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
        'payslip_id' => true,
        'type' => true,
        'amount' => true,
        'remarks' => true,
        'created' => true,
        'modified' => true,
        'payslip' => true,
        'employee' => true,
    ];
}
