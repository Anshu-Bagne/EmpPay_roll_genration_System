<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Attendance $attendance
 * @var \Cake\Collection\CollectionInterface|\App\Model\Entity\Employee[] $employees
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Attendances'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="attendances form large-9 medium-8 columns content">
    <?= $this->Form->create($attendance) ?>
    <fieldset>
        <legend><?= __('Add Attendance') ?></legend>
        <?php
            echo $this->Form->control('employee_id', ['options' => $employees]);
            // echo $this->Form->control('attendance_date', ['type' => 'text','label' => 'Attendance Date',
            //  'templates' => [
            //     'inputContainer' => '<div class="input {{type}}{{required}}">{{content}}</div>'],
            //
            //    'value' => isset($attendance->attendance_date)? $attendance->attendance_date->format('Y-m-d') : date('Y-m-d'),'type' => 'date','max' => date('Y-m-d')]);

        ?>
        <label>Attendance Date</label>
           <input type="date"name="attendance_date"
           value="<?= !empty($attendance->attendance_date) ? $attendance->attendance_date->format('Y-m-d') : date('Y-m-d') ?>"
           max="<?= date('Y-m-d') ?>"
          class="form-control">

        <?php echo $this->Form->control('status', ['type' => 'radio',
              'options' => ['present' => 'Present',
                            'absent' => 'Absent',
                            'leave' => 'Leave'
                            ],
                'label' => 'Attendance Status','hiddenField' => false]);
         ?>
            
        
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
