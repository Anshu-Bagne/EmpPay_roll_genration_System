<?php

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Bonuses Model
 *
 * @property \App\Model\Table\EmployeesTable&\Cake\ORM\Association\BelongsTo $Employees
 *
 * @method \App\Model\Entity\Bonus get($primaryKey, $options = [])
 * @method \App\Model\Entity\Bonus newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Bonus[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Bonus|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Bonus saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Bonus patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Bonus[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Bonus findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BonusesTable extends Table
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

        $this->setTable('bonuses');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Employees', [
            'foreignKey' => 'employee_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('Payslips', [
            'foreignKey'=>'payslip_id'
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
        // Employee
        $validator
           ->nonNegativeInteger('employee_id')
           ->requirePresence('employee_id', 'create')
           ->notEmptyString('employee_id', 'Please select an employee.');

        $validator
    ->scalar('type')
    ->requirePresence('type', 'create')
    ->notEmptyString('type')
    ->inList(
        'type',
        ['Performance', 'Festival'],
        'Invalid bonus type.'
    );
        $validator
          ->numeric('amount', 'Enter a valid bonus amount.')
          ->greaterThan('amount', 0, 'Bonus amount must be greater than zero.')
           ->requirePresence('amount', 'create')
              ->notEmptyString('amount');

        $validator
    ->integer('payroll_month')
    ->range('payroll_month', [1, 12], 'Month must be between 1 and 12.')
    ->requirePresence('payroll_month', 'create')
    ->notEmptyString('payroll_month');

        $validator
    ->integer('payroll_year')
    ->greaterThanOrEqual(
        'payroll_year',
        2024,
        'Invalid payroll year.'
    )
    ->requirePresence('payroll_year', 'create')
    ->notEmptyString('payroll_year');

        $validator
    ->scalar('remarks')
    ->maxLength(
        'remarks',
        255,
        'Remarks cannot exceed 255 characters.'
    )
    ->allowEmptyString('remarks');

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
        //$rules->add(
        // $rules->isUnique(
        //     ['employee_id','payroll_month','payroll_year','type'],
        //     'This bonus already exists for the employee.'
        // )
        // );
        return $rules;
    }

    public function getmonthlyBonus($EmployeeId, $month, $year)
    {
        $bonus= $this->find()
       ->where([
        'employee_id'=>$EmployeeId,
        'payroll_month'=>$month,
        'payroll_year'=>$year
       ])
       ->select(['total_bonus'=>$this->find()->func()->sum('amount')])
       ->first();

        if ($bonus && $bonus->total_bonus) {
            return $bonus->total_bonus;
        }
        return 0;
    }

    public function payrollExists($month, $year)
    {
        return $this
               ->Employees->Payslips->find()
               ->where([
                      'payroll_month' =>$month,
                      'payroll_year'  => $year
               ])
               ->count()>0;
    }

    public function saveBonus(array $data)
    {
        $bonus = $this->newEntity();
        $bonus = $this->patchEntity($bonus, $data);
        return $this->save($bonus);
    }

    public function validateBonusMonth($month, $year)
    {
        $currentMonth = date('n');
        $currentYear = date('Y');

        if (
        $year < $currentYear ||
        ($year == $currentYear && $month < $currentMonth)
    ) {
            return [
            'success' => false,
            'message' => 'Bonus cannot be assigned for a past payroll month.'
        ];
        }

        return ['success' => true];
    }

    public function getBonusTypeOptions()
    {
        return [
        'Performance' => 'Performance',
        'Festival'    => 'Festival'
    ];
    }
    public function getBonusTotal(array $bonuses)
    {
        return array_sum(array_column($bonuses, 'amount'));
    }
}
