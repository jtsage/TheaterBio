
<h3><?= h($show->name) . __(" Payroll Expenditure by Date"); ?></h3>
<p><?= __("Please choose the dates (inclusive)") ?></p>

<form id="daterangepick">
    <fieldset>
        <legend><?= __('Date Range') ?></legend>
    <?php
    echo $this->Pretty->datePicker('start_date', __('Start Date'), $start_time);
    echo $this->Pretty->datePicker('end_date', __('End Date'));
    ?>
    </fieldset>
    <button class="btn btn-primary" id="set_dates">View Report</button>
</form>


<?= $this->Pretty->helpMeStart(__('View Payrolls by Date')); ?>
<p><?= __("This display allows you to set a date range for payroll records to disaplay."); ?></p>
<?= $this->Pretty->helpMeEnd(); ?>
