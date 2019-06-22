<div class="tasks form large-10 medium-9 columns">
    <?= $this->Form->create($task, ['data-toggle' => 'validator']) ?>
    <fieldset>
        <legend><?= __('Edit Task Item') ?><br /><span style="font-size:60%">Created At: <?= $task->created_at ?></span></legend>

        <?php
            echo $this->Form->input('show_id', ['readonly' => 'readonly', 'label' => __('Show'), 'options' => $shows]);
            echo $this->Form->input('assigned_to', ['readonly' => 'readonly', 'label' => __('Assign To'), 'options' => $assignee]);
            echo $this->Form->input('priority', ['label' => __('Priority'), 'options' => [0 => 'Missable', 1 => 'Normal', 2 => 'High', 3 => 'Critical' ]]);
            echo $this->Pretty->datePicker('due', __('Due / Event / Opening Date'), $task->due);
            echo $this->Form->input('category', ['label' => __('Task Category'), 'autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $cat]);
            echo $this->Form->input('title', ['label' => __('Task Title')]);
            echo $this->Form->input('note', ['label' => __('Description')]);
            if ( $adminTask ) { $ro = ['readonly' => 'readonly']; } else { $ro = []; }
            echo $this->Pretty->check('task_accepted', $task->task_accepted, [
                'label-width' => '100',
                'label-text' => __('Task Accepted'),
                'on-text' => __('YES'),
                'off-text' => __('NO'),
                'on-color' => 'success',
                'off-color' => 'danger'
            ], "normal", !$adminTask );
            echo $this->Pretty->check('task_done', $task->task_done, [
                'label-width' => '100',
                'label-text' => __('Task Complete'),
                'on-text' => __('YES'),
                'off-text' => __('NO'),
                'on-color' => 'success',
                'off-color' => 'danger'
            ], "normal", !$adminTask );
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit'), ['class' => 'btn-default']) ?>
    <?= $this->Form->end() ?>
</div>

<?= $this->Pretty->helpMeStart(__('Edit Task Item')); ?>
<p><?= __("This display allows you to add a new task item.") ?></p>

<table class="table table-condensed helptable">
<?= $this->Html->tableCells([
    [__("Show"),               __("locked to the current show.")],
    [__("Assign To"),          __("User who is responsible for carring out this task")],
    [__("Priority"),           __("Priority of the task.")],
    [__("Due"),                __("Due date of the task.")],
    [__("Task Category"),      __("A grouping category for this task.")],
    [__("Description"),        __("A description of the task.")],
    [__("Task Accepted"),      __("The task has been accepted by the administrator")],
    [__("Task Complete"),      __("The task has been completed")],
]); ?>
</table>

<?= $this->Pretty->helpMeEnd(); ?>


