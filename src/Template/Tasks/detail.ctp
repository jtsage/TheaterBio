<h3>
    <?= $task->show_name ?> Task
    <div class="btn-group">
        <?php echo $this->Html->link(
            $this->Pretty->iconAdd($task->show_name . " " . __("Task Item")),
            ['action' => 'add', $task->show_id],
            ['escape' => false, 'class' => 'btn btn-outline-success btn-sm']
        ); ?>
    </div>
</h3>


<?php 
if ( $task->task_done ) { $panel_class = ["bg-success", "text-success", "border-success"]; }
elseif ( $task->is_overdue ) { $panel_class = ["bg-danger", "text-danger", "border-danger"]; }
elseif ( ! $task->task_accepted ) { $panel_class = ["bg-info", "text-info", "border-info"]; }
elseif ( $task->task_accepted ) { $panel_class = ["bg-warning", "text-warning", "border-warning"]; }
else { $panel_class = ["bg-danger", "text-danger", "border-danger"] ;}
?>

<div class="card <?= $panel_class[2] ?>">
	<div class="card-header <?= $panel_class[0] ?>">
		<div class="row">
			<div class="col-sm-3">
				<i class="fa fa-tasks fa-5x"></i>
			</div>
			<div class="col-sm-9 text-right">
				<div class="h1"><?php
    				for ( $i = 1; $i <= $task->priority; $i++ ) {
    					echo '<i class="fa fa-bell" aria-hidden="true"></i>';
    				} echo " " . $task->title ?></div>
				<div><?= __("{3}due on {0}{2}{1} with {0}{4}{1} priority", [
					"<strong>",
					"</strong>",
					$task->due->i18nFormat([\IntlDateFormatter::MEDIUM, \IntlDateFormatter::NONE], 'UTC'),
					(($task->is_overdue) ? "was " : "is "),
                    ["Missable","Normal","High","Critical"][$task->priority]
				]); ?></div>
			</div>
		</div>
	</div>
	<table class="table table-bordered">
    	<tr><th style="width: 30%">Created By</th><td><?= h($task->created_name) ?></td></tr>
    	<tr><th>Asssigned To</th><td><?= h($task->assigned_name) ?></td></tr>
    	<tr><th>Category</th><td><?= h($task->category) ?></td></tr>
        <?php if ( ! $task->task_done ) : ?>
    	<tr><th>Task Accepted</th><td class="<?= ($task->task_accepted) ? "success" : "warning" ?>"><?= ($task->task_accepted) ? "yes" : "no" ?></td></tr>
        <?php endif; ?>
    	<tr><th>Task Complete</th><td class="<?= ($task->task_done) ? "success" : ($task->is_overdue ? "danger" : "warning") ?>"><?= ($task->task_done) ? "YES" : "no" ?></td></tr>
        
        <?php if ( $opsok || $task->created_by == $opid ) : ?>
    	<tr><th>Created / Edited</th><td><?= h($task->created_at) ?> &nbsp;/ &nbsp;<?= h($task->updated_at) ?></td></tr>
        <?php endif; ?>
    	<tr><th colspan="2">Description</th></tr>
  	</table>
	<div class="card-body">
		<?= $this->Text->autoParagraph(h($task->note)); ?>
	</div>

    <?php if ( $opsok || $task->created_by == $opid ) : ?>
	<a href="/tasks/edit/<?= $task->id; ?>">
		<div class="card-footer">
			<span class="pull-left"><?= __('Edit Task Item'); ?></span>
			<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
			<div class="clearfix"></div>
		</div>
	</a>
    <?php endif; ?>
    <?php if ( $opsok ) {
    echo $this->Form->postLink(
        '<div class="card-footer"><span class="pull-left">' . __('Delete Task Item'). '</span><span class="pull-right"><i class="fa fa-trash"></i></span><div class="clearfix" ></div></div>',
        ['action' => 'delete', $task->id],
        ['escape' => false, 'confirm' => __('Are you sure you want to delete {0}?', $task->title) ] );
    } ?>
   
</div>


<?= $this->Pretty->helpMeStart(__('View Task Item Detail')); ?>
<p><?= __("This display allows you to view task item detail.") ?></p>

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
