<div class="tasks form large-10 medium-9 columns">
    <?= $this->Form->create($task, ['data-toggle' => 'validator']) ?>
    <fieldset>
        <legend><?= __('Add Task Item') ?></legend>
        <?php
            echo $this->Form->input('show_id', ['label' => __('Show'), 'options' => $shows]);
            echo $this->Form->input('assigned_to', ['label' => __('Assign To'), 'options' => $assignee]);
            echo $this->Form->input('priority', ['label' => __('Priority'), 'options' => [0 => 'Missable', 1 => 'Normal', 2 => 'High', 3 => 'Critical' ]]);
            echo $this->Pretty->datePicker('due', __('Due / Event / Opening Date'));
            echo $this->Form->input('category', ['label' => __('Task Category'), 'autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $cat]);
            echo $this->Form->input('title', ['label' => __('Task Title')]);
            echo $this->Form->input('note', ['label' => __('Description')]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit'), ['class' => 'btn-default']) ?>
    <?= $this->Form->end() ?>
</div>

<?= $this->Pretty->helpMeStart(__('Add Task Item')); ?>
<p><?= __("This display allows you to add a new task item.") ?></p>

<table class="table table-condensed helptable">
<?= $this->Html->tableCells([
    [__("Show"),               __("locked to the current show.")],
    [__("Assign To"),          __("User who is responsible for carring out this task")],
    [__("Priority"),           __("Priority of the task.")],
    [__("Due"),                __("Due date of the task.  Defaults to today.")],
    [__("Task Category"),      __("A grouping category for this task.")],
    [__("Task Title"),         __("A brief title for the task")],
    [__("Description"),        __("A description of the task.")],
]); ?>
</table>

<?= $this->Pretty->helpMeEnd(); ?>