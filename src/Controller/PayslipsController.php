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
            $data = $this->request->getData(); // Get all submitted form data


            // Check existing playlist
            $existingPayslip = $this->Payslips
            ->find()
            ->where([
                'employee_id'   => $data['employee_id'],
                'payroll_month' => $data['payroll_month'],
                'payroll_year'  => $data['payroll_year']
            ])
            ->first();

            if ($existingPayslip) {
                $this->Flash->error(__('Payslip has already been generated for this employee.'));
                return $this->redirect(['action' => 'add']);
            }

            // Create entity: associated Bonus & Deduction records
            $payslip = $this->Payslips->patchEntity(
                $payslip,
                $data,
                ['associated' => [
                    'Bonuses',
                    'Deductions']
                ]
            );

            // Save
            if ($this->Payslips->save($payslip)) {
                $this->Flash->success(__('Payslip has been generated successfully.'));
                return $this->redirect(['action' => 'index']);
            }
            // Show validation errors during development
            debug($payslip->getErrors());

            $this->Flash->error(__('Unable to save the payslip.'));
        }

        // Employee Dropdown
        $employees = $this->Payslips
        ->Employees
        ->find()
        ->select([
            'id',
            'employee_code',
            'name'])
        ->where([
            'status' => 'active'])
        ->order([
            'employee_code' => 'ASC'])
        ->all()
        ->combine('id', function ($employee) {
            return $employee->employee_code . ' - ' . $employee->name;
        })
        ->toArray();

        // Bonus Options
        $bonusOptions = [
        'Performance' => 'Performance',
        'Festival'    => 'Festival',
        'Overtime'    => 'Overtime',
        'Incentive'   => 'Incentive'
        ];

        // Deduction Options
        $deductionOptions = [
        'Loan Recovery'    => 'Loan Recovery',
        'Professional Tax' => 'Professional Tax',
        'Advance'          => 'Advance',
        'Other'            => 'Other'
        ];

        $this->set(compact(
            'payslip',
            'employees',
            'bonusOptions',
            'deductionOptions'
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

        $payroll = $this->Payslips->calculateEmployeePayroll(
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

    public function generate()
    {
        // Read Filters
        $payrollMonth = $this->request->getQuery('payroll_month');
        $payrollYear  = $this->request->getQuery('payroll_year');
        $paymentDate  = $this->request->getQuery('payment_date', date('Y-m-d'));

        // Default Values
        $employees = [];
        $workingDays = 0;
        $canGeneratePayroll = false;
        $incompleteEmployees = [];
        $payrollExists = false;
        $existingPayroll = null;

        $currentMonth = date('n');
        $currentYear = date('Y');

        /*
         * Validate Future Payroll Month
         */
        if (!empty($payrollMonth) && !empty($payrollYear)) {
            if (
            $payrollYear > $currentYear ||
            (
                $payrollYear == $currentYear &&
                $payrollMonth > $currentMonth
            )
        ) {
                $this->Flash->error(
                    __('Payroll cannot be generated for a future month.')
                );

                return $this->redirect(['action' => 'generate']);
            }
        }

//    Validate Payment Date
        if (!empty($paymentDate) &&!empty($payrollMonth) &&!empty($payrollYear)) {
            if (is_array($paymentDate)) {
                $paymentDate = sprintf(
                    '%04d-%02d-%02d',
                    $paymentDate['year'],
                    $paymentDate['month'],
                    $paymentDate['day']
                );
            }
            $paymentDateObj = new \DateTime($paymentDate);
            $payrollMonthObj = new \DateTime($payrollYear . '-' .sprintf('%02d', $payrollMonth) .'-01');
            if ($paymentDateObj < $payrollMonthObj) {
                $this->Flash->error(
                    __('Payment date cannot be before the payroll month.')
                );

                return $this->redirect(['action' => 'generate']);
            }
        }

        // Load Payroll Preview
        if (!empty($payrollMonth) &&!empty($payrollYear)) {
            $result = $this->Payslips->Employees
            ->getPayrollPreview($payrollMonth, $payrollYear);

            $employees = $result['employees'];
            $workingDays = $result['workingDays'];

            if (empty($employees)) {
                $this->Flash->info(
                    __('All eligible employees already have payslips for the selected payroll period.')
                );
            }

            if (empty($employees)) {
                $this->Flash->info(
                    __('All active employees already have payslips for the selected payroll period.')
                );
            }

            $canGeneratePayroll = true;
            $incompleteEmployees = [];
        }

        $this->set(compact(
            'payrollMonth',
            'payrollYear',
            'paymentDate',
            'employees',
            'workingDays',
            'canGeneratePayroll',
            'incompleteEmployees',
            'payrollExists',
            'existingPayroll'
        ));
    }
    public function savePayroll()
    {
        if (!$this->request->is('POST')) {
            return $this->redirect(['action' => 'generate']);
        }

        $payrollMonth = $this->request->getData('payroll_month');
        $payrollYear  = $this->request->getData('payroll_year');
        $paymentDate  = $this->request->getData('payment_date');

        $result = $this->Payslips->Employees
        ->getPayrollPreview($payrollMonth, $payrollYear);

        $employees = $result['employees'];


        $exists = $this->Payslips->find()
        ->where([
              'payroll_month' => $payrollMonth,
                'payroll_year'  => $payrollYear
                ])
        ->count();

        if ($exists) {
            $this->Flash->error(__('Payroll already generated.'));

            return $this->redirect(['action' => 'generate']);
        }

        $payslips = [];

        foreach ($employees as $employee) {
            $payslips[] = $this->Payslips->newEntity([
        'employee_id' => $employee->id,
        'payroll_month' => $payrollMonth,
        'payroll_year' => $payrollYear,
        'payment_date' => $paymentDate,
        'base_salary' => $employee->base_salary,
        'working_days' => $employee->working_days,
        'present_days' => $employee->present_days,
        'leave_days' => $employee->leave_days,
        'absent_days' => $employee->absent_days,
        'salary_earned' => $employee->salary_earned,
        'bonus_total' => $employee->bonus,
        'deduction_total' => $employee->total_deduction,
        'net_salary' => $employee->net_salary
    ]);
        }

        $this->Payslips->saveMany($payslips);

        $this->Flash->success(__('Payroll generated successfully.'));
        return $this->redirect(['action' => 'index']);
    }
}



    // private function calculateEmployeePayroll(
    //     $employee,
    //     $workingDays,
    //     $presentDays,
    //     $leaveDays,
    //     $absentDays
    // ) {

    // // Database stores Annual Salary
    //     $baseSalary = $employee->base_salary;
    //     $month_salary= $baseSalary /12;
    //     $paidDays = $presentDays-$absentDays-(($leaveDays<2) ? 0 : $leaveDays-=2);
    //     //  + $leaveDays;
    //     $salaryEarned = 0;

    //     if ($workingDays > 0) {
    //         $salaryEarned =($month_salary / $workingDays) * $paidDays;
    //     }

    //     return [
    //     'base_salary'      => round($month_salary, 2),
    //     'working_days'     => $workingDays,
    //     'present_days'     => $presentDays,
    //     'leave_days'       => $leaveDays,
    //     'absent_days'      => $absentDays,
    //     'salary_earned'    => round($salaryEarned, 2),
    //     'bonus_total'      => 0,
    //     'deduction_total'  => 0,
    //     'net_salary'       => round($salaryEarned, 2)
    //     ];
    // }
