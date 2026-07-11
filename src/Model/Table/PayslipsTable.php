<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Payslips Model
 *
 * @property \App\Model\Table\EmployeesTable&\Cake\ORM\Association\BelongsTo $Employees
 * @property \App\Model\Table\BonusesTable&\Cake\ORM\Association\HasMany $Bonuses
 * @property \App\Model\Table\DeductionsTable&\Cake\ORM\Association\HasMany $Deductions
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
        $this->hasMany('Bonuses', [
            'foreignKey' => 'payslip_id',
        ]);
        $this->hasMany('Deductions', [
            'foreignKey' => 'payslip_id',
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
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->requirePresence('payroll_month', 'create')
            ->notEmptyString('payroll_month');

        $validator
            ->scalar('payroll_year')
            ->requirePresence('payroll_year', 'create')
            ->notEmptyString('payroll_year');

        $validator
            ->integer('working_days')
            ->requirePresence('working_days', 'create')
            ->notEmptyString('working_days');

        $validator
            ->integer('present_days')
            ->requirePresence('present_days', 'create')
            ->notEmptyString('present_days');

        $validator
            ->integer('leave_days')
            ->requirePresence('leave_days', 'create')
            ->notEmptyString('leave_days');

        $validator
            ->integer('absent_days')
            ->requirePresence('absent_days', 'create')
            ->notEmptyString('absent_days');

        $validator
            ->decimal('base_salary')
            ->requirePresence('base_salary', 'create')
            ->notEmptyString('base_salary');

        $validator
            ->decimal('salary_earned')
            ->requirePresence('salary_earned', 'create')
            ->notEmptyString('salary_earned');

        $validator
            ->decimal('bonus_total')
            ->allowEmptyString('bonus_total');

        $validator
            ->decimal('deduction_total')
            ->allowEmptyString('deduction_total');

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
}
