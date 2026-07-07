<h2>Mark Attendance</h2>

<!-- Date & Filter Form -->
<?= $this->Form->create(null, ['type' => 'get']) ?>

<div style="margin-bottom:20px;">

    <label><strong>Attendance Date</strong></label><br>

    <input
        type="date"
        name="attendance_date"
        value="<?= h($attendanceDate ?? '') ?>"
        max="<?= date('Y-m-d') ?>"
        required>

    <br><br>

    <?= $this->Form->control('status_filter', [
        'type' => 'select',
        'label' => 'Status Filter',
        'options' => [
            'present' => 'Present',
            'absent' => 'Absent',
            'leave' => 'Leave',
            'not_marked' => 'Not Marked'
        ],
        'empty' => 'All',
        'value' => $statusFilter,

    ]) ?>

    <?= $this->Form->button('Load Employees') ?>

</div>

<?= $this->Form->end() ?>


<!-- Employee Table -->
 <?php if (!empty($employees)): ?>

<?= $this->Form->create(null, ['url' => ['action' => 'saveAttendance']]) ?>

<?= $this->Form->hidden('attendance_date', ['value' => $attendanceDate]) ?>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
<thead>
        <tr>
             <th>Employee Code</th>
             <th>Employee Name</th>
             <th>Status</th>
        </tr>
</thead>
<tbody>
    <?php foreach ($employees as $employee): ?>

        <?php
        $selectedStatus = '';
        if (!empty($employee->attendances)) {
            $selectedStatus = $employee->attendances[0]->status;//[0]-> contain(['Attendances']) so to get the exact val on P/A/L
        }?>
        <tr>

            <td><?= h($employee->employee_code) ?></td>
            <td><?= h($employee->name) ?></td>
            <td>

                <?= $this->Form->hidden(
            "attendance.$employee->id.employee_id",
            ['value' => $employee->id]
        ) ?>

                <?= $this->Form->control(
            "attendance.$employee->id.status",
            [
                    'type' => 'select',
                    'label' => false,
                    'options' => [
                        'present' => 'Present',
                        'absent' => 'Absent',
                        'leave' => 'Leave'],
                    'empty' => 'Select',
                    'value' => $selectedStatus,
                    'class' => 'attendance-status',
                    'data-employee-id' => $employee->id,
                    'data-date' => $attendanceDate
                    ]
        )?>
            <span class="save-icon" style="margin-left:8px;font-size:18px;"></span>

                

            </td>

        </tr>

    <?php endforeach; ?>

    </tbody>

</table>

<br>

<?= $this->Form->end() ?>

<?php else: ?>

<?php if (!empty($attendanceDate)): ?>

<div style="
margin-top:20px;
padding:15px;
background:#fff3cd;
border:1px solid #fdfdfd;
border-radius:5px;
">

<?php if (!empty($statusFilter)): ?>

<strong>
No employees found for the selected status.
</strong>

<?php else: ?>
    
<strong>
No employees available for the selected date.
</strong>

<?php endif; ?>

</div> 
<?php endif; ?>
<?php endif; ?>



<script>

document.querySelectorAll('.attendance-status').forEach(function(dropdown){

    dropdown.addEventListener('change', function(){

        let employeeId = this.dataset.employeeId;
        let attendanceDate = this.dataset.date;
        let status = this.value;
       let icon = this.closest("td").querySelector(".save-icon");
        icon.innerHTML = "⏳";
        icon.style.color = "orange";

        fetch("<?= $this->Url->build(['controller' => 'Attendances','action' => 'ajaxSaveAttendance']) ?>"
        ,{
            method: "POST",
               headers: {
                "Content-Type": "application/json",
                "X-CSRF-Token": document
                    .querySelector('meta[name="csrfToken"]')
                    .getAttribute('content')
            },

            body: JSON.stringify({
                employee_id: employeeId,
                attendance_date: attendanceDate,
                status: status
            })
        })
        .then(function(response){

    return response.json();

})
.then(function(data){

    if(data.success){

    icon.innerHTML = "✔";
    icon.style.color = "green";

    }else{

    icon.innerHTML = "✖";
    icon.style.color = "red";

}


})
       .catch(function(error){
               icon.innerHTML = "✖";
              icon.style.color = "red";
    console.log(error);

});
    });
});

</script>