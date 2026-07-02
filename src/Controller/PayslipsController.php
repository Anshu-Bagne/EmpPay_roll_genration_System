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
        'contain' => [
            'Employees' => [
                'Departments',
                'Designations'
                ]
            ]
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
            $result = $this->calculatePayroll(
                $payrollMonth,
                $payrollYear
            );

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

        $daysInMonth = cal_days_in_month(
            CAL_GREGORIAN,
            $payrollMonth,
            $payrollYear
        );

        $sundays = 0;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            if (
                    date(
                        'w',
                        strtotime($payrollYear . '-' . $payrollMonth . '-' . $day)
                    ) == 0
                ) {
                $sundays++;
            }
        }
        $workingDays = $daysInMonth - $sundays;
        // Load Employees

        $employees = $this->Payslips
                ->Employees
                ->find()
                ->contain([
                    'Departments',
                    'Designations'
                ])
                ->where([
                    'Employees.status' => 'active',
                    'Employees.joining_date <=' => $lastDate
                ])
                ->toArray();

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


            $employee->present_days = $attendanceTable
        ->find()
        ->where([
            'employee_id' => $employee->id,
            'status' => 'present',
            'MONTH(attendance_date)' => $payrollMonth,
            'YEAR(attendance_date)' => $payrollYear
        ])
        ->count();

            $employee->leave_days = $attendanceTable
        ->find()
        ->where([
            'employee_id' => $employee->id,
            'status' => 'leave',
            'MONTH(attendance_date)' => $payrollMonth,
            'YEAR(attendance_date)' => $payrollYear
        ])
        ->count();

            $employee->absent_days = $attendanceTable
        ->find()
        ->where([
            'employee_id' => $employee->id,
            'status' => 'absent',
            'MONTH(attendance_date)' => $payrollMonth,
            'YEAR(attendance_date)' => $payrollYear
        ])
        ->count();

            // 3. Attendance Validation
            $employee->attendance_records =
        $employee->present_days +
        $employee->leave_days +
        $employee->absent_days;

            $employee->attendance_complete =
        ($employee->attendance_records == $workingDays);

            if (!$employee->attendance_complete) {
                $canGeneratePayroll = false;

                $missingDates = [];

                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $currentDate = sprintf(
                        '%04d-%02d-%02d',
                        $payrollYear,
                        $payrollMonth,
                        $day
                    );

                    // Skip Sundays
                    if (date('w', strtotime($currentDate)) == 0) {
                        continue;
                    }
                    $exists = $attendanceTable
                    ->find()
                    ->where([
                        'employee_id' => $employee->id,
                        'attendance_date' => $currentDate
                    ])
                    ->count();

                    if ($exists == 0) {
                        $missingDates[] = $currentDate;
                    }
                }

                $incompleteEmployees[] = [
            'employee_code' => $employee->employee_code,
            'employee_name' => $employee->name,
            'recorded' => $employee->attendance_records,
            'expected' => $workingDays,
            'missing_dates' => $missingDates
        ];
            }


            // 4. Paid Leave Policy


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

            // 5. Salary Calculations
            $employee->daily_salary = round(
                $employee->monthly_salary / $workingDays,
                2
            );

            // Gross Salary
            $employee->salary_earned =$employee->daily_salary * $employee->paid_days;

            // Unpaid Leave Deduction
            $employee->unpaid_leave_deduction = round(
                $employee->daily_salary *
        $employee->unpaid_days,
                2
            );


            // 6. Bonus

            $bonus = $this->Payslips
        ->Employees
        ->Bonuses
        ->find()
        ->where([
            'employee_id' => $employee->id,
            'payroll_month' => $payrollMonth,
            'payroll_year' => $payrollYear
        ])
        ->select([
            'total_bonus' => $this->Payslips
                ->Employees
                ->Bonuses
                ->find()
                ->func()
                ->sum('amount')
        ])
        ->first();

            $employee->bonus =
        ($bonus && $bonus->total_bonus)
            ? $bonus->total_bonus
            : 0;


            // 7. Fixed Deductions


            $employee->pf = $employee->pf_amount;
            $employee->tds = $employee->tds_amount;



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

            $employee->manual_deduction =
        ($deduction && $deduction->total_deduction)
            ? $deduction->total_deduction
            : 0;


            // 9. Total Deduction


            $employee->total_deduction = round(
                $employee->pf +

        $employee->tds +

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
        return [
    'employees' => $employees,
    'workingDays' => $workingDays,
    'canGeneratePayroll' => $canGeneratePayroll,
    'incompleteEmployees' => $incompleteEmployees
    ];
    }


    public function savePayroll()
    {
        $payrollMonth = $this->request->getData('payroll_month');
        $payrollYear  = $this->request->getData('payroll_year');
        $paymentDate  = $this->request->getData('payment_date');

        $alreadyGenerated = $this->Payslips
        ->find()
        ->where([
        'payroll_month' => $payrollMonth,
        'payroll_year' => $payrollYear
        ])
        ->count();
        if ($alreadyGenerated > 0) {
            $this->Flash->error(__('Payroll already generated.'));
            return $this->redirect(['action' => 'generate']);
        }

        $result = $this->calculatePayroll(
            $payrollMonth,
            $payrollYear
        );
        $employees = $result['employees'];
        $canGeneratePayroll = $result['canGeneratePayroll'];
        $workingDays = $result['workingDays'];

        // if (!$canGeneratePayroll) {
        //     $this->Flash->error(__('Payroll cannot be generated because attendance is incomplete.'));
        //     return $this->redirect([
        //         'action' => 'generate','?' => [
        //     'payroll_month' => $payrollMonth,
        //     'payroll_year' => $payrollYear,
        //     'payment_date' => $paymentDate]
        //     ]);
        // }
        $connection = $this->Payslips->getConnection();
        $connection->begin();

        try {
            foreach ($employees as $employee) {
                $payslip = $this->Payslips->newEntity();

                $payslip = $this->Payslips->patchEntity($payslip, [

            'employee_id'            => $employee->id,
            'payroll_month'          => $payrollMonth,
            'payroll_year'           => $payrollYear,
            'working_days'           => $workingDays,
            'present_days'           => $employee->present_days,
            'leave_days'             => $employee->leave_days,
            'base_salary'            => $employee->monthly_salary,
            'salary_earned'          => $employee->salary_earned,
            'bonus_total'            => $employee->bonus,
            'pf_amount'              => $employee->pf,
            'tds_amount'             => $employee->tds,
            'deduction_total'        => $employee->total_deduction,
            'unpaid_leave_deduction' => $employee->unpaid_leave_deduction,
            'net_salary'             => $employee->net_salary,
            'payment_date'           => $paymentDate
             ]);

                if (!$this->Payslips->save($payslip)) {
                    throw new \Exception(
                        'Unable to save payroll for Employee Code: ' .
                     $employee->employee_code
                    );
                }
            }

            $connection->commit();

            $this->Flash->success(
                __('Payroll generated successfully.')
            );

            return $this->redirect(['action' => 'index']);
        } catch (\Exception $e) {
            $connection->rollback();

            $this->Flash->error(
                $e->getMessage()
            );

            return $this->redirect([
        'action' => 'generate',
        '?' => [
            'payroll_month' => $payrollMonth,
            'payroll_year'  => $payrollYear,
            'payment_date'  => $paymentDate
        ]
        ]);
        }
    }
}
