<nav class="large-2 medium-3 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('list Bonus'), ['controller'=> 'Bonuses','action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Payslips'), [ 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('list Employee'), ['controller' => 'Employees', 'action' => 'index']) ?></li>
    </ul>
</nav>
<div class="payslips form large-10 medium-9 columns content">

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

<h4>Bonuses</h4>
<button type="button" id="addBonus"class="button"> 
          + Add Bonus
        </button>

<table width="100%" id="bonusTable">
<thead>
<tr>

<th>Type</th>
<th>Amount</th>
<th>Remarks</th>
<th>Action</th>

</tr>
</thead>
<tbody id="bonusBody">
</tbody>

</table>
  <h4>Deductions</h4>

<button type="button" id="addDeduction" class="button">
          + Add Deduction
</button>

<table id="deductionTable" width="100%">
<thead>
<tr>
<th>Type</th>
<th>Amount</th>
<th>Remarks</th>
<th>Action</th>
</tr>
</thead>

<tbody id="deductionBody">

</tbody>
<!-- payment date on -->
<?= $this->Form->control('payment_date', [
    'type' => 'date',
     'label' => 'Payment On',
    'value' => date('Y-m-d')
]);
?> 

</table>
<h4>Payroll Preview</h4>
<table width="100%">
<tr>
    <th>Base Salary</th>
    <td id="previewBaseSalary">0</td>
</tr>
<tr>
    <th>Salary Earned</th>
    <td id="previewSalaryEarned">0</td>
</tr>
<tr>
    <th>Total Bonus</th>
    <td id="previewBonus">0</td>
</tr>
<tr>
    <th>Total Deduction</th>
    <td id="previewDeduction">0</td>
</tr>
<tr>
    <th>Net Salary</th>
    <td id="previewNetSalary">0</td>
</tr>
</table>

</fieldset>
<?= $this->Form->hidden('working_days', ['id' => 'workingDaysInput']); ?>
<?= $this->Form->hidden('present_days', ['id' => 'presentDaysInput']); ?>
<?= $this->Form->hidden('leave_days', ['id' => 'leaveDaysInput']); ?>
<?= $this->Form->hidden('absent_days', ['id' => 'absentDaysInput']); ?>
<?= $this->Form->hidden('base_salary', ['id' => 'baseSalaryInput']); ?>
<?= $this->Form->hidden('salary_earned', ['id' => 'salaryEarnedInput']); ?>
<?= $this->Form->hidden('bonus_total', ['id' => 'bonusTotalInput']); ?>
<?= $this->Form->hidden('deduction_total', ['id' => 'deductionTotalInput']); ?>
<?= $this->Form->hidden('net_salary', ['id' => 'netSalaryInput']); ?>

    
<?= $this->Form->button(__('Save')); ?>
<?= $this->Form->end(); ?>

<?php $bonusDropdown='';
foreach ($bonusOptions as $key=>$value) {
    $bonusDropdown.='<option value="'.$key.'">'.$value.'</option>';
}?>

<?php $deductionDropdown='';
    foreach ($deductionOptions as $key=>$value) {
        $deductionDropdown.='<option value="'.$key.'">'.$value.'</option>';
    }?>

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

            $('#previewBaseSalary').text(response.base_salary);
            $('#previewBonus').text(response.bonus_total);
            $('#previewSalaryEarned').text(response.salary_earned);
            $('#previewNetSalary').text(response.net_salary);
            $('#previewDeduction').text(response.deduction_total);

            $('#workingDaysInput').val(response.working_days);
            $('#presentDaysInput').val(response.present_days);

            $('#leaveDaysInput').val(response.leave_days);
            $('#absentDaysInput').val(response.absent_days);
            $('#baseSalaryInput').val(response.base_salary);
            $('#salaryEarnedInput').val(response.salary_earned);
            $('#bonusTotalInput').val(response.bonus_total);
            $('#deductionTotalInput').val(response.deduction_total);
            $('#netSalaryInput').val(response.net_salary);
        }

    });

});

let bonusIndex=0;
$('#addBonus').click(function(){

let row=`<tr class="bonus-row">
<td>
<select name="bonuses[${bonusIndex}][type]"
class="bonus-type">
    <option value="">Select</option> <?= $bonusDropdown ?>
</select>
</td>

<td>
<input type="number" class="bonus-amount" name="bonuses[${bonusIndex}][amount]" step="0.01">
</td>

<td>
<input type="text" name="bonuses[${bonusIndex}][remarks]">
</td>

<td>
<button type="button" class="removeBonus"> 
✖
</button>
</td>

</tr>

`;

$('#bonusBody').append(row);
bonusIndex++;
});


$(document).on('click','.removeBonus',function(){
    $(this).closest('tr').remove();
    calculatePreview();
});


$(document).on('keyup change','.bonus-amount',function(){
    calculatePreview();
});

let deductionIndex = 0;

$('#addDeduction').click(function(){

let row = `
<tr class="deduction-row">
 <td>
<select
name="deductions[${deductionIndex}][type]"
class="deduction-type">

<option value="">Select</option><?= $deductionDropdown ?>
</select>
</td>

<td>
<input type="number" class="deduction-amount" name="deductions[${deductionIndex}][amount]" step="0.01">
</td>

<td>
<input type="text" name="deductions[${deductionIndex}][remarks]">
</td>

<td>
<button type="button" class="removeDeduction">
✖
</button>
</td>
</tr>

`;

$('#deductionBody').append(row);
deductionIndex++;
});

   $(document).on('click','.removeDeduction',function(){
    $(this).closest('tr').remove();
    calculatePreview();});

$(document).on('keyup change','.deduction-amount',function(){
    calculatePreview();
});

function calculatePreview()
{
    let salaryEarned =parseFloat($('#previewSalaryEarned').text())||0;
    let totalBonus = 0;
    
    $('.bonus-amount').each(function(){
          totalBonus +=parseFloat($(this).val())||0;});
    let totalDeduction = 0;

    $('.deduction-amount').each(function(){
       totalDeduction +=parseFloat($(this).val())||0;});
    let netSalary =salaryEarned+totalBonus-totalDeduction;

    $('#previewBonus').text(totalBonus.toFixed(2));
    $('#previewDeduction').text(totalDeduction.toFixed(2));
    $('#previewNetSalary').text(netSalary.toFixed(2));
    //hidden terms
    $('#bonusTotalInput').val(totalBonus.toFixed(2));
    $('#deductionTotalInput').val(totalDeduction.toFixed(2));
    $('#netSalaryInput').val(netSalary.toFixed(2));

}

$(function () {
    $('.date-picker').attr('type', 'date');
});


</script>

</div>