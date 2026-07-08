<h2>Employee Monthly Salary Report</h2>

<?= $this->Form->create(null, ['type'=>'get']) ?>


<?= $this->Form->control('month', [
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
'value'=>$month

]) ?>

<?= $this->Form->control('year', ['type'=>'number','value'=>$year ?? date('Y')]) ?>
<?= $this->Form->button('Generate Report') ?>
<?= $this->Form->end() ?>

<?php if (!empty($report)): ?>

<table class="salary-report-table">
<tr>
<th>Employee</th>
<th>Department</th>
<th>Month</th>
<th>Base Pay</th>
<th>Bonus</th>
<th>Deductions</th>
<th>Net Salary</th>
</tr>

<?php foreach ($report as $row): ?>

<tr>
<td style="white-space: nowrap;"><?= h($row->employee->name) ?></td>
<td><?= h($row->employee->department->name) ?></td>
<td style="white-space: nowrap;"><?= date('F', mktime(0, 0, 0, $month, 1)) . ' ' . $year ?></td>
<td><?= number_format($row->base_salary, 2) ?></td>
<td><?= number_format($row->bonus_total, 2) ?></td>
<td><?= number_format($row->deduction_total, 2) ?></td>
<td><?= number_format($row->net_salary, 2) ?></td>
</tr>
<?php endforeach; ?>
</table>

<br>
<button type="button" class="button" onclick="window.print();">
    Print Report
</button>

<?php endif; ?>
