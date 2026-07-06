<?php

namespace App\Controller;

/**
 * Attendances Controller
 *
 * @property \App\Model\Table\AttendancesTable $Attendances
 *
 * @method \App\Model\Entity\Attendance[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AttendancesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = ['contain' => ['Employees'],];
        $attendances = $this->paginate($this->Attendances);

        $this->set(compact('attendances'));
    }

    /**
     * View method
     *
     * @param string|null $id Attendance id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $attendance = $this->Attendances->get($id, [
            'contain' => ['Employees'],
        ]);

        $this->set('attendance', $attendance);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $attendance = $this->Attendances->newEntity();
        if ($this->request->is('post')) {
            $attendance = $this->Attendances->patchEntity($attendance, $this->request->getData());
            if ($this->Attendances->save($attendance)) {
                $this->Flash->success(__('The attendance has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The attendance could not be saved. Please, try again.'));
        }
        $employees = $this->Attendances->Employees
           ->find('list', ['keyField' => 'id','valueField' => function ($employee) {
               return $employee->employee_code . ' - ' . $employee->name;
           }])->order(['name' => 'ASC']);
        $this->set(compact('attendance', 'employees'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Attendance id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $attendance = $this->Attendances->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $attendance = $this->Attendances->patchEntity($attendance, $this->request->getData());
            if ($this->Attendances->save($attendance)) {
                $this->Flash->success(__('The attendance has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The attendance could not be saved. Please, try again.'));
        }
        $employees = $this->Attendances->Employees->find('list', ['limit' => 200]);
        $this->set(compact('attendance', 'employees'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Attendance id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $attendance = $this->Attendances->get($id);
        if ($this->Attendances->delete($attendance)) {
            $this->Flash->success(__('The attendance has been deleted.'));
        } else {
            $this->Flash->error(__('The attendance could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }


    //mark()
    public function mark()
    {
        $attendanceDate = $this->request->getQuery('attendance_date');
        $statusFilter = $this->request->getQuery('status_filter');
        $mode = null;
        $employees = [];


        if (!empty($attendanceDate) && $attendanceDate > date('Y-m-d')) {
            $this->Flash->error(__('Attendance date cannot be in the future.'));
            return $this->redirect(['action' => 'mark']);
        }

        if (!empty($attendanceDate)) {

        // Detect mode
            if ($attendanceDate == date('Y-m-d')) {
                $mode = 'today';
            } else {
                $mode = 'history';
            }

            // Load employees
            $employees = $this->Attendances->Employees->getEmployeeAttendance($attendanceDate);

            // Load attendance only in history mode
            if ($mode == 'history') {
                $records = $this->Attendances->getAttendancebyDate($attendanceDate);
                $attendanceRecords = $this->Attendances->getattendanceMap($attendanceDate);

                if (!empty($statusFilter)) {
                    $filteredEmployees = [];
                    foreach ($employees as $employee) {
                        $currentStatus = '';
                        if (isset($attendanceRecords[$employee->id])) {
                            $currentStatus = $attendanceRecords[$employee->id]->status;
                        }
                        if ($statusFilter == 'not_marked') {
                            if ($currentStatus == '') {
                                $filteredEmployees[] = $employee;
                            }
                        } else {
                            if ($currentStatus == $statusFilter) {
                                $filteredEmployees[] = $employee;
                            }
                        }
                    }
                    $employees = $filteredEmployees;
                }
            }
        }


        $this->set(compact(
            'attendanceDate',
            'mode',
            'employees',
            'attendanceRecords',
            'statusFilter'
        ));
    }

    //save attendence
    public function saveAttendance()
    {
        $this->request->allowMethod(['post']);
        $data = $this->request->getData();
        $attendanceDate = $data['attendance_date'];
        $attendanceList = $data['attendance'];

        if ($attendanceDate > date('Y-m-d')) {
            $this->Flash->error(__('Attendance date cannot be in the future.'));
            return $this->redirect(['action' => 'mark']);
        }

        foreach ($attendanceList as $attendance) {
            if (empty($attendance['status'])) {
                continue;
            }
            $existingAttendance = $this->Attendances->find()
     ->where([
        'employee_id' => $attendance['employee_id'],
        'attendance_date' => $attendanceDate])->first();

            if ($existingAttendance) {
                $existingAttendance->status = $attendance['status'];
                $this->Attendances->save($existingAttendance);
            } else {
                $newAttendance = $this->Attendances->newEntity();
                $newAttendance->employee_id = $attendance['employee_id'];
                $newAttendance->attendance_date = $attendanceDate;
                $newAttendance->status = $attendance['status'];
                $this->Attendances->save($newAttendance);
            }
        }
        $this->Flash->success(__('Attendance saved successfully.'));

        return $this->redirect(['action' => 'mark','?' => ['attendance_date' => $attendanceDate]]);
    }


    public function ajaxSaveAttendance()
    {
        $this->request->allowMethod(['post']);
        $this->autoRender = false;
        $data = json_decode($this->request->input(), true);

        //Future Date Validation //=====
        if ($data['attendance_date'] > date('Y-m-d')) {
            echo json_encode([
            'success' => false,
            'message' => 'Attendance date cannot be in the future.']);
            return;
        }//======//

        //ajax save function verify.
        if ($this->Attendances
        ->saveAttendanceStatus(
            $data['employee_id'],
            $data['attendance_date'],
            $data['status']
        )) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode([ 'success' => false]);
        }
    }

    public function report()
    {
        $month = $this->request->getQuery('month');
        $year  = $this->request->getQuery('year');

        $dates = [];
        $employees = [];
        $attendanceMatrix = [];

        if (!empty($month) && !empty($year)) {
            $daysInMonth = cal_days_in_month(
                CAL_GREGORIAN,
                $month,
                $year
            );
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

                $dates[] = $currentDate;
            }
            $attendanceRecords = $this->Attendances
            ->find()
            ->where([
            'MONTH(attendance_date)' => $month,
            'YEAR(attendance_date)'  => $year
            ])->toArray();

            foreach ($attendanceRecords as $record) {
                $attendanceMatrix[$record->employee_id]
                     [$record->attendance_date->format('Y-m-d')]= $record->status;
            }
        }

        $employees = $this->Attendances
        ->Employees->find()
        ->where([
        'status' => 'active'
         ])->order([
        'employee_code' => 'ASC'
        ])->toArray();

        $this->set(compact(
            'month',
            'year',
            'dates',
            'employees',
            'attendanceMatrix'
        ));
    }
}
