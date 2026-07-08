<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Employee $employee
 */
?>
<nav class="large-2 medium-3 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Employees'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Attendances'), ['controller' => 'Attendances', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Attendance'), ['controller' => 'Attendances', 'action' => 'mark']) ?></li>
        <li><?= $this->Html->link(__('List Payslips'), ['controller' => 'Payslips', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Generate Payslip'), ['controller'=>'Payslips','action'=>'generate']) ?></li>
        <li><?= $this->Html->link(__('New Payslip'), ['controller' => 'Payslips', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="employees form large-10 medium-9 columns content">
    <?= $this->Form->create($employee) ?>
    <fieldset>

        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('department_id', ['options' => $departments]);
            echo $this->Form->control('designation_id', ['options' => $designations]);
            echo $this->Form->control('base_salary');
            echo $this->Form->control('joining_date', ['max' => date('Y-m-d')]);
            echo $this->Form->control('email');
            echo $this->Form->control('mobile');
            echo $this->Form->control('status', ['type' => 'radio','options' =>
            ['active' => 'Active','inactive' => 'Inactive'],'hiddenField'=>false,'label' => 'Employee Status']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
