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
{    //pf and tds
    private const PF_PERCENTAGE = 8;
    private const TDS_PERCENTAGE = 5;
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Bonuses');
        $this->loadModel('Attendances');
    }
    public function index()
    {
        $this->paginate = ['contain' => ['Employees']];
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
            //nesteed assocaition
        'contain' => ['Employees' => ['Departments','Designations'] ]
        ]);
        $this->set(compact('payslip'));
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
            $payslip = $this->Payslips->patchEntity($payslip, $this->request->getData(), ['associated'=>['Bonuses','Deductions']]);
            $employeePayroll = $this->calculateEmployeePayroll(
                $payslip->employee_id,
                $payslip->payroll_month,
                $payslip->payroll_year
            );

            $payslip->base_salary = $employeePayroll->monthly_salary;
            $payslip->working_days = $employeePayroll->working_days;
            $payslip->present_days = $employeePayroll->paid_days;
            $payslip->salary_earned = $employeePayroll->salary_earned;
            $payslip->pf_amount = $employeePayroll->pf_amount;
            $payslip->tds_amount = $employeePayroll->tds_amount;
            $payslip->unpaid_leave_deduction =$employeePayroll->unpaid_leave_deduction;

            $bonusTotal = $this->Payslips->Bonuses->getBonusTotal($this->request->getData('bonuses') ?? []);
            $manualDeduction = $this->Payslips->Deductions->getDeductionTotal($this->request->getData('deductions') ?? []);
            $payslip->bonus_total = $employeePayroll->bonus_total + $bonusTotal;
            $payslip->deduction_total =$employeePayroll->pf_amount +$employeePayroll->tds_amount +$employeePayroll->unpaid_leave_deduction +$employeePayroll->manual_deduction +$manualDeduction;
            $payslip->net_salary =$employeePayroll->salary_earned +$payslip->bonus_total -$payslip->deduction_total;

            if (!empty($payslip->bonuses)) {
                foreach ($payslip->bonuses as $bonus) {
                    $bonus->employee_id = $payslip->employee_id;
                    $bonus->payroll_month = $payslip->payroll_month;
                    $bonus->payroll_year = $payslip->payroll_year;
                }
            }
            if (!empty($payslip->deductions)) {
                foreach ($payslip->deductions as $deduction) {
                    $deduction->employee_id = $payslip->employee_id;
                    $deduction->payroll_month = $payslip->payroll_month;
                    $deduction->payroll_year = $payslip->payroll_year;
                }
            }
            // check payslip generated or not
            $existingPayslip = $this->Payslips->find()->where([
                  'employee_id'   => $payslip->employee_id,
                  'payroll_month' => $payslip->payroll_month,
                  'payroll_year'  => $payslip->payroll_year
                   ])->first();

            if ($existingPayslip) {
                $this->Flash->error(__('Payslip has already been generated for this employee for the selected payroll period.'));
                return $this->redirect(['action' => 'add']);
            }

            if ($this->Payslips->save($payslip)) {
                $this->Flash->success(__('Payslip has been saved successfully.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Unable to save payslip.'));
            }
        }


        $employees = $this->Payslips->Employees->find()
         ->select(['id','employee_code','name'])
         ->where(['status' => 'active'])
         ->order(['employee_code' => 'ASC'])
         ->all()
          ->combine('id', function ($employee) {
              return $employee->employee_code . ' - ' . $employee->name;
          })
         ->toArray();

        $bonusOptions = $this->Payslips->Bonuses->getBonusTypeOptions();
        $deductionOptions = $this->Payslips->Deductions->getDeductionTypeOptions();

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

    public function generate()
    {
        // Read Filters
        $payrollMonth = $this->request->getQuery('payroll_month');
        $payrollYear  = $this->request->getQuery('payroll_year');
        $paymentDate  = $this->request->getQuery('payment_date');

        // Default Values
        $employees = [];
        $workingDays = 0;
        $canGeneratePayroll = false;
        $incompleteEmployees = [];
        $payrollExists = false;
        $existingPayroll = null;

        $currentMonth = date('n');
        $currentYear  = date('Y');

        // Validate Future Payroll Month

        if (!empty($payrollMonth) && !empty($payrollYear)) {
            if ($payrollYear > $currentYear ||
            ($payrollYear == $currentYear &&$payrollMonth > $currentMonth)) {
                $this->Flash->error(
                    __('Payroll cannot be generated for a future month.')
                );

                return $this->redirect($this->referer());
            }
        }

        // Validate Payment Date
        if (!empty($paymentDate) && !empty($payrollMonth) && !empty($payrollYear)) {
            if (is_array($paymentDate)) {
                $paymentDate = sprintf(
                    '%04d-%02d-%02d',
                    $paymentDate['year'],
                    $paymentDate['month'],
                    $paymentDate['day']
                );
            }
            $paymentDateObj = new \DateTime($paymentDate);

            $payrollMonthObj = new \DateTime(
                $payrollYear . '-' .
            sprintf('%02d', $payrollMonth) .
            '-01'
            );

            if ($paymentDateObj < $payrollMonthObj) {
                $this->Flash->error(
                    __('Payment date cannot be before the payroll month.')
                );
            }
        }

        // Check Existing Payroll

        if (!empty($payrollMonth) && !empty($payrollYear)) {
            $existingPayroll = $this->Payslips
            ->find()
            ->where([
                'payroll_month' => $payrollMonth,
                'payroll_year'  => $payrollYear
            ])
            ->first();
            $payrollExists = ($existingPayroll != null);
        }

        // Calculate Payroll
        if (!empty($payrollMonth) && !empty($payrollYear)) {
            $result = $this->calculatePayroll($payrollMonth, $payrollYear);

            $employees = $result['employees'];
            $workingDays = $result['workingDays'];
            $canGeneratePayroll = $result['canGeneratePayroll'];
            $incompleteEmployees = $result['incompleteEmployees'];
        }

        // Send Data to View

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

    private function calculatePayroll($payrollMonth, $payrollYear)
    {
        // Last Date of Selected Month
        $lastDate = date(
            'Y-m-t',
            strtotime($payrollYear . '-' . $payrollMonth . '-01')
        );

        // Calculate Working Days
        $workingDays = $this->Attendances->getWorkingDays($payrollMonth, $payrollYear);

        // Load Employees
        $employees = $this->Payslips->Employees->getPayrollEmployees($lastDate, $payrollMonth, $payrollYear);
        $attendanceTable = $this->Payslips->Employees->Attendances;

        $canGeneratePayroll = true;
        $incompleteEmployees = [];

        // Payroll Calculations
        foreach ($employees as $employee) {
            $monthlyPaidLeaves = 2;

            // 1. Monthly Salary
            $employee->monthly_salary = round(
                $employee->base_salary / 12,
                2
            );

            // 2. Attendance Summary
            $summary = $attendanceTable->getAttendanceSummary(
                $employee->id,
                $payrollMonth,
                $payrollYear
            );

            $employee->present_days = $summary['present'];
            $employee->leave_days = $summary['leave'];
            $employee->absent_days = $summary['absent'];

            // 3. Attendance Validation
            $employee->attendance_records =
            $employee->present_days +$employee->leave_days +$employee->absent_days;

            $employee->attendance_complete =($employee->attendance_records == $workingDays);

            if (!$employee->attendance_complete) {
                $canGeneratePayroll = false;
                $missingDates = $attendanceTable->getMissingAttendanceDates($employee->id, $payrollMonth, $payrollYear);

                $incompleteEmployees[] = [
                'employee_code' => $employee->employee_code,
                'employee_name' => $employee->name,
                'recorded' => $employee->attendance_records,
                'expected' => $workingDays,
                'missing_dates' => $missingDates
                ];
            }
            // 4. Paid Leave Policy
            $employee->paid_leave = min($employee->leave_days, $monthlyPaidLeaves);
            $employee->unpaid_leave = max(0, $employee->leave_days - $monthlyPaidLeaves);

            $employee->paid_days =$employee->present_days +$employee->paid_leave;
            $employee->unpaid_days = $employee->absent_days +$employee->unpaid_leave;

            // 5. Salary Calculations
            $employee->daily_salary = round($employee->monthly_salary / $workingDays, 2);

            // Gross Salary
            $employee->salary_earned =$employee->daily_salary * $employee->paid_days;

            // Unpaid Leave Deduction
            $employee->unpaid_leave_deduction = round($employee->daily_salary *$employee->unpaid_days, 2);

            // 6. Bonus
            $employee->bonus =$this->Payslips
            ->Employees
            ->Bonuses
            ->getMonthlyBonus($employee->id, $payrollMonth, $payrollYear);

            // 7. Fixed Deductions
            $employee->pf = round($employee->salary_earned * self::PF_PERCENTAGE / 100, 2);
            $employee->tds = round($employee->salary_earned * self::TDS_PERCENTAGE / 100, 2);

            // 8. Manual Deductions
            $deduction = $this->Payslips
            ->Employees
             ->Deductions
            ->find()
            ->where([
               'employee_id' => $employee->id,
               'payroll_month' => $payrollMonth,
               'payroll_year' => $payrollYear
            ])
            ->select([
               'total_deduction' => $this->Payslips
                  ->Employees
                  ->Deductions
                 ->find()
                 ->func()
                 ->sum('amount')
            ])
            ->first();

            $employee->manual_deduction =($deduction && $deduction->total_deduction) ? $deduction->total_deduction : 0;

            // 9. Total Deduction
            $employee->total_deduction = round(
                $employee->pf +$employee->tds +
                 $employee->manual_deduction +
                 $employee->unpaid_leave_deduction,
                2
            );

            // 10. Net Salary
            $employee->net_salary = round($employee->salary_earned +$employee->bonus-$employee->total_deduction, 2);
            if ($employee->net_salary < 0) {
                $employee->net_salary = 0;
                $employee->validation_error ='Net salary cannot be negative.';
            }
        }
        return ['employees' => $employees,'workingDays' => $workingDays,'canGeneratePayroll' => $canGeneratePayroll,'incompleteEmployees' => $incompleteEmployees];
    }


    private function calculateEmployeePayroll($employeeId, $payrollMonth, $payrollYear)
    {
        // Working Days
        $workingDays = $this->Attendances->getWorkingDays(
            $payrollMonth,
            $payrollYear
        );

        $monthlyPaidLeaves = 2;

        // Employee Details
        $employee = $this->Payslips
        ->Employees
        ->find()
        ->contain(['Departments', 'Designations'])
        ->where(['Employees.id' => $employeeId])
        ->first();

        // Attendance Summary
        $summary = $this->Attendances->getPayrollAttendanceSummary(
            $employeeId,
            $payrollMonth,
            $payrollYear
        );

        $employee->present_days = $summary['present_days']??0;
        $employee->leave_days = $summary['leave_days']??0;
        $employee->absent_days = $summary['absent_days']??0;

        // Monthly Salary
        $employee->monthly_salary = round(
            $employee->base_salary / 12,
            2
        );

        // Paid Leave Policy
        $employee->paid_leave = min(
            $employee->leave_days,
            $monthlyPaidLeaves
        );

        $employee->unpaid_leave = max(
            0,
            $employee->leave_days - $monthlyPaidLeaves
        );

        $employee->paid_days =
        $employee->present_days +
        $employee->paid_leave;

        $employee->unpaid_days =
        $employee->absent_days +
        $employee->unpaid_leave;

        // Salary Earned
        $employee->daily_salary = round(
            $employee->monthly_salary / $workingDays,
            2
        );

        $employee->salary_earned = round(
            $employee->daily_salary *
        $employee->paid_days,
            2
        );

        // Unpaid Leave Deduction
        $employee->unpaid_leave_deduction = round(
            $employee->daily_salary *
        $employee->unpaid_days,
            2
        );

        // Existing Bonus Total
        $employee->bonus_total = $this->Payslips
        ->Employees
        ->Bonuses
        ->getMonthlyBonus(
            $employeeId,
            $payrollMonth,
            $payrollYear
        );

        // Existing Manual Deduction
        $deduction = $this->Payslips
        ->Employees
        ->Deductions
        ->find()
        ->where([
            'employee_id' => $employeeId,
            'payroll_month' => $payrollMonth,
            'payroll_year' => $payrollYear
        ])
        ->select([
            'total_deduction' => $this->Payslips
                ->Employees
                ->Deductions
                ->find()
                ->func()
                ->sum('amount')
        ])
        ->first();

        $employee->manual_deduction =
        ($deduction && $deduction->total_deduction)
        ? $deduction->total_deduction
        : 0;

        // PF & TDS
        $employee->pf_amount = round(
            $employee->salary_earned *
        self::PF_PERCENTAGE / 100,
            2
        );

        $employee->tds_amount = round(
            $employee->salary_earned *
        self::TDS_PERCENTAGE / 100,
            2
        );

        // Total Deduction
        $employee->deduction_total = round(
            $employee->pf_amount +
        $employee->tds_amount +
        $employee->manual_deduction +
        $employee->unpaid_leave_deduction,
            2
        );

        // Net Salary
        $employee->net_salary = round(
            $employee->salary_earned +
        $employee->bonus_total -
        $employee->deduction_total,
            2
        );

        if ($employee->net_salary < 0) {
            $employee->net_salary = 0;
        }
        $employee->working_days = $workingDays;
        return $employee;
    }





    public function savePayroll()
    {
        $payrollMonth = $this->request->getData('payroll_month');
        $payrollYear  = $this->request->getData('payroll_year');
        $paymentDate  = $this->request->getData('payment_date');

        $alreadyGenerated = $this->Payslips->payrollExists($payrollMonth, $payrollYear);
        if ($alreadyGenerated > 0) {
            $this->Flash->error(__('Payroll already generated.'));
            return $this->redirect(['action' => 'generate']);
        }

        $result = $this->calculatePayroll($payrollMonth, $payrollYear);
        $employees = $result['employees'];
        $canGeneratePayroll = $result['canGeneratePayroll'];
        $workingDays = $result['workingDays'];

        //attendance valdlidation
        if (!$canGeneratePayroll) {
            $this->Flash->error(__('Payroll cannot be generated because attendance is incomplete.'));
            return $this->redirect([
                'action' => 'generate','?' => [
                'payroll_month' => $payrollMonth,
                'payroll_year' => $payrollYear,
                'payment_date' => $paymentDate]
            ]);
        }
        $connection = $this->Payslips->getConnection();
        $connection->begin();

        try {
            foreach ($employees as $employee) {
                $payslip = $this->Payslips->createPayslipEntity(
                    $employee,
                    $payrollMonth,
                    $payrollYear,
                    $workingDays,
                    $paymentDate
                );

                if (!$this->Payslips->save($payslip)) {
                    throw new \Exception(
                        'Unable to save payroll for Employee Code: ' .
                     $employee->employee_code
                    );
                }
            }

            $connection->commit();
            $this->Flash->success(__('Payroll generated successfully.'));
            return $this->redirect(['action' => 'index']);
        } catch (\Exception $e) {
            $connection->rollback();
            $this->Flash->error($e->getMessage());
            return $this->redirect(['action' => 'generate','?' => ['payroll_month' => $payrollMonth,'payroll_year'  => $payrollYear,'payment_date'  => $paymentDate]]);
        }
    }


    public function getEmployeePayrollDetails()
    {
        $this->request->allowMethod(['post']);
        $this->autoRender = false;

        $employeeId = $this->request->getData('employee_id');
        $month      = $this->request->getData('payroll_month');
        $year       = $this->request->getData('payroll_year');
        $bonuses = $this->request->getData('bonuses', []);
        $deductions = $this->request->getData('deductions', []);

        $employee = $this->calculateEmployeePayroll($employeeId, $month, $year);

        $bonusTotal = $this->Payslips->Bonuses ->getBonusTotal($bonuses);
        $manualDeduction = $this->Payslips->Deductions->getDeductionTotal($deductions);
        $totalDeduction =$employee->pf_amount +$employee->tds_amount +$employee->unpaid_leave_deduction +$manualDeduction;
        $netSalary =$employee->salary_earned +$bonusTotal -$totalDeduction;

        return $this->response
        ->withType('application/json')
        ->withStringBody(json_encode([
            'success' => true,

        'employee_code' => $employee->employee_code,
        'name' => $employee->name,
        'department' => $employee->department->name,
        'designation' => $employee->designation->name,
        'joining_date' => $employee->joining_date->format('d-M-Y'),
        'base_salary' => $employee->monthly_salary,
        'working_days' => $employee->working_days,
        'salary_earned' => $employee->salary_earned,
        'pf_amount' => $employee->pf_amount,
        'tds_amount' => $employee->tds_amount,
        'bonus_total' => $bonusTotal,
        'manual_deduction' => $manualDeduction,
        'total_deduction' => $totalDeduction,
        'net_salary' => $netSalary,
        'present_days' => $attendance['present_days'] ?? 0,
        'leave_days' => $attendance['leave_days'] ?? 0,
        'absent_days' => $attendance['absent_days'] ?? 0
        ]));
    }
}
