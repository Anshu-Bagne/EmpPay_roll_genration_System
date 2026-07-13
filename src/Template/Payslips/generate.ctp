
<h2>Generate Payroll</h2>

<?= $this->Form->create(null, ['type' => 'get']) ?>

<?= $this->Form->control('payroll_month', [

    'label'=>'Payroll Month',

    'options'=>[
        1=>'January',
        2=>'February',
        3=>'March',
        4=>'April',
        5=>'May',
        6=>'June',
        7=>'July',
        8=>'August',
        9=>'September',
        10=>'October',
        11=>'November',
        12=>'December'
    ],
    'empty'=>'Select Month',
    'value'=>$payrollMonth??''
]) ?>
<?= $this->Form->control('payroll_year', [

    'type'=>'number',

    'value'=>$payrollYear ?? date('Y')
]) ?>

<?= $this->Form->control('payment_date', ['type'=>'date','value' => $paymentDate ?? date('Y-m-d')]) ?>
<?= $this->Form->button(' Preview Payroll') ?>
<?= $this->Form->end() ?>
<?php if (!empty($employees)): ?>
    <h3>Payroll Preview</h3>

    <?= h($employee->employee_code) ?>
    <?= h($employee->name) ?>
    <?= h($employee->department->name) ?>
    <?= number_format($employee->base_salary, 2) ?>
    <?= $employee->working_days ?>
    <?= $employee->present_days ?>
    <?= $employee->leave_days ?>
    <?= $employee->absent_days ?>
    <?= number_format($employee->bonus, 2) ?>
    <?= number_format($employee->manual_deduction, 2) ?>
    <?= number_format($employee->unpaid_leave_deduction, 2) ?>
    <?= number_format($employee->total_deduction, 2) ?>
    <?= number_format($employee->net_salary, 2) ?>
    

<table border="1" cellpadding="8">
<tr>
<th>Employee Code</th>
<th>Name</th>
<th>Department</th>
<th>Monthly Salary</th>
<th>Working Days</th>
<th>Present</th>
<th>Leave</th>
<th>Absent</th>
<th>Bonus</th>
<th>PF</th>
<th>TDS</th>
<th>Manual Deduction</th>
<th>Unpaid Leave Deduction</th>
<th>Total Deduction</th>
<th>Net Salary</th>
    
</tr>

<?php foreach ($employees as $employee): ?>
<tr>
<td><?= h($employee->employee_code) ?></td>
<td><?= h($employee->name) ?></td>
<td><?= h($employee->department->name) ?></td>
<td><?= number_format($employee->monthly_salary, 2) ?></td>
<td><?= $workingDays ?></td>
<td><?= $employee->present_days ?></td>
<td><?= $employee->leave_days ?></td>
<td><?= $employee->absent_days ?></td>
<td><?= number_format($employee->bonus, 2) ?></td>
<td><?= number_format($employee->pf, 2) ?></td>
<td><?= number_format($employee->tds, 2) ?></td>
<td><?= number_format($employee->manual_deduction, 2) ?></td>
<td><?= number_format($employee->unpaid_leave_deduction, 2) ?></td>
<td><?= number_format($employee->total_deduction, 2) ?></td>
<td><?= number_format($employee->net_salary, 2) ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>


<?php if ($payrollExists): ?>
<div class="alert alert-info">
    <strong>Payroll Already Generated</strong><br><br>Payroll has already been generated for
    <strong>
        <?= date('F Y', strtotime(
    $existingPayroll->payroll_year . '-' .
                $existingPayroll->payroll_month . '-01'
))
    ?>
    </strong>
    <br>
    Payment Date :
    <strong>
        <?= date('d-M-Y', strtotime($existingPayroll->payment_date)) ?>
    </strong>
    <br><br>
    <button class="btn btn-secondary" disabled
        title="Payroll already generated. 
        Click View Payslips to view the generated payroll.">Generate Payroll</button>
    
    <?= $this->Html->link(
        'View Payslips',
        ['action' => 'index'],
        ['class' => 'btn btn-primary']
    ) ?>
    
</div>
<?php elseif (!empty($employees)): ?>
<?= $this->Form->create(null, ['url' => ['action' => 'savePayroll']]); ?>
<?= $this->Form->hidden('payroll_month', ['value' => $payrollMonth]); ?>
<?= $this->Form->hidden('payroll_year', ['value' => $payrollYear]); ?>
<?= $this->Form->hidden('payment_date', ['value' => $paymentDate]); ?>
<?= $this->Form->button('Generate Payroll', ['class' => 'btn btn-success']); ?>
<?= $this->Form->end(); ?>

<?php endif; ?>