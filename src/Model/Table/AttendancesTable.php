<?php

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Attendances Model
 *
 * @property \App\Model\Table\EmployeesTable&\Cake\ORM\Association\BelongsTo $Employees
 *
 * @method \App\Model\Entity\Attendance get($primaryKey, $options = [])
 * @method \App\Model\Entity\Attendance newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Attendance[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Attendance|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Attendance saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Attendance patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Attendance[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Attendance findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AttendancesTable extends Table
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

        $this->setTable('attendances');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Employees', ['foreignKey' => 'employee_id','joinType' => 'INNER',]);
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
            ->date('attendance_date')
            ->requirePresence('attendance_date', 'create')
            ->notEmptyDate('attendance_date');

        $validator
            ->scalar('status')
            ->requirePresence('status', 'create')
            ->notEmptyString('status');

        return $validator;

        $validator->add(
            'attendance_date',
            'notFuture',
            ['rule' => function ($value) {
                return $value <= date('Y-m-d');
            },'message' => 'Attendance date cannot be in the future.'
        ]
        );
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

    public function getAttendancebyDate($attendanceDate)
    {
        return $this->find()
            ->where(['attendance_date'=>$attendanceDate])
            ->all();
    }

    public function getattendanceMap($attendanceDate)
    {
        $records= $this->getAttendancebyDate($attendanceDate);
        $attendanceMap=[];

        foreach ($records as $record) {
            $attendanceMap[$record->employee_id]=$record;
        }
        return $attendanceMap;
    }


    public function saveAttendanceStatus($employeeId, $attendanceDate, $status)
    {
        $attendance = $this->find()
             ->where(['employee_id' => $employeeId,'attendance_date' => $attendanceDate])
             ->first();

        if (!$attendance) {
            $attendance = $this->newEntity();
            $attendance->employee_id = $employeeId;
            $attendance->attendance_date = $attendanceDate;
        }
        $attendance->status = $status;
        return $this->save($attendance);
    }
}
