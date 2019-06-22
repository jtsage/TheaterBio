<h3>
    <?= $show->name ?> Tasks
    <div class="btn-group">
        <?php echo $this->Html->link(
            $this->Pretty->iconAdd($show->name . " " . __("Task Item")),
            ['action' => 'add', $show->id],
            ['escape' => false, 'class' => 'btn btn-outline-success btn-sm']
        ); ?>
    </div>

</h3>

<div class="card w-75 my-3 mx-auto">
    <div class="card-body text-center">
        <span class="text-danger"><i class="fa fa-exclamation-circle"></i>&nbsp;:&nbsp;Danger,&nbsp;Action&nbsp;is&nbsp;overdue</span>&nbsp;&nbsp;&nbsp;
        <span class="text-warning"><i class="fa fa-exclamation-circle"></i>&nbsp;:&nbsp;Warning,&nbsp;Action&nbsp;required</span>&nbsp;&nbsp;&nbsp;
        <span class="text-default"><i class="fa fa-times-circle"></i>&nbsp;:&nbsp;Pending</span>&nbsp;&nbsp;&nbsp;
        <span class="text-success"><i class="fa fa-check-circle"></i>&nbsp;:&nbsp;Complete</span>
    </div>
</div>

<ol class="breadcrumb">
	<li class="breadcrumb-item"><strong>Sort By: </strong></li>
	<li class="breadcrumb-item"><a <?= ($sort == "due") ? 'class="text-success"' : '' ?> href="/tasks/view/<?= $show->id; ?>/due">Due Date</a></li>
	<li class="breadcrumb-item"><a <?= ($sort == "new") ? 'class="text-success"' : '' ?> href="/tasks/view/<?= $show->id; ?>/new">New Items</a></li>
	<li class="breadcrumb-item"><a <?= ($sort == "created") ? 'class="text-success"' : '' ?> href="/tasks/view/<?= $show->id; ?>/created">Created Date</a></li>
	<li class="breadcrumb-item"><a <?= ($sort == "updated") ? 'class="text-success"' : '' ?> href="/tasks/view/<?= $show->id; ?>/updated">Updated Date</a></li>
	<li class="breadcrumb-item"><a <?= ($sort == "priority") ? 'class="text-success"' : '' ?> href="/tasks/view/<?= $show->id; ?>/priority">Assigned Priority</a></li>
</ol>
<div id="tableTop">
<table class="my-0 table table-striped table-bordered">
        <thead>
            <?= $this->Html->tableHeaders([
                __("Title"),
                __("Category"),
                [__("Priority") => ['class' => 'text-center']],
                __("Due"),
                __("Accepted"),
                __("Complete"),
                [__('Actions') => ['class' => 'text-center']]
            ]); ?>
        </thead>
</table>
</div>
<div id="tableBod">
<table class="my-0 table table-striped table-bordered">


<?php foreach ($tasks as $task) {

    if ( $task->task_done ) { 
        $done_icon = "check-circle"; $done_clr = "success";
        $cept_icon = "check-circle"; $cept_clr = "success";
    } elseif ( $task->is_overdue ) { 
        $done_icon = "exclamation-circle"; $done_clr = "danger";
        if ( $task->task_accepted ) { 
            $cept_icon = "check-circle"; $cept_clr = "success";        
        } else {
            $cept_icon = "exclamation-circle"; $cept_clr = "danger";
        }
    } elseif ( ! $task->task_accepted ) { 
        $done_icon = "times-circle"; $done_clr = "default";
        $cept_icon = "exclamation-circle"; $cept_clr = "warning";
    } else { 
        $done_icon = "exclamation-circle"; $done_clr = "warning";
        $cept_icon = "check-circle"; $cept_clr = "success";
    }

    $pri_icon = "";
    for ( $i = 1; $i <= $task->priority; $i++ ) {
        $pri_icon .= '<i class="fa fa-bell" aria-hidden="true"></i>';
    }

    echo $this->Html->tableCells([
        [
            $task->title,
            $task->category,
            [
                $pri_icon . " " . ["Missable","Normal","High","Critical"][$task->priority],
                [ 'class' => 'text-center' ]
            ],
            $task->due->i18nFormat([\IntlDateFormatter::MEDIUM, \IntlDateFormatter::NONE], 'UTC'),
            [
                '<i class="fa fa-2x fa-' . $cept_icon . '" aria-hidden="true"></i>',
                ['class' => 'text-center text-' . $cept_clr, 'style' => 'width: 100px']
            ],
            [
                '<i class="fa fa-2x fa-' . $done_icon . '" aria-hidden="true"></i>',
                ['class' => 'text-center text-' . $done_clr, 'style' => 'width: 100px']
            ],
            [
                "<div class='btn-group'>" .
                 $this->Html->link(
                    $this->Pretty->iconView("Detail - " . $task->title),
                    ['action' => 'detail', $task->id],
                    ['escape' => false, 'class' => 'btn btn-outline-dark btn-sm' ] 
                ) .
                ( $opsok ? $this->Html->link(
                    $this->Pretty->iconEdit($task->title),
                    ['action' => 'edit', $task->id],
                    ['escape' => false, 'class' => 'btn btn-outline-secondary btn-sm' ] 
                ) : "") .
                ( $opsok ? $this->Form->postLink(
                    $this->Pretty->iconDelete($task->title),
                    ['action' => 'delete', $task->id],
                    ['escape' => false, 'confirm' => __('Are you sure you want to delete # {0}?', $task->id), 'class' => 'btn btn-outline-danger btn-sm' ] 
                ) : "") . 
                "</div>",
                ['class' => 'text-center']
            ]
        ]
    ]);

} ?>

</table>
</div>
<?= $this->Pretty->helpMeStart(__('View Task List by Show')); ?>
<p><?= __("This display allows you to view task items.") ?></p>

<table class="table table-condensed helptable">
<?= $this->Html->tableCells([
    [__("Title"),              __("Title of the task")],
    [__("Due"),                __("Due date of the task.  Defaults to today.")],
    [__("Created By"),         __("User who is responsible for creating this task")],
    [__("Assign To"),          __("User who is responsible for carring out this task")],
    [__("Priority"),           __("Priority of the task.")],
    [__("Category"),           __("A grouping category for this task.")],
    [__("Task Accepted"),      __("The task list administrator has seen and accepted this task")],
    [__("Task Completed"),     __("The task list administrator has marked this task completed")],
    [__("Created / Edited"),   __("The creation and last edit date for this task")],
    [__("Description"),        __("A description of the task.")],
]); ?>
</table>

<?= $this->Pretty->helpMeEnd(); ?>
