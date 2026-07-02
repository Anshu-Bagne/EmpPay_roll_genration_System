<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Employees Model
 *
 * @property \App\Model\Table\DepartmentsTable&\Cake\ORM\Association\BelongsTo $Departments
 * @property \App\Model\Table\DesignationsTable&\Cake\ORM\Association\BelongsTo $Designations
 * @property \App\Model\Table\AttendancesTable&\Cake\ORM\Association\HasMany $Attendances
 * @property &\Cake\ORM\Association\HasMany $Bonuses
 * @property &\Cake\ORM\Association\HasMany $Deductions
 * @property \App\Model\Table\PayslipsTable&\Cake\ORM\Association\HasMany $Payslips
 *
 * @method \App\Model\Entity\Employee get($primaryKey, $options = [])
 * @method \App\Model\Entity\Employee newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Employee[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Employee|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Employee saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Employee patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Employee[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Employee findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class EmployeesTable extends Table
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

        $this->setTable('employees');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Departments', [
            'foreignKey' => 'department_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Designations', [
            'foreignKey' => 'designation_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Attendances', [
            'foreignKey' => 'employee_id',
        ]);
        $this->hasMany('Bonuses', [
            'foreignKey' => 'employee_id',
        ]);
        $this->hasMany('Deductions', [
            'foreignKey' => 'employee_id',
        ]);
        $this->hasMany('Payslips', [
            'foreignKey' => 'employee_id',
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
          ->scalar('employee_code')
           ->maxLength('employee_code', 20)
           ->allowEmptyString('employee_code', 'create');
        $validator
    ->scalar('name')
    ->maxLength('name', 100)
    ->requirePresence('name', 'create')
    ->notEmptyString('name', 'Please enter employee name')
    ->regex(
        'name',
        '/^[A-Za-z ]+$/',
        'Only alphabets and spaces are allowed.'
    );

        $validator
    ->numeric('base_salary', 'Please enter a valid salary.')
    ->greaterThan(
        'base_salary',
        0,
        'Salary must be greater than zero.'
    )
    ->requirePresence('base_salary', 'create')
    ->notEmptyString('base_salary');

       $validator
    ->numeric('pf_amount', 'Enter a valid PF amount.')
    ->greaterThanOrEqual(
        'pf_amount',
        0,
        'PF amount cannot be negative.'
    )
    ->requirePresence('pf_amount', 'create')
    ->notEmptyString('pf_amount');

        $validator
    ->numeric('tds_amount', 'Enter a valid TDS amount.')
    ->greaterThanOrEqual(
        'tds_amount',
        0,
        'TDS amount cannot be negative.'
    )
    ->requirePresence('tds_amount', 'create')
    ->notEmptyString('tds_amount');

     $validator
    ->date('joining_date')
    ->requirePresence('joining_date', 'create')
    ->notEmptyDate('joining_date')
    ->add('joining_date', 'notFuture', [
        'rule' => function ($value) {

            if ($value instanceof \Cake\I18n\FrozenDate) {
                return $value <= \Cake\I18n\FrozenDate::today();
            }

            return true;
        },
        'message' => 'Joining date cannot be in the future.'
    ]);

       $validator
    ->email('email', false, 'Please enter a valid email.')
    ->requirePresence('email', 'create')
    ->notEmptyString('email');

    $validator
    ->scalar('mobile')
    ->maxLength('mobile', 10)
    ->requirePresence('mobile', 'create')
    ->notEmptyString('mobile')
    ->regex(
        'mobile',
        '/^[6-9][0-9]{9}$/',
        'Enter a valid 10-digit mobile number.'
    );

    $validator
      ->scalar('status')
      ->inList(
        'status',
        ['active', 'inactive'],
        'Please select a valid status.'
    )
    ->allowEmptyString('status');

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
    $rules->add($rules->isUnique(['email']));
    $rules->add($rules->isUnique(['mobile']));
    $rules->add($rules->isUnique(['employee_code']));

    $rules->add($rules->existsIn(['department_id'], 'Departments'));
    $rules->add($rules->existsIn(['designation_id'], 'Designations'));

    return $rules;
}

    public function beforeSave($event, $entity, $options)
{
    if ($entity->isNew() && empty($entity->employee_code)) {

        $lastEmployee = $this->find()
            ->order(['id' => 'DESC'])
            ->first();

        $nextNumber = $lastEmployee ? $lastEmployee->id + 1 : 1;

        $entity->employee_code =
            'EMP' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
}
