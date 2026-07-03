<?php
/**
 * Reports Dashboard
 */
?>

<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Reports') ?></li>
        <li><?= $this->Html->link(__('Attendance Report'), ['controller' => 'Attendances', 'action' => 'report']) ?> </li>
        <li><?= $this->Html->link(__('Department Monthly Salary Report'), ['action' => 'departmentSalary']) ?></li>
        <li><?= $this->Html->link(__('Employee Monthly Salary Report'), ['action' => 'employeeMonthly']) ?></li>
        <li><?= $this->Html->link(__('Employee Yearly Salary Report'), ['action' => 'employeeYearly']) ?></li>
    </ul>
</nav>
<div class="reports index large-9 medium-8 columns content">
    <h3><?= __('Reports Dashboard') ?></h3>
    <p>
        Select a report from the left navigation panel.
    </p>
    <table cellpadding="8">
        <thead>
        <tr>
            <th>Report Name</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Attendance Report</td>
            <td>
                <?= $this->Html->link(
    'Open',
    ['controller'=>'Attendances','action'=>'report'],
    ['class'=>'button']
) ?>
            </td>
        </tr>
        <tr>
            <td>Department Monthly Salary Report</td>
            <td>
                <?= $this->Html->link(
                'Open',
                ['action'=>'departmentSalary'],
                ['class'=>'button']
            ) ?>
            </td>
        </tr>
        <tr>
            <td>Employee Monthly Salary Report</td>
            <td>
                <?= $this->Html->link(
                'Open',
                ['action'=>'employeeMonthly'],
                ['class'=>'button']
            ) ?>
            </td>
        </tr>
        <tr>
            <td>Employee Yearly Salary Report</td>
            <td>
                <?= $this->Html->link(
                'Open',
                ['action'=>'employeeYearly'],
                ['class'=>'button']
            ) ?>
            </td>

        </tr>

        </tbody>

    </table>

</div>