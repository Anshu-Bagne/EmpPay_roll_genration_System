<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Employee[]|\Cake\Collection\CollectionInterface $employees
 */
?>
<!-- <nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Employee'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Departments'), ['controller' => 'Departments', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Department'), ['controller' => 'Departments', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Designations'), ['controller' => 'Designations', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Designation'), ['controller' => 'Designations', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Attendances'), ['controller' => 'Attendances', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Attendance'), ['controller' => 'Attendances', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Payslips'), ['controller' => 'Payslips', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Payslip'), ['controller' => 'Payslips', 'action' => 'add']) ?></li>
    </ul>
</nav> -->
<div class="employees index large-9 medium-8 columns content">
    <h3><?= __('Employees Management') ?></h3>
    <div class="row" style="margin-bottom:20px;">
      <p>  
      <?= $this->Html->link('Add Employee',['action' => 'add'],['class' => 'button']) ?>
     </p> 

    <?= $this->Form->create(null, ['type' => 'get']) ?>
    <?= $this->Form->control('search', ['label' => false,'placeholder' => 'Search by Employee Code, Name, Email or Mobile','value' => $search]) ?>
    <?= $this->Form->control('department', ['type' => 'select','options' => $departments,'empty' => 'All Departments','label' => false,'value' => $department]);?>
    <?= $this->Form->control('status', [
       'type' => 'select',
       'options' => ['active' => 'Active','inactive' => 'Inactive'],
       'empty' => 'All Status','label' => 'Status','value' => $status]) ?>
    <?= $this->Form->button('Search') ?> 
    <?= $this->Html->link('Reset',['action' => 'index'],['class' => 'button']) ?>
    <?= $this->Form->end() ?>

</div>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <!-- <th scope="col"><?= $this->Paginator->sort('id') ?></th> -->
                <th scope="col"><?= $this->Paginator->sort('employee_code') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('department_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('designation_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('base_salary') ?></th>
                <th scope="col"><?= $this->Paginator->sort('joining_date') ?></th>
                <th scope="col"><?= $this->Paginator->sort('email') ?></th>
                <th scope="col"><?= $this->Paginator->sort('mobile') ?></th>
                <th scope="col"><?= $this->Paginator->sort('status') ?></th>
                <!-- <th scope="col"><?= $this->Paginator->sort('created') ?></th> -->
                <!-- <th scope="col"><?= $this->Paginator->sort('modified') ?></th> -->
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employees as $employee): ?>
            <tr>
                <!-- <td><?= $this->Number->format($employee->id) ?></td> -->
                <td><?= h($employee->employee_code) ?></td>
                <td><?= h($employee->name) ?></td>
               <td><?= $employee->has('department')? h($employee->department->name): '' ?></td> 
                <td><?= $employee->has('designation')? h($employee->designation->name): '' ?></td>
                <td><?= '₹ ' . number_format($employee->base_salary, 2) ?></td>
                <td><?= $employee->joining_date->format('d-M-Y') ?></td>
                <td><?= h($employee->email) ?></td>
                <td><?= h($employee->mobile) ?></td>
                <td><?php if ($employee->status == 'active'): ?>
                    <span style="color:green;font-weight:bold;">● Active</span>
                    <?php else: ?>
                    <span style="color:red;font-weight:bold;">● Inactive</span>
                    <?php endif; ?>
                </td>
                <!-- <td><?= h($employee->created) ?></td> -->
                <!-- <td><?= h($employee->modified) ?></td> -->
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $employee->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $employee->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $employee->id], ['confirm' => __('Are you sure you want to delete employee {0}?', $employee->employee_code)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
    <?php $this->Paginator->options(['url' => ['search' => $search,'department' => $department,'status' => $status]]);?>
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Showing {{current}} employee(s) out of {{count}} total')]) ?></p>
    </div>
</div>


