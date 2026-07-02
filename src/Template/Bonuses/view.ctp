<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Bonus $bonus
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Bonus'), ['action' => 'edit', $bonus->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Bonus'), ['action' => 'delete', $bonus->id], ['confirm' => __('Are you sure you want to delete # {0}?', $bonus->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Bonuses'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Bonus'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="bonuses view large-9 medium-8 columns content">
    <h3><?= h($bonus->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Employee') ?></th>
            <td><?= $bonus->has('employee') ? $this->Html->link($bonus->employee->name, ['controller' => 'Employees', 'action' => 'view', $bonus->employee->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Type') ?></th>
            <td><?= h($bonus->type) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Payroll Year') ?></th>
            <td><?= h($bonus->payroll_year) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Remarks') ?></th>
            <td><?= h($bonus->remarks) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($bonus->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Amount') ?></th>
            <td><?= $this->Number->format($bonus->amount) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Payroll Month') ?></th>
            <td><?= $this->Number->format($bonus->payroll_month) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($bonus->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($bonus->modified) ?></td>
        </tr>
    </table>
</div>
