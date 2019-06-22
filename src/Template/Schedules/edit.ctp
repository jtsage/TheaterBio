<div class="schedules form large-9 medium-8 columns content">
    <?= $this->Form->create($schedule) ?>
    <fieldset>
        <legend><?= __('Edit Scheduled Task') ?></legend>
        <?php
            echo $this->Form->input('jobtype', ['label' => __('Job Type'), 'options' => ['remind' => 'Send Reminders to Employees', 'unpaid' => 'Send Un-Paid Hours to Payroll Dept.', 'budget' => 'Send Budget Report', 'tasks' => 'Send Task List', 'today' => 'Send Today\'s Calendar Events']]);
            echo $this->Form->input('sendto', ['label' => __('Send-To E-Mail (ignored for "remind" task)')]);
            echo $this->Form->input('show_id', ['label' => __('Show (if applicable)'), 'options' => $shows ]);
            echo $this->Pretty->dateSPicker('start_time', __('First Valid Date/Time'), $schedule->start_time);
            echo $this->Form->input('period', ['label' => __('Period in Days')]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

<?= $this->Pretty->helpMeStart(__('Edit Scheduled Task')); ?>
<p><?= __("This display allows you to edit a scheduled task.") ?></p>

<table class="table table-condensed helptable">
<?= $this->Html->tableCells([
    [__("Job Type"),               __("Pick an available job type")],
    [__("Send-To Email"),          __("The e-mail address to send the report to")],
    [__("Show"),                   __("If applicable, show to use for report")],
    [__("First Valid Date/Time"),  __("First valid date & time to send the report")],
    [__("Period"),                 __("How many days to repeat this task in. (Default 7, one week)")],
]); ?>
</table>

<?= $this->Pretty->helpMeEnd(); ?>
