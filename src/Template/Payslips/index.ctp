<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Payslip[]|\Cake\Collection\CollectionInterface $payslips
 */
?>
<nav class="large-2 medium-3 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Payslip'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="payslips index large-10 medium-9 columns content">
    <h3><?= __('Payslips') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('employee_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('payroll_month') ?></th>
                <th scope="col"><?= $this->Paginator->sort('payroll_year') ?></th>
                <th scope="col"><?= $this->Paginator->sort('working_days') ?></th>
                <th scope="col"><?= $this->Paginator->sort('present_days') ?></th>
                <th scope="col"><?= $this->Paginator->sort('base_salary') ?></th>
                <th scope="col"><?= $this->Paginator->sort('bonus_total') ?></th>
                <th scope="col"><?= $this->Paginator->sort('deduction_total') ?></th>
                <th scope="col"><?= $this->Paginator->sort('net_salary') ?></th>
                <th scope="col"><?= $this->Paginator->sort('payment_date') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($payslips as $payslip): ?>
            <tr>
                <td><?= $this->Number->format($payslip->id) ?></td>
                <td><?= $payslip->has('employee') ? $this->Html->link($payslip->employee->name, ['controller' => 'Employees', 'action' => 'view', $payslip->employee->id]) : '' ?></td>
                <td><?= $this->Number->format($payslip->payroll_month) ?></td>
                <td><?= h($payslip->payroll_year) ?></td>
                <td><?= $this->Number->format($payslip->working_days) ?></td>
                <td><?= $this->Number->format($payslip->present_days) ?></td>
                <td><?= $this->Number->format($payslip->base_salary) ?></td>
                <td><?= $this->Number->format($payslip->bonus_total) ?></td>
                <td><?= $this->Number->format($payslip->deduction_total) ?></td>
                <td><?= $this->Number->format($payslip->net_salary) ?></td>
                <td><?= h($payslip->payment_date) ?></td>
                <td><?= h($payslip->created) ?></td>
                <td><?= h($payslip->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $payslip->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $payslip->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $payslip->id], ['confirm' => __('Are you sure you want to delete # {0}?', $payslip->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
