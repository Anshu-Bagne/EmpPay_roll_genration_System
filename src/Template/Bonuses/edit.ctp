<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Bonus $bonus
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $bonus->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $bonus->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Bonuses'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="bonuses form large-9 medium-8 columns content">
    <?= $this->Form->create($bonus) ?>
    <fieldset>
        <legend><?= __('Edit Bonus') ?></legend>
        <?php
            echo $this->Form->control('employee_id', ['options' => $employees]);
            echo $this->Form->control('type');
            echo $this->Form->control('amount');
            echo $this->Form->control('payroll_month');
            echo $this->Form->control('payroll_year');
            echo $this->Form->control('remarks');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
