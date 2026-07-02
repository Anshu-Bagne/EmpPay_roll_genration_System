<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Payslip $payslip
 */
?>
<h2 style="text-align:center;">Employee Payslip</h2>
<hr>
<h5>Employee Details</h5>
<hr>
<table  border="1" width="100%" cellpadding="8" cellspacing="0">
<tr>
    <td><strong>Employee Code</strong></td>
    <td><?= h($payslip->employee->employee_code) ?></td>
    <td><strong>Payroll Month</strong></td>
    <td>
        <?= date(
    'F Y',
    strtotime(
        $payslip->payroll_year .'-' .$payslip->payroll_month .'-01'
    )
) ?>
    </td>
</tr>
<tr>
    <td><strong>Employee Name</strong></td>
    <td><?= h($payslip->employee->name) ?></td>
    <td><strong>Payment Date</strong></td>
    <td><?= date('d-M-Y', strtotime($payslip->payment_date)) ?></td>
</tr>
<tr>
    <td><strong>Department</strong></td>
    <td><?= h($payslip->employee->department->name) ?></td>
    <td><strong>Designation</strong></td>
    <td><?= h($payslip->employee->designation->name) ?></td>
</tr>
</table>
<hr>
<h5>Attendance Summary</h5>

<table border="1" width="100%" cellpadding="8" cellspacing="0">

<tr>
    <th>Working Days</th>
    <th>Present Days</th>
    <th>Leave Days</th>
    <th>Absent Days</th>
</tr>

<tr>
    <td><?= $payslip->working_days ?></td>

    <td><?= $payslip->present_days ?></td>

    <td><?= $payslip->leave_days ?></td>

    <td>
        <?= $payslip->working_days
            - ($payslip->present_days + $payslip->leave_days) ?>
    </td>

</tr>

</table>

<br>
<h5>Salary Structure</h5>
<table border="1" width="100%" cellpadding="8" cellspacing="0">


<tr style="background:#f5f5f5;">
    <th>Earnings</th>
    <th>Amount (₹)</th>
    <th>Deductions</th>
    <th>Amount (₹)</th>
</tr>
<tr>
    <td>Base Salary</td>
    <td><?= number_format($payslip->base_salary, 2) ?></td>
    <td>PF</td>
    <td><?= number_format($payslip->pf_amount, 2) ?></td>
</tr>
<tr>

    <td>Bonus</td>
    <td><?= number_format($payslip->bonus_total, 2) ?></td>
    <td>TDS</td>
    <td><?= number_format($payslip->tds_amount, 2) ?></td>
</tr>
<tr>

    <td>Salary Earned</td>
    <td><?= number_format($payslip->salary_earned, 2) ?></td>
    <td>Leave Deduction</td>
    <td><?= number_format($payslip->unpaid_leave_deduction, 2) ?></td>
</tr>
<tr>
    <td></td>
    <td></td>

    <td>Total Deduction</td>
    <td><?= number_format($payslip->deduction_total, 2) ?></td>

</tr>

<tr style="background:#f5f5f5;">

    <th colspan="3" style="text-align:right;">

        Net Salary

    </th>

    <th>

        ₹ <?= number_format($payslip->net_salary, 2) ?>

    </th>

</tr>

</table>