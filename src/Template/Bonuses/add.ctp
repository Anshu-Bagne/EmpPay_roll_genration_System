<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Bonus $bonus
 * @var \Cake\Collection\CollectionInterface|\App\Model\Entity\Employee[] $employees
 */
?>

<div class="content-area">
    <?= $this->Form->create($bonus) ?>
    <fieldset>
        <legend><?= __('Employee Bonus Management') ?></legend>
        <div class="input text">
            <label>Search Employee</label>
            <input
                type="text"
                id="employeeSearch"
                placeholder="Enter Employee Code or Name">
           </div>
        <?php
            echo $this->Form->control('employee_id', ['label' => 'Employee','options' => $employees,'empty' => '-- Select Employee code/Name --','id' => 'employeeDropdown']);
            echo $this->Form->control('type', ['label' => 'Bonus Type','type' => 'select',
            'options' => ['Performance' => 'Performance','Festival' => 'Festival'],'empty' => '-- Select Bonus Type --']);
            echo $this->Form->control('amount', ['label' => 'Bonus Amount','type' => 'number','step' => '0.01','min' => 1]);
            echo $this->Form->control('payroll_month', [
                'label' => 'Payroll Month',
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
                    ],
                    'empty' => '-- Select Month --'
                ]);
            echo $this->Form->control('payroll_year', ['label' => 'Payroll Year','type' => 'select',
            'options' => [
                date('Y') - 1 => date('Y') - 1,
                date('Y') => date('Y'),
                date('Y') + 1 => date('Y') + 1
                ]
            ]);
            echo $this->Form->control('remarks', ['type' => 'textarea','rows' => 3,'placeholder' => 'Enter remarks (optional)']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Save BONUS')) ?>
    <?= $this->Form->end() ?>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const search = document.getElementById("employeeSearch");
    const dropdown = document.getElementById("employeeDropdown");

    // Save original options
    const allOptions = Array.from(dropdown.options);

    search.addEventListener("input", function () {

        let keyword = this.value.toLowerCase().trim();

        // Clear dropdown
        dropdown.innerHTML = "";
    
        allOptions.forEach(function (option) {
            
        // Always keep the empty option
            if (option.value === "") {
                dropdown.appendChild(option.cloneNode(true));
                return;
            }
            // Match employee code or name
            if (option.text.toLowerCase().indexOf(keyword) !== -1) {
                dropdown.appendChild(option.cloneNode(true));
            }

        });

    });


});
</script>
</div>
