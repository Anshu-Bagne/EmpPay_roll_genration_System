<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Employee $employee
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Employees'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Departments'), ['controller' => 'Departments', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Department'), ['controller' => 'Departments', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Designations'), ['controller' => 'Designations', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Designation'), ['controller' => 'Designations', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Attendances'), ['controller' => 'Attendances', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Attendance'), ['controller' => 'Attendances', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Payslips'), ['controller' => 'Payslips', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Payslip'), ['controller' => 'Payslips', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="employees form large-9 medium-8 columns content">
    <?= $this->Form->create($employee) ?>
    <fieldset>
        <!-- <legend><?= __('Add Employee') ?></legend>
        
        <div class="input text">
            <label>Employee Code</label>
            <input type="text" value="Will be generated automatically" readonly>
        </div> -->
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('department_id', ['options' => $departments]);
            echo $this->Form->control('designation_id', ['options' => $designations]);
            echo $this->Form->control('base_salary');
            echo $this->Form->control('pf_amount');
            echo $this->Form->control('tds_amount');
           echo $this->Form->control('joining_date', ['max' => date('Y-m-d')]);            echo $this->Form->control('email');
            echo $this->Form->control('mobile');
            echo $this->Form->control('status', ['type' => 'radio','options' => 
            ['active' => 'Active','inactive' => 'Inactive'],'hiddenField'=>false,'label' => 'Employee Status']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
