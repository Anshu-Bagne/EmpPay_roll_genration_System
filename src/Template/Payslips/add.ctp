<div class="payslips form large-9 medium-8 columns content">

<?= $this->Form->create($payslip) ?>

<fieldset>

    <legend><?= __('Generate Payslip') ?></legend>

    <?= $this->Form->control('employee_id', [
        'options' => $employees,
        'empty' => 'Select Employee'
    ]); ?>

    <?= $this->Form->control('payroll_month', [
        'type' => 'select',
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
        ]
    ]); ?>

    <?= $this->Form->control('payroll_year', [
        'value' => date('Y')
    ]); ?>


    <!-- ADD THIS BLOCK HERE -->

    <h4>Employee Summary</h4>

<table width="100%">

    <tr>
        <th>Employee Code</th>
        <td id="employeeCode">-</td>

        <th>Department</th>
        <td id="department">-</td>
    </tr>

    <tr>
        <th>Designation</th>
        <td id="designation">-</td>

        <th>Joining Date</th>
        <td id="joiningDate">-</td>
    </tr>

    <tr>
        <th>Base Salary</th>
        <td id="baseSalary">0</td>

        <th>Working Days</th>
        <td id="workingDays">0</td>
    </tr>

    <tr>
        <th>Present Days</th>
        <td id="presentDays">0</td>

        <th>Leave Days</th>
        <td id="leaveDays">0</td>
    </tr>

    <tr>
        <th>Absent Days</th>
        <td id="absentDays">0</td>

        <td></td>
        <td></td>
    </tr>

</table>

</fieldset>

<?= $this->Form->button(__('Save')); ?>

<?= $this->Form->end(); ?>

<script>

$('#employee-id,#payroll-month,#payroll-year').change(function(){
     
    let employeeId=$('#employee-id').val();
    let month=$('#payroll-month').val();
    let year=$('#payroll-year').val();

    
    if(employeeId=='' || month=='' || year==''){
        return;
    }


    $.ajax({
    

        type:'POST',
        url:"<?= $this->Url->build(['action'=>'getEmployeePayrollDetails']) ?>",
        data:{
            employee_id:employeeId,
            payroll_month:month,
            payroll_year:year
        },

        headers:{

            "X-CSRF-Token": $('meta[name="csrfToken"]').attr('content')

        },

        dataType:'json',
        success:function(response){

            $('#employeeCode').text(response.employee_code);
            $('#designation').text(response.designation);
            $('#department').text(response.department);
            $('#baseSalary').text(response.base_salary);
            $('#joiningDate').text(response.joining_date);
            
            $('#presentDays').text(response.present_days);
            $('#workingDays').text(response.working_days);
            $('#leaveDays').text(response.leave_days);
            $('#absentDays').text(response.absent_days);

        }

    });

});

</script>


</div>