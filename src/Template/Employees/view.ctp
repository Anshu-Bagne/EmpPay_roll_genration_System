<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Employee $employee
 */
?>
<!-- <nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Employee'), ['action' => 'edit', $employee->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Employee'), ['action' => 'delete', $employee->id], ['confirm' => __('Are you sure you want to delete # {0}?', $employee->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Employees'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Departments'), ['controller' => 'Departments', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Department'), ['controller' => 'Departments', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Designations'), ['controller' => 'Designations', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Designation'), ['controller' => 'Designations', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Attendances'), ['controller' => 'Attendances', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Attendance'), ['controller' => 'Attendances', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Payslips'), ['controller' => 'Payslips', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Payslip'), ['controller' => 'Payslips', 'action' => 'add']) ?> </li>
    </ul>
</nav> -->
<div class="employees view large-9 medium-8 columns content">
    <h3><?= h($employee->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Employee Code') ?></th>
            <td><?= h($employee->employee_code) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($employee->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Department') ?></th>
         <td><?= $employee->has('department') ? h($employee->department->name): '' ?></td>        
        </tr>
        <tr>
            <th scope="row"><?= __('Designation') ?></th>
           <td><?= $employee->has('designation') ? h($employee->designation->name) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Email') ?></th>
            <td><?= h($employee->email) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Mobile') ?></th>
            <td><?= h($employee->mobile) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status') ?></th>
            <td><?= h($employee->status) ?></td>
        </tr>
        <!-- <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($employee->id) ?></td>
        </tr> -->
        <tr>
            <th scope="row"><?= __('Base Salary') ?></th>
            <td><?= '₹ ' . number_format($employee->base_salary, 2) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Joining Date') ?></th>
            <td><?= $employee->joining_date->format('d-M-Y') ?></td>
        </tr>
        <!-- <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($employee->created) ?></td>
        </tr> -->
        <!-- <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($employee->modified) ?></td>
        </tr> -->
    </table>
    <!-- <div class="related">
         <h4><?= __('Related Attendances') ?></h4>
        <?php if (!empty($employee->attendances)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Employee Id') ?></th>
                <th scope="col"><?= __('Attendance Date') ?></th>
                <th scope="col"><?= __('Status') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($employee->attendances as $attendances): ?>
            <tr>
                <td><?= h($attendances->id) ?></td>
                <td><?= h($attendances->employee_id) ?></td>
                <td><?= h($attendances->attendance_date) ?></td>
                <td><?= h($attendances->status) ?></td>
                <td><?= h($attendances->created) ?></td>
                <td><?= h($attendances->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Attendances', 'action' => 'view', $attendances->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Attendances', 'action' => 'edit', $attendances->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Attendances', 'action' => 'delete', $attendances->id], ['confirm' => __('Are you sure you want to delete # {0}?', $attendances->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?> 
        </table>
        <?php endif; ?>
     </div> -->
        <div class="related">
          <!-- <h4><?= __('Related Payslips') ?></h4> 
          <?php if (!empty($employee->payslips)): ?>
          <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Employee Id') ?></th>
                <th scope="col"><?= __('Payroll Month') ?></th>
                <th scope="col"><?= __('Payroll Year') ?></th>
                <th scope="col"><?= __('Working Days') ?></th>
                <th scope="col"><?= __('Present Days') ?></th>
                <th scope="col"><?= __('Base Salary') ?></th>
                <th scope="col"><?= __('Bonus Total') ?></th>
                <th scope="col"><?= __('Deduction Total') ?></th>
                <th scope="col"><?= __('Net Salary') ?></th>
                <th scope="col"><?= __('Payment Date') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($employee->payslips as $payslips): ?> 
            <tr>
                <td><?= h($payslips->id) ?></td>
                <td><?= h($payslips->employee_id) ?></td>
                <td><?= h($payslips->payroll_month) ?></td>
                <td><?= h($payslips->payroll_year) ?></td>
                <td><?= h($payslips->working_days) ?></td>
                <td><?= h($payslips->present_days) ?></td>
                <td><?= h($payslips->base_salary) ?></td>
                <td><?= h($payslips->bonus_total) ?></td>
                <td><?= h($payslips->deduction_total) ?></td>
                <td><?= h($payslips->net_salary) ?></td>
                <td><?= h($payslips->payment_date) ?></td>
                <td><?= h($payslips->created) ?></td>
                <td><?= h($payslips->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Payslips', 'action' => 'view', $payslips->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Payslips', 'action' => 'edit', $payslips->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Payslips', 'action' => 'delete', $payslips->id], ['confirm' => __('Are you sure you want to delete # {0}?', $payslips->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
         </table> -->
         <!-- <?php endif; ?> -->
        </div>
</div>

<?= $this->Html->link(
    'Edit Employee',
    ['action' => 'edit', $employee->id],
    ['class' => 'button']
) ?>

<?= $this->Html->link(
    'Back to List',
    ['action' => 'index'],
    ['class' => 'button']
) ?>
