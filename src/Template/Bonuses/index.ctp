<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Bonus[]|\Cake\Collection\CollectionInterface $bonuses
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Bonus'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="bonuses index large-9 medium-8 columns content">
    <h3><?= __('Bonuses') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('employee_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('type') ?></th>
                <th scope="col"><?= $this->Paginator->sort('amount') ?></th>
                <th scope="col"><?= $this->Paginator->sort('payroll_month') ?></th>
                <th scope="col"><?= $this->Paginator->sort('payroll_year') ?></th>
                <th scope="col"><?= $this->Paginator->sort('remarks') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bonuses as $bonus): ?>
            <tr>
                <td><?= $this->Number->format($bonus->id) ?></td>
                <td><?= $bonus->has('employee') ? $this->Html->link($bonus->employee->name, ['controller' => 'Employees', 'action' => 'view', $bonus->employee->id]) : '' ?></td>
                <td><?= h($bonus->type) ?></td>
                <td><?= $this->Number->format($bonus->amount) ?></td>
                <td><?= $this->Number->format($bonus->payroll_month) ?></td>
                <td><?= h($bonus->payroll_year) ?></td>
                <td><?= h($bonus->remarks) ?></td>
                <td><?= h($bonus->created) ?></td>
                <td><?= h($bonus->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $bonus->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $bonus->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $bonus->id], ['confirm' => __('Are you sure you want to delete # {0}?', $bonus->id)]) ?>
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
