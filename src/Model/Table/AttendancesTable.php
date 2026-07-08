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

        $validator->add(
            'attendance_date',
            'notFuture',
            ['rule' => function ($value) {
                return $value <= date('Y-m-d');
            },'message' => 'Attendance date cannot be in the future.'
           ]
        );

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

    public function getAttendancebyDate($attendanceDate)
    {
        return $this->find()
            ->where(['attendance_date'=>$attendanceDate])
            ->all();
    }

    public function getMonthlyattendance($month, $year)
    {
        $attendanceMatrix = [];
        $attendanceRecords= $this->find()
            ->where([
            'MONTH(attendance_date)' => $month,
            'YEAR(attendance_date)'  => $year
            ])->toArray();

        foreach ($attendanceRecords as $record) {
            $attendanceMatrix[$record->employee_id]
            [$record->attendance_date->format('Y-m-d')]= $record->status;
        }
        return $attendanceMatrix;
    }

    // public function saveAttendanceStatus(array $data)
    // {
    //     $attendance = $this->find()
    //          ->where(['employee_id' =>$data['$employee_id'],'attendance_date' => $data['$attendance_date']])
    //          ->first();

    //     if (!$attendance) {
    //         $attendance = $this->newEntity();
    //     }
    //     //patchdata rather
    //     $attendance= $this->patchEntity($attendance, $data);
    //     $result =$this->save($attendance);
    //     if (!$result) {
    //         debug($attendance->getErrors());
    //     }
    //     return $result;
    // }

    public function saveAttendanceStatus(array $data)
    {
        $attendance = $this->find()
        ->where([
            'employee_id' => $data['employee_id'],
            'attendance_date' => $data['attendance_date']
        ])
        ->first();

        if (!$attendance) {
            $attendance = $this->newEntity();
        }
        $attendance = $this->patchEntity($attendance, $data);
        return  $this->save($attendance);
    }

    public function getAttendanceSummary($employeeId, $month, $year)
    {
        $summary =['present' => 0,'leave' => 0,'absent' =>0];

        $records = $this->find()
        ->select([
            'status',
            'total' => $this->find()->func()->count('*')
        ])
        ->where([
            'employee_id' => $employeeId,
            'MONTH(attendance_date)' => $month,
            'YEAR(attendance_date)' => $year
        ])
        ->group('status')
        ->toArray();

        foreach ($records as $record) {
            $summary[$record->status] = $record->total;
        }

        return $summary;
    }

    public function getMissingAttendanceDates($employeeId, $month, $year)
    {
        $missingDates = [];
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDate = sprintf(
                '%04d-%02d-%02d',
                $year,
                $month,
                $day
            );

            // Skip Sundays
            if (date('w', strtotime($currentDate)) == 0) {
                continue;
            }
            $exists = $this->find()
            ->where([
                'employee_id' => $employeeId,
                'attendance_date' => $currentDate
            ])
            ->count();

            if ($exists == 0) {
                $missingDates[] = $currentDate;
            }
        }

        return $missingDates;
    }
}
