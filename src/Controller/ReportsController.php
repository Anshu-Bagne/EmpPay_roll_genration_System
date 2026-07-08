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
            $report = $this->Payslips->getDepartmentSalaryReport($month, $year);
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
            $report = $this->Payslips->getEmployeeMonthlyReport($month, $year);
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
            $report = $this->Payslips->getEmployeeYearlyReport($year);
        }

        $this->set(compact(
            'year',
            'report'
        ));
    }
}
