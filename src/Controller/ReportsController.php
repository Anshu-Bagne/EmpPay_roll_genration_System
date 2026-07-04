<?php

namespace App\Controller;

class ReportsController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->loadModel('Payslips');
        $this->loadModel('Employees');
        $this->loadModel('Departments');
    }


    public function departmentSalary()
    {
        $month = $this->request->getQuery('month');
        $year  = $this->request->getQuery('year');

        $report = [];
        if (!empty($month) && !empty($year)) {
            $report = $this->Payslips->find();
            $report->select([
                'department_name' => 'Departments.name',
                'base_pay' => $report->func()->sum('Payslips.base_salary'),
                'bonus' => $report->func()->sum('Payslips.bonus_total'),
                'deduction' => $report->func()->sum('Payslips.deduction_total'),
                'net_salary' => $report->func()->sum('Payslips.net_salary')
                ])
                ->contain(['Employees.Departments'])
                ->where([
                    'Payslips.payroll_month' => $month,
                   'Payslips.payroll_year'  => $year
                ])
               ->group([
                    'Departments.id',
                    'Departments.name'
                 ])
                ->enableHydration(false)
                ->toArray();
        }

        $this->set(compact(
            'month',
            'year',
            'report'
        ));
    }

    public function index()
    {
    }


    public function employeeMonthly()
    {
        $month = $this->request->getQuery('month');
        $year  = $this->request->getQuery('year');

        $report = [];

        if (!empty($month) && !empty($year)) {
            $report = $this->Payslips
        ->find()
        ->contain([
            'Employees.Departments'
        ])
        ->where([
            'Payslips.payroll_month' => $month,
            'Payslips.payroll_year' => $year
        ])
        ->order([
            'Employees.employee_code' => 'ASC'
        ])
        ->toArray();
        }





        $this->set(compact(
            'month',
            'year',
            'report'
        ));
    }

    public function employeeYearly()
    {
        $year = $this->request->getQuery('year');

        $report = [];

        if (!empty($year)) {
            $query = $this->Payslips->find();
            $report = $query
            ->select([
                'employee_name' => 'Employees.name',
                'department_name' => 'Departments.name',
                'base_salary' => $query->func()->sum('Payslips.base_salary'),
                'bonus' => $query->func()->sum('Payslips.bonus_total'),
                'deduction' => $query->func()->sum('Payslips.deduction_total'),
                'net_salary' => $query->func()->sum('Payslips.net_salary')
            ])
            ->contain([
                'Employees.Departments'
            ])
            ->where([
                'Payslips.payroll_year' => $year
            ])
            ->group([
                'Employees.id',
                'Employees.name',
                'Departments.name'
                ])
                ->order([
                    'Employees.employee_code'=>'ASC'
                    ])
                    ->enableHydration(false)
                    ->toArray();
        }

        $this->set(compact(
            'employeeId',
            'year',
            'employees',
            'report'
        ));
    }
}
