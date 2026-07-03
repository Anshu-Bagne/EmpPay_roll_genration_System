<h2>Employee Yearly Salary Report</h2>

<?= $this->Form->create(null, ['type'=>'get']) ?>


<?= $this->Form->control('year', [
    'type'=>'number',
    'value'=>$year ?? date('Y')
]) ?>
<?= $this->Form->button('Generate Report') ?>
<?= $this->Form->end() ?>

<?php if (!empty($report)): ?>

<table class="salary-report-table">
<tr>
<th>Employee</th>
<th>Department</th>
<th>Year</th>
<th>Base Pay</th>
<th>Bonus</th>
<th>Deductions</th>
<th>Net Salary</th>
</tr>
<?php foreach ($report as $row): ?>

<tr>
<td><?= h($row['employee_name']) ?></td>
<td><?= h($row['department_name']) ?></td>
<td><?= h($year) ?></td>
<td><?= number_format($row['base_salary'], 2) ?></td>
<td><?= number_format($row['bonus'], 2) ?></td>
<td><?= number_format($row['deduction'], 2) ?></td>
<td><?= number_format($row['net_salary'], 2) ?></td>
</tr>

<?php endforeach; ?>

</table>
<br>

<button type="button" class="button" onclick="window.print();">
    Print Report
</button>
<?php endif; ?>
