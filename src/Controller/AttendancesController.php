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
        $employees = [];

        //future date validation
        if (!empty($attendanceDate) && $attendanceDate > date('Y-m-d')) {
            $this->Flash->error(__('Attendance date cannot be in the future.'));
            return $this->redirect(['action' => 'mark']);
        }

        if (!empty($attendanceDate)) {
            // Load employees and attendece acc to the status filter
            $employees = $this->Attendances->Employees->getEmployeeAttendance($attendanceDate, $statusFilter);
        }

        $this->set(compact(
            'attendanceDate',
            'employees',
            'statusFilter'
        ));
    }

    public function ajaxSaveAttendance()
    {
        $this->request->allowMethod(['post']);
        $this->autoRender = false;
        $data = json_decode($this->request->input(), true);

        //Future Date Validation //=====
        if ($data['attendance_date'] > date('Y-m-d')) {
            return $this->response->withType('applicaiton/json')
                   ->withStringBody(json_encode([
                       'success'=>false,
                       'message'=>'Date cannot be in future!'
                   ]));
        }

        //ajax save function verify.
        if ($this->Attendances
        ->saveAttendanceStatus(
            $data['employee_id'],
            $data['attendance_date'],
            $data['status']
        )) {
            return $this->response->withType('application/json')
            ->withStringBody(json_encode(['success' => true]));
        } else {
            return $this->response->withType('application/json')
            ->withStringBody(json_encode(['success' => false]));
        }
    }

    public function report()
    {
        $month = $this->request->getQuery('month');
        $year  = $this->request->getQuery('year');
        $dates = [];
        $employees = [];


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
            $attendanceMatrix = $this->Attendances->getMonthlyattendance($month, $year);
        }

        $currdate = date('y-m-t', strtotime($year.'-'.$month.'-01'));
        $employees =$this->Attendances->Employees->getActiveEmployees($currdate);

        $this->set(compact(
            'month',
            'year',
            'dates',
            'employees',
            'attendanceMatrix'
        ));
    }
}
