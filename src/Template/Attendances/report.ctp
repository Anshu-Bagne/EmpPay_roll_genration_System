<h2>Attendance Report</h2>

<?= $this->Form->create(null, ['type' => 'get']) ?>

<?= $this->Form->control('month', [
    'label' => 'Month',
    'options' => [
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December'
    ],
    'empty' => 'Select Month',
    'value' => $month
]); ?>

<?= $this->Form->control('year', [
    'type' => 'number',
    'value' => $year ?? date('Y')
]); ?>

<?= $this->Form->button('Generate Report') ?>
<?= $this->Form->end(); ?>
<?php if (!empty($employees) && !empty($dates)): ?>

<div class="attendance-report">

<table class="attendance-table">

    <thead>

        <tr>

            <th>Employee<br>Code</th>

            <th>Employee<br>Name</th>

            <?php foreach ($dates as $date): ?>

                <th><?= date('d', strtotime($date)) ?></th>

            <?php endforeach; ?>

        </tr>

    </thead>

    <tbody>

        <?php foreach ($employees as $employee): ?>

        <tr>

            <td><?= h($employee->employee_code) ?></td>

            <td><?= h($employee->name) ?></td>

            <?php foreach ($dates as $date): ?>

                <td>

                <?php

                $status = $attendanceMatrix[$employee->id][$date] ?? '-';

                switch ($status) {

                    case 'present':
                        echo '<span class="present">P</span>';
                        break;

                    case 'absent':
                        echo '<span class="absent">A</span>';
                        break;

                    case 'leave':
                        echo '<span class="leave">L</span>';
                        break;

                    default:
                        echo '-';
                }

                ?>
                </td>
            <?php endforeach; ?>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php endif; ?>