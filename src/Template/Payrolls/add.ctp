<div class="payrolls form large-10 medium-9 columns">
    <?= $this->Form->create($payroll, ['data-toggle' => 'validator']) ?>
    <fieldset>
        <legend><?= __('Add Payroll Item') ?></legend>
        <?php
            echo $this->Form->input('show_id', ['label' => __('Show Name'), 'options' => $shows]);
            echo $this->Form->input('user_id', ['label' => __('User'), 'options' => $users]);
            echo $this->Form->input('notes', ['label' => __('Notes')]);
            echo $this->Pretty->datePicker('date_worked', __('Date Worked'));
            echo $this->Pretty->clockPicker('start_time', __('Start Time'), '9:00');
            echo $this->Pretty->clockPicker('end_time', __('End Time'),  '16:00');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

<?= $this->Pretty->helpMeStart(__('Add Payroll Item')); ?>
<p><?= __("This display allows you to add a payroll expense."); ?></p>
<table class="table table-condensed helptable">
<?= $this->Html->tableCells([
    [__("Show Name"),        __("Name of the show")],
    [__("User Name"),        __("Name of the user")],
    [__('Notes'),            __("A short description of the expense")],
    [__('Date Worked'),      __("The date the work was completed on")],
    [__('Start Time'),       __("The beginning time of the work")],
    [__('End Time'),         __("The ending time of the work")]
]); ?>
</table>
<?= $this->Pretty->helpMeEnd(); ?>
