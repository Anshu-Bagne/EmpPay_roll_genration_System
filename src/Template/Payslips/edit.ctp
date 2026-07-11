<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Payslip $payslip
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $payslip->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $payslip->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Payslips'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Bonuses'), ['controller' => 'Bonuses', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Bonus'), ['controller' => 'Bonuses', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Deductions'), ['controller' => 'Deductions', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Deduction'), ['controller' => 'Deductions', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="payslips form large-9 medium-8 columns content">
    <?= $this->Form->create($payslip) ?>
    <fieldset>
        <legend><?= __('Edit Payslip') ?></legend>
        <?php
            echo $this->Form->control('employee_id', ['options' => $employees]);
            echo $this->Form->control('payroll_month');
            echo $this->Form->control('payroll_year');
            echo $this->Form->control('working_days');
            echo $this->Form->control('present_days');
            echo $this->Form->control('leave_days');
            echo $this->Form->control('absent_days');
            echo $this->Form->control('base_salary');
            echo $this->Form->control('salary_earned');
            echo $this->Form->control('bonus_total');
            echo $this->Form->control('deduction_total');
            echo $this->Form->control('net_salary');
            echo $this->Form->control('payment_date');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
