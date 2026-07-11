<?php

namespace App\Controller;

/**
 * Payslips Controller
 *
 * @property \App\Model\Table\PayslipsTable $Payslips
 *
 * @method \App\Model\Entity\Payslip[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PayslipsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */

    public function initialize(): void
    {
        parent::initialize();

        $this->loadModel('Attendances');
    }

    public function index()
    {
        $this->paginate = [
            'contain' => ['Employees'],
        ];
        $payslips = $this->paginate($this->Payslips);

        $this->set(compact('payslips'));
    }

    /**
     * View method
     *
     * @param string|null $id Payslip id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $payslip = $this->Payslips->get($id, [
            'contain' => ['Employees', 'Bonuses', 'Deductions'],
        ]);

        $this->set('payslip', $payslip);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $payslip = $this->Payslips->newEntity();

        if ($this->request->is('post')) {
            $payslip = $this->Payslips->patchEntity(
                $payslip,
                $this->request->getData(),
                [
                'associated' => [
                    'Bonuses',
                    'Deductions'
                ]
            ]
            );

            if ($this->Payslips->save($payslip)) {
                $this->Flash->success(__('Payslip saved successfully.'));

                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('Unable to save payslip.'));
        }

        $employees = $this->Payslips
        ->Employees
        ->find()
        ->select(['id','employee_code','name'])
        ->where(['status'=>'active'])
        ->order(['employee_code'])
        ->all()
        ->combine('id', function ($employee) {
            return $employee->employee_code .
                   ' - ' .
                   $employee->name;
        })
        ->toArray();

        $this->set(compact(
            'payslip',
            'employees'
        ));
    }

    /**
     * Edit method
     *
     * @param string|null $id Payslip id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $payslip = $this->Payslips->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $payslip = $this->Payslips->patchEntity($payslip, $this->request->getData());
            if ($this->Payslips->save($payslip)) {
                $this->Flash->success(__('The payslip has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The payslip could not be saved. Please, try again.'));
        }
        $employees = $this->Payslips->Employees->find('list', ['limit' => 200]);
        $this->set(compact('payslip', 'employees'));
    }

    private function calculateEmployeePayroll(
        $employee,
        $workingDays,
        $presentDays,
        $leaveDays,
        $absentDays
    ) {

    // Database stores Annual Salary
        $baseSalary = $employee->base_salary;

        $month_salary= $baseSalary /12;

        $paidDays = $presentDays + $leaveDays;

        $salaryEarned = 0;

        if ($workingDays > 0) {
            $salaryEarned =
            ($month_salary / $workingDays) * $paidDays;
        }

        return [

        'base_salary'      => round($baseSalary, 2),

        'working_days'     => $workingDays,

        'present_days'     => $presentDays,

        'leave_days'       => $leaveDays,

        'absent_days'      => $absentDays,

        'salary_earned'    => round($salaryEarned, 2),

        'bonus_total'      => 0,

        'deduction_total'  => 0,

        'net_salary'       => round($salaryEarned, 2)

    ];
    }

    /**
     * Delete method
     *
     * @param string|null $id Payslip id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $payslip = $this->Payslips->get($id);
        if ($this->Payslips->delete($payslip)) {
            $this->Flash->success(__('The payslip has been deleted.'));
        } else {
            $this->Flash->error(__('The payslip could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function getEmployeePayrollDetails()
    {
        $this->request->allowMethod(['post']);
        $this->autoRender = false;

        $employeeId = $this->request->getData('employee_id');
        $month = $this->request->getData('payroll_month');
        $year = $this->request->getData('payroll_year');

        $employee = $this->Payslips->Employees
        ->find()
        ->contain(['Departments','Designations'])
        ->where(['Employees.id' => $employeeId])
        ->first();

        $attendance = $this->Attendances->getAttendanceSummary($employeeId, $month, $year);
        $workingDays = $this->Attendances->getWorkingDays($month, $year);

        $payroll = $this->calculateEmployeePayroll(
            $employee,
            $workingDays,
            $attendance['present_days'] ?? 0,
            $attendance['leave_days'] ?? 0,
            $attendance['absent_days'] ?? 0
        );
        return $this->response
        ->withType('application/json')
        ->withStringBody(json_encode([
            'success' => true,
        'employee_code' => $employee->employee_code,
        'department' => $employee->department->name,
        'designation' => $employee->designation->name,
        'joining_date' => $employee->joining_date->format('d-M-Y'),
        'base_salary' => $payroll['base_salary'],
        'working_days' => $payroll['working_days'],
        'present_days' => $payroll['present_days'],
        'leave_days' => $payroll['leave_days'],
        'absent_days' => $payroll['absent_days'],
        'salary_earned' => $payroll['salary_earned'],
        'bonus_total' => $payroll['bonus_total'],
        'deduction_total' => $payroll['deduction_total'],
        'net_salary' => $payroll['net_salary']
        ]));
    }
}
