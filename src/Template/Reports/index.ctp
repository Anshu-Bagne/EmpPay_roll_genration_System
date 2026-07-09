<?php
/**
 * Reports Dashboard
 */
?>


<div class="reports index large-11 medium-10 columns content">
    <!-- <div class="content-area"> -->
    <h3><?= __('Reports Dashboard') ?></h3>
    
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