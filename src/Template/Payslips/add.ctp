<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Payslip $payslip
 */
?>
<nav class="large-2 medium-3 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Payslips'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Employees'), ['controller' => 'Employees', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Employee'), ['controller' => 'Employees', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('Generate Payslip'), ['action' => 'generate']) ?></li>
    </ul>
</nav>
<div class="payslips form large-10 medium-9 columns content">
    <?= $this->Form->create($payslip) ?>
    <fieldset>
        <legend><?= __('Add Payslip') ?></legend>
        <h4>Employee Information</h4>
          <?= $this->Form->control('employee_id', [
             'label' => 'Employee',
             'options' => $employees,
             'empty' => 'Select Employee'
          ]); ?>

         <h4>Payroll Information</h4>

         <?= $this->Form->control('payroll_month', [
               'type' => 'select',
              'options' => [
                 1=>'January',
                 2=>'February',
                 3=>'March',
                 4=>'April',
                 5=>'May',
                 6=>'June',
                 7=>'July',
                 8=>'August',
                 9=>'September',
                 10=>'October',
                 11=>'November',
                 12=>'December'
                ],
            'empty'=>'Select Month'
           ]); ?>

           <table>
<h4>Employee Summary</h4>
<table width="100%">
<tr>

<th>Employee Code</th>
<td id="employeeCode">-</td>

<th>Joining Date</th>
<td id="joiningDate">-</td>

<th>Present Days</th>
<td id="presentDays">0</td>

<tr>

<th>Department</th>
<td id="department">-</td>

<th>Base Salary</th>
<td id="baseSalary">0</td>

<th>Leave Days</th>
<td id="leaveDays">0</td>

 
</tr>


<tr>

<th>Designation</th>
<td id="designation">-</td>
<th>Working Days</th>
    <td id="workingDays">0</td>


<th>Absent Days</th>
<td id="absentDays">0</td>
</tr>

</table>

         <?= $this->Form->control('payroll_year', [
                'type'=>'number',
             'value'=>date('Y')
           ]); ?>

          <?= $this->Form->control('payment_date', [
                'type'=>'date',
               'value'=>date('Y-m-d')
               ]); ?>

               <h4>Bonuses</h4>
               <button type="button" id="addBonus"  class="button">
                     + Add Bonus
                </button>
                   <table id="bonusTable" width="100%">
                   <thead>
                        <tr>
                                <th>Bonus Type</th>
                                <th>Amount</th>
                                <th>Remarks</th>
                                <th width="100"></th>
                        </tr>
                  </thead> 
                        <tbody id="bonusBody"></tbody>
                    </table>

          <div id="bonusContainer"></div>

               <h4>Manual Deductions</h4>
                    <button type="button" id="addDeduction" class="button">
                        + Add Deduction
                    </button>

                    <table id="deductionTable" width="100%">
                        <thead>
                            <tr>
                                <th>Deduction Type</th>
                                <th>Amount</th>
                                <th>Remarks</th>
                                <th width="100"></th>
                         </tr>
                       </thead>
                        <tbody id="deductionBody"></tbody>
                    </table>

        <h4>Payroll Preview</h4>
<table class="salary-report-table">
<tr>
    <th>Base Salary</th>
    <td id="previewBaseSalary">0</td>
</tr>

<tr>
    <th>Total Bonus</th>
    <td id="previewBonus">0</td>
</tr>

<tr>
    <th>PF</th>
    <td id="previewPF">0</td>
</tr>

<tr>
    <th>TDS</th>
    <td id="previewTDS">0</td>
</tr>

<tr>
    <th>Manual Deduction</th>
    <td id="previewManualDeduction">0</td>
</tr>

<tr>
    <th>Salary Earned</th>
    <td id="previewSalaryEarned">0</td>
</tr>

<tr>
    <th>Total Deduction</th>
    <td id="previewTotalDeduction">0</td>
</tr>

<tr>
    <th>Net Salary</th>
    <td id="previewNetSalary">0</td>
</tr>

</table>
     <!-- Hidden Payroll Fields -->

<?= $this->Form->hidden('base_salary', ['id' => 'hiddenBaseSalary']) ?>
<?= $this->Form->hidden('working_days', [ 'id' => 'hiddenWorkingDays']) ?>
<?= $this->Form->hidden('present_days', ['id' => 'hiddenPresentDays']) ?>
<?= $this->Form->hidden('salary_earned', ['id' => 'hiddenSalaryEarned']) ?>
<?= $this->Form->hidden('pf_amount', ['id' => 'hiddenPF']) ?>
<?= $this->Form->hidden('tds_amount', [ 'id' => 'hiddenTDS']) ?>
<?= $this->Form->hidden('bonus_total', ['id' => 'hiddenBonusTotal']) ?>
<?= $this->Form->hidden('deduction_total', [ 'id' => 'hiddenDeductionTotal']) ?>
<?= $this->Form->hidden('unpaid_leave_deduction', [ 'id' => 'hiddenUnpaidLeave']) ?>
<?= $this->Form->hidden('net_salary', [ 'id' => 'hiddenNetSalary']) ?>
               
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>

    <?php
    $bonusDropdown = '';
          foreach ($bonusOptions as $value => $label) {
              $bonusDropdown .='<option value="' .h($value) .'">' .h($label) .'</option>';
          }
    $deductionDropdown = '';
          foreach ($deductionOptions as $value => $label) {
              $deductionDropdown .='<option value="' .h($value) .'">' .h($label) .'</option>';
          } ?>

<style>
.remove-btn{
    width:32px;
    height:32px;
    padding:0;
    border:none;
    border-radius:50%;
    background:#dc3545;
    color:#fff;
    font-size:18px;
    font-weight:bold;
    cursor:pointer;
    transition:.2s;
}

.remove-btn:hover{
    background:#b02a37;
    transform:scale(1.08);
}
</style>
    
    <script>
       let bonusIndex = 0;
      let deductionIndex = 0;
$('#addBonus').click(function () {
    let row =`
        <tr>
            <td>

                <select
                    name="bonuses[${bonusIndex}][type]"
                    class="bonus-type">

                    <option value="">Select</option><?= $bonusDropdown ?>
                </select>
            </td>
            <td>
                <input type="number" name="bonuses[${bonusIndex}][amount]" min="0" step="0.01" class="bonus-amount">
            </td>
            <td>
                <input
                    type="text"
                    name="bonuses[${bonusIndex}][remarks]">
            </td>
            <td>
                <button
                    type="button"
                    class="removeBonus remove-btn"
                    title="Remove Bonus">
                     ✖
                </button>
            </td>
        </tr>
    `;
    $('#bonusBody').append(row);
    bonusIndex++;
    loadPayrollPreview();
});
       
$(document).on('click','.removeBonus',function(){
    $(this).closest('tr').remove();
   loadPayrollPreview();
});
 
// Whenever bonus amount changes
$(document).on('keyup change','.bonus-amount,.bonus-type',
    function () {
        loadPayrollPreview();
    }
);

$('#addDeduction').click(function(){
    let row =`
        <tr>
            <td>
                <select
                    name="deductions[${deductionIndex}][type]">
                    <option value="">Select</option><?= $deductionDropdown ?>
                    <option value="Other">Other</option>
                </select>
            </td>
            <td>
                <input
                    type="number"
                    name="deductions[${deductionIndex}][amount]"
                    class="deduction-amount"
                    min="0"
                    step="0.01">
            </td>
            <td>
                <input
                    type="text"
                    name="deductions[${deductionIndex}][remarks]">
          </td>
            <td>
                <button  type="button"
                        class="removeDeduction remove-btn"
                        title="Remove Deduction">
                         ✖
                </button>
            </td>
        </tr>
    `;

    $('#deductionBody').append(row);
    deductionIndex++;
    loadPayrollPreview();
});

$(document).on('click','.removeDeduction',function(){
    $(this).closest('tr').remove();
    loadPayrollPreview();
});

// Whenever deduction amount changes
$(document).on('keyup change','.deduction-amount,select[name*="deductions"]',
    function () {
        loadPayrollPreview();
    }
);
      //  load the data of emp payroll
$('#employee-id, #payroll-month, #payroll-year').change(function () {
    loadPayrollPreview();
});

  function loadPayrollPreview() {
    let employeeId = $('#employee-id').val();
    let month = $('#payroll-month').val();
    let year = $('#payroll-year').val();
    if (employeeId == '' || month == '' || year == '') {
        return;
    }

    let bonuses = [];

    $('#bonusBody tr').each(function () {

        bonuses.push({
            type: $(this).find('.bonus-type').val(),
            amount: parseFloat($(this).find('.bonus-amount').val()) || 0
        });
    });

    let deductions = [];
    $('#deductionBody tr').each(function () {
        deductions.push({
            type: $(this).find('select').val(),
            amount: parseFloat($(this).find('.deduction-amount').val()) || 0
        });
    });

    $.ajax({
        type: 'POST',
        url: "<?= $this->Url->build(['action'=>'getEmployeePayrollDetails']) ?>",
        data: {
            employee_id: employeeId,
            payroll_month: month,
            payroll_year: year,
            bonuses: bonuses,
            deductions: deductions
        },

        headers: {
            "X-CSRF-Token":
            $('meta[name="csrfToken"]').attr('content')
        },

        dataType: 'json',

        success: function (response) {
            console.log(response);
            updatePreview(response);
        }
    });
}

function updatePreview(response){

    $('#employeeCode').text(response.employee_code);
    $('#department').text(response.department);
    $('#designation').text(response.designation);
    $('#joiningDate').text(response.joining_date);
    $('#baseSalary').text(response.base_salary);
    $('#presentDays').text(response.present_days);
    $('#leaveDays').text(response.leave_days);
    $('#workingDays').text(response.working_days);
    $('#absentDays').text(response.absent_days);
    // Preview
    $('#previewBaseSalary').text(response.base_salary);
    $('#previewBonus').text(response.bonus_total);
    $('#previewPF').text(response.pf_amount);
    $('#previewTDS').text(response.tds_amount);
    $('#previewManualDeduction').text(response.manual_deduction);
    $('#previewSalaryEarned').text(response.salary_earned);
    $('#previewTotalDeduction').text(response.total_deduction);
    $('#previewNetSalary').text(response.net_salary);
    //hidden val
    $('#hiddenBaseSalary').val(response.base_salary);
    $('#hiddenWorkingDays').val(response.working_days);
    $('#hiddenPresentDays').val(response.present_days);
    $('#hiddenSalaryEarned').val(response.salary_earned);
    $('#hiddenPF').val(response.pf_amount);
    $('#hiddenTDS').val(response.tds_amount);
    $('#hiddenBonusTotal').val(response.bonus_total);
    $('#hiddenDeductionTotal').val(response.total_deduction);
    $('#hiddenUnpaidLeave').val(response.unpaid_leave_deduction);
    $('#hiddenNetSalary').val(response.net_salary);

}

    // function calculatePreview(){ 
    //     let baseSalary =parseFloat($('#baseSalary').text()) || 0;
    //     let presentDays =parseInt($('#presentDays').text()) || 0;
    //     let leaveDays =parseInt($('#leaveDays').text()) || 0;
    //     let woringDays =parseInt($('#workingDays').text()) || 0;
         
    //     let totalBonus = 0;
    //     $('.bonus-amount').each(function(){
    //         totalBonus +=parseFloat($(this).val()) || 0;
    //     });
    //     let manualDeduction = 0;
    //     $('.deduction-amount').each(function(){
    //         manualDeduction +=parseFloat($(this).val()) || 0;
    //     });
    //     let salaryEarned =(baseSalary / workingDays) *(presentDays + leaveDays);
    //     let pf =salaryEarned * 0.12;
    //     let tds =salaryEarned * 0.10;
    //     let totalDeduction =pf +tds +manualDeduction;
    //     let netSalary =salaryEarned +totalBonus -totalDeduction;
    //     //display 
    //     $('#previewBaseSalary').text(baseSalary.toFixed(2));
    //     $('#previewBonus').text(totalBonus.toFixed(2));
    //     $('#previewPF').text(pf.toFixed(2));
    //     $('#previewTDS').text(tds.toFixed(2));
    //     $('#previewManualDeduction').text(manualDeduction.toFixed(2));
    //     $('#previewSalaryEarned').text(salaryEarned.toFixed(2));
    //     $('#previewTotalDeduction').text(totalDeduction.toFixed(2));
    //     $('#previewNetSalary').text(netSalary.toFixed(2));
    // }



</script>





</div>
