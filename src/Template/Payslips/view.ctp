<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Payslip $payslip
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Payslip'), ['action' => 'edit', $payslip->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Payslip'), ['action' => 'delete', $payslip->id], ['confirm' => __('Are you sure you want to delete # {0}?', $payslip->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Payslips'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Payslip'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Bonuses'), ['controller' => 'Bonuses', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Bonus'), ['controller' => 'Bonuses', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Deductions'), ['controller' => 'Deductions', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Deduction'), ['controller' => 'Deductions', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="payslips view large-9 medium-8 columns content">
    <h3><?= h($payslip->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Employee') ?></th>
            <td><?= $payslip->has('employee') ? $this->Html->link($payslip->employee->name, ['controller' => 'Employees', 'action' => 'view', $payslip->employee->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Payroll Year') ?></th>
            <td><?= h($payslip->payroll_year) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($payslip->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Payroll Month') ?></th>
            <td><?= $this->Number->format($payslip->payroll_month) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Working Days') ?></th>
            <td><?= $this->Number->format($payslip->working_days) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Present Days') ?></th>
            <td><?= $this->Number->format($payslip->present_days) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Leave Days') ?></th>
            <td><?= $this->Number->format($payslip->leave_days) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Absent Days') ?></th>
            <td><?= $this->Number->format($payslip->absent_days) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Base Salary') ?></th>
            <td><?= $this->Number->format($payslip->base_salary) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Salary Earned') ?></th>
            <td><?= $this->Number->format($payslip->salary_earned) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Bonus Total') ?></th>
            <td><?= $this->Number->format($payslip->bonus_total) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Deduction Total') ?></th>
            <td><?= $this->Number->format($payslip->deduction_total) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Net Salary') ?></th>
            <td><?= $this->Number->format($payslip->net_salary) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Payment Date') ?></th>
            <td><?= h($payslip->payment_date) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($payslip->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($payslip->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Bonuses') ?></h4>
        <?php if (!empty($payslip->bonuses)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Payslip Id') ?></th>
                <th scope="col"><?= __('Type') ?></th>
                <th scope="col"><?= __('Amount') ?></th>
                <th scope="col"><?= __('Remarks') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($payslip->bonuses as $bonuses): ?>
            <tr>
                <td><?= h($bonuses->id) ?></td>
                <td><?= h($bonuses->payslip_id) ?></td>
                <td><?= h($bonuses->type) ?></td>
                <td><?= h($bonuses->amount) ?></td>
                <td><?= h($bonuses->remarks) ?></td>
                <td><?= h($bonuses->created) ?></td>
                <td><?= h($bonuses->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Bonuses', 'action' => 'view', $bonuses->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Bonuses', 'action' => 'edit', $bonuses->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Bonuses', 'action' => 'delete', $bonuses->id], ['confirm' => __('Are you sure you want to delete # {0}?', $bonuses->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Deductions') ?></h4>
        <?php if (!empty($payslip->deductions)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Payslip Id') ?></th>
                <th scope="col"><?= __('Type') ?></th>
                <th scope="col"><?= __('Amount') ?></th>
                <th scope="col"><?= __('Remarks') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($payslip->deductions as $deductions): ?>
            <tr>
                <td><?= h($deductions->id) ?></td>
                <td><?= h($deductions->payslip_id) ?></td>
                <td><?= h($deductions->type) ?></td>
                <td><?= h($deductions->amount) ?></td>
                <td><?= h($deductions->remarks) ?></td>
                <td><?= h($deductions->created) ?></td>
                <td><?= h($deductions->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Deductions', 'action' => 'view', $deductions->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Deductions', 'action' => 'edit', $deductions->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Deductions', 'action' => 'delete', $deductions->id], ['confirm' => __('Are you sure you want to delete # {0}?', $deductions->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
