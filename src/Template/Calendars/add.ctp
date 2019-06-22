
<div class="calendars form large-9 medium-8 columns content">
    <?= $this->Form->create($calendar) ?>
    <fieldset>
	<legend><?= __('Add Event') ?> - THIS IS NOT HOW YOU ADD HOURS TO PAYROLL!</legend>
        <?php
            echo $this->Form->input('show_id', ['label' => __('Show Name'), 'options' => $shows]);
            echo $this->Form->input('title', ['label' => __("Event Title")]);
            echo $this->Form->input('category', ['label' => __('Event Category'), 'options' => ['default' => 'No Color', 'secondary' => 'Active Color (grey)', 'success' => 'Success Color (green)', 'info' => 'Info Color (lt. blue)', 'warning' => 'Warning Color (yellow)', 'danger' => 'Danger Color (red)' ]]);
            echo $this->Form->input('note', ['label' => __("Event Description")]);
            echo $this->Pretty->datePicker('date', __('Event Date'));
            echo $this->Pretty->clockPicker('start_time', __('Start Time'), '9:00');
            echo $this->Pretty->clockPicker('end_time', __('End Time'),  '16:00');
            echo $this->Pretty->check('all_day', false, [
                'label-width' => '100',
                'label-text' => __('All Day Event'),
                'on-text' => __('YES'),
                'off-text' => __('NO'),
                'on-color' => 'danger',
                'off-color' => 'success'
            ]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

<?= $this->Pretty->helpMeStart(__('Add Event')); ?>
<p><?= __("This display allows you to add a calendar event."); ?></p>
<table class="table table-condensed helptable">
<?= $this->Html->tableCells([
    [__("Show Name"),          __("Name of the show")],
    [__("Event Title"),        __("Short title of the event")],
    [__("Event Category"),     __("Category for the event")],
    [__('Event Description'),  __("A description of the event")],
    [__('Date'),               __("The date of the event")],
    [__('Start Time'),         __("The beginning time of the event")],
    [__('End Time'),           __("The ending time of the event")],
    [__("All Day Event"),      __("Check for events that last all day")]
]); ?>
</table>
<?= $this->Pretty->helpMeEnd(); ?>
