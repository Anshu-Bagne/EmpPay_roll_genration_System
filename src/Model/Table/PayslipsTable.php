<?php

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Payslips Model
 *
 * @property \App\Model\Table\EmployeesTable&\Cake\ORM\Association\BelongsTo $Employees
 *
 * @method \App\Model\Entity\Payslip get($primaryKey, $options = [])
 * @method \App\Model\Entity\Payslip newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Payslip[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Payslip|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Payslip saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Payslip patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Payslip[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Payslip findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PayslipsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('payslips');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Employees', [
            'foreignKey' => 'employee_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->nonNegativeInteger('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->requirePresence('payroll_month', 'create')
            ->notEmptyString('payroll_month');

        $validator
            ->scalar('payroll_year')
            ->requirePresence('payroll_year', 'create')
            ->notEmptyString('payroll_year');

        $validator
            ->nonNegativeInteger('working_days')
            ->notEmptyString('working_days');

        $validator
           ->nonNegativeInteger('leave_days')
           ->notEmptyString('leave_days');

        $validator
          ->decimal('salary_earned')
          ->notEmptyString('salary_earned');

        $validator
          ->decimal('pf_amount')
            ->notEmptyString('pf_amount');

        $validator
         ->decimal('tds_amount')
         ->notEmptyString('tds_amount');

        $validator
         ->decimal('unpaid_leave_deduction')
        ->notEmptyString('unpaid_leave_deduction');

        $validator
            ->nonNegativeInteger('present_days')
            ->notEmptyString('present_days');

        $validator
            ->decimal('base_salary')
            ->requirePresence('base_salary', 'create')
            ->notEmptyString('base_salary');

        $validator
            ->decimal('bonus_total')
            ->notEmptyString('bonus_total');

        $validator
            ->decimal('deduction_total')
            ->notEmptyString('deduction_total');

        $validator
            ->decimal('net_salary')
            ->requirePresence('net_salary', 'create')
            ->notEmptyString('net_salary');

        $validator
            ->date('payment_date')
            ->requirePresence('payment_date', 'create')
            ->notEmptyDate('payment_date');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['employee_id'], 'Employees'));

        return $rules;
    }

    public function createPayslipEntity($employee, $month, $year, $workingDays, $paymentDate)
    {
        $payslip = $this->Payslips->newEntity();
        $payslip = $this->Payslips->patchEntity($payslip, [
            'employee_id'            => $employee->id,
            'payroll_month'          => $payrollMonth,
            'payroll_year'           => $payrollYear,
            'working_days'           => $workingDays,
            'present_days'           => $employee->present_days,
            'leave_days'             => $employee->leave_days,
            'base_salary'            => $employee->monthly_salary,
            'salary_earned'          => $employee->salary_earned,
            'bonus_total'            => $employee->bonus,
            'pf_amount'              => $employee->pf,
            'tds_amount'             => $employee->tds,
            'deduction_total'        => $employee->total_deduction,
            'unpaid_leave_deduction' => $employee->unpaid_leave_deduction,
            'net_salary'             => $employee->net_salary,
            'payment_date'           => $paymentDate
             ]);
        return $payslip;
    }
}
