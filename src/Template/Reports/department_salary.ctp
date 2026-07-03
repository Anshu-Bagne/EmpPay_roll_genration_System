<h2>Department Monthly Salary Report</h2>

<?= $this->Form->create(null, ['type' => 'get']) ?>

<?= $this->Form->control('month', [
    'label' => 'Month',
    'options' => [
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
    'value'=>$month
]) ?>

<?= $this->Form->control('year', [
    'type'=>'number',
    'value'=>$year ?? date('Y')
]) ?>

<?= $this->Form->button('Generate Report') ?>

<?= $this->Form->end() ?>

<?php if (!empty($report)): ?>

<table border="1" cellpadding="8">

<tr>
    <th>Department</th>
    <th>Month</th>
    <th>Base Pay</th>
    <th>Bonus</th>
    <th>Deductions</th>
    <th>Net Salary</th>

</tr>

<?php foreach ($report as $row): ?>
<tr>
    <td><?= h($row['department_name']) ?></td>
    <td><?= date('F', mktime(0, 0, 0, $month, 1)) . ' ' . $year ?></td>
    <td><?= number_format($row['base_pay'], 2) ?></td>
    <td><?= number_format($row['bonus'], 2) ?></td>
    <td><?= number_format($row['deduction'], 2) ?></td>
    <td><?= number_format($row['net_salary'], 2) ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>