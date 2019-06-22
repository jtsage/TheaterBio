<?php if ( count($messagesWaiting) > 0 ) : ?>
<div class="card mb-4 panel-default">
    <div class="card-body">
        <h4>You have <?= sizeof($messagesWaiting) ?> message(s) waiting.
        <?php
            echo $this->Form->postLink(
                 $this->Pretty->iconDelete("Clear All Messages"),
                 ['controller' => 'messages', 'action' => 'clear', $user->id],
                 ['escape' => false, 'confirm' => __('Are you sure you want to clear all your messages?'), 'class' => 'btn btn-outline-danger btn-sm btn-inline']
            );
        ?></h4>
    </div>
    <table class="table table-bordered">
        <?php foreach ($messagesWaiting as $message) : ?>
            <tr><td><?= $message['note'] ?> <small>on <?= $message['created_at']->i18nFormat([\IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT], 'UTC') ?></small></td><td style="text-align:center"><?php
                echo $this->Form->postLink(
                 $this->Pretty->iconMark("Delete Message #" . $message['id']),
                 ['controller' => 'messages', 'action' => 'delete', $message['id']],
                 ['escape' => false, 'confirm' => __('Are you sure you want to delete this message?'), 'class' => 'btn btn-outline-warning btn-sm']
            );
            ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php endif; ?>

<?php if ($WhoAmI): ?>
<div class="row">
<div class="col-lg-8">
<?php endif; ?>

<div class="row">
    <?php if ( $tasksAdm->count() > 0 || $tasksUser->count() > 0 || $calUser->count() > 0 ) : ?>
    <div class="col-md-6">
        <div class="card mb-4 border-info">
            <div class="card-body bg-info">
                <div class="row">
                    <div class="col-sm-3">
                        <i class="fa fa-calendar fa-5x"></i>
                    </div>
                    <div class="col-sm-9 text-right">
                        <div class="h1"><?= __("Tasks &amp; Calendars") ?></div>
                        <div><?= __("A listing of active system shows that you have task list or calendar access to.") ?></div>
                    </div>
                </div>
            </div>

            <a class="text-info" href="/tasks/">
                <div class="card-footer"><strong>
                    <span class="pull-left">View Task Lists</span>
                    <span class="pull-right"><i class="fa fa-lg fa-arrow-right"></i></span>
                    <div class="clearfix"></div>
                </strong></div>
            </a>

            <a class="text-info" href="/calendars/">
                <div class="card-footer"><strong>
                    <span class="pull-left">View Calendars</span>
                    <span class="pull-right"><i class="fa fa-lg fa-arrow-right"></i></span>
                    <div class="clearfix"></div>
                </strong></div>
            </a>

            <div class="card-footer text-center">
                <strong><?= __("Your Task Administrated Shows<br />(Overdue / New / Pending / Total) "); ?></strong>
            </div>

            <?php foreach ( $tasksAdm as $item ): ?>
            <a class="text-info" href="/tasks/view/<?= $item->id ?>">
                <div class="card-footer">
                    <span class="pull-left"><?= $item->name ?></span>
                    <span class="pull-right">
                        <span class="badge"><?= $showtask['overdue'][$item->id] ?> / <?= $showtask['new'][$item->id] ?> / <?= $showtask['accept_notdone'][$item->id] ?> / <?= $showtask['total'][$item->id] ?></span> 
                        <i class="fa fa-lg fa-arrow-right"></i>
                    </span>
                    <div class="clearfix"></div>
                </div>
            </a>
            <?php endforeach; ?>

            <div class="card-footer text-center">
                <strong><?= __("Your Task Shows (Your Created Tasks)"); ?></strong>
            </div>

            <?php foreach ( $tasksUser as $item ): ?>
            <a class="text-info" href="/tasks/view/<?= $item->id ?>">
                <div class="card-footer">
                    <span class="pull-left"><?= $item->name ?></span>
                    <span class="pull-right">
                        <span class="badge"><?= $showtask['yours'][$item->id] ?></span> 
                        <i class="fa fa-lg fa-arrow-right"></i>
                    </span>
                    <div class="clearfix"></div>
                </div>
            </a>
            <?php endforeach; ?>

            <div class="card-footer text-center">
                <strong><?= __("Your Calendars (Events Today)"); ?></strong>
            </div>

            <?php foreach ( $calUser as $item ): ?>
            <a class="text-info" href="/calendars/view/<?= $item->id ?>">
                <div class="card-footer">
                    <span class="pull-left"><?= $item->name ?></span>
                    <span class="pull-right">
                        <span class="badge"><?= $showcal['today'][$item->id] ?></span> 
                        <i class="fa fa-lg fa-arrow-right"></i>
                    </span>
                    <div class="clearfix"></div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if ( $budgetAdmin->count() > 0 ) : ?>
    <div class="col-md-6">
        <div class="card mb-4 border-success">
            <div class="card-body bg-success">
                <div class="row">
                    <div class="col-sm-3">
                        <i class="fa fa-bar-chart fa-5x"></i>
                    </div>
                    <div class="col-sm-9 text-right">
                        <div class="h1"><?= __("Budget") ?></div>
                        <div><?= __("A listing of active system shows that you have budget access to.") ?></div>
                    </div>
                </div>
            </div>

            <a class="text-success" href="/budgets/">
                <div class="card-footer"><strong>
                    <span class="pull-left">View Show List</span>
                    <span class="pull-right"><i class="fa fa-lg fa-arrow-right"></i></span>
                    <div class="clearfix"></div>
                </strong></div>
            </a>

            <div class="card-footer text-center">
                <strong><?= __("Your Shows"); ?></strong>
            </div>

            <?php foreach ( $budgetAdmin as $item ): ?>
            <a class="text-success" href="/budgets/view/<?= $item->show_id ?>">
                <div class="card-footer">
                    <span class="pull-left"><?= $item->showName ?></span>
                    <span class="pull-right">
                        <span class="badge"><?= $this->Number->currency($item->priceTotal); ?></span> 
                        <i class="fa fa-lg fa-arrow-right"></i>
                    </span>
                    <div class="clearfix"></div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
<!-- </div>
<div class="row"> -->
    <?php if ( $payrollSelfShows->count() > 0 || $payrollAdmShows->count() > 0 ) : ?>
    <div class="col-md-6">
        <div class="card mb-4 border-primary">
            <div class="card-body bg-primary">
                <div class="row">
                    <div class="col-sm-3">
                        <i class="fa fa-line-chart fa-5x"></i>
                    </div>
                    <div class="col-sm-9 text-right">
                        <div class="h1"><?= __("Payroll Shows") ?></div>
                        <div><?= __("A listing of active system shows that you have payroll access to.") ?></div>
                    </div>
                </div>
            </div>

            <a class="text-primary" href="/payrolls/">
                <div class="card-footer"><strong>
                    <span class="pull-left"><?= __("View Show List") ?></span>
                    <span class="pull-right"><i class="fa fa-lg fa-arrow-right"></i></span>
                    <div class="clearfix"></div>
                </strong></div>
            </a>

            <div class="card-footer text-center">
                <strong><?= __("Your Paid Shows"); ?></strong>
            </div>

            <?php foreach ( $payrollSelfShows as $item ): ?>
            <a class="text-primary" href="/payrolls/viewbyshow/<?= $item->show_id ?>">
                <div class="card-footer">
                    <span class="pull-left"><?= $item->showName ?></span>
                    <span class="pull-right" title="Outstanding Hours">
                        <span class="badge"><?= number_format($item->workTotal,2 ); ?></span> 
                        <i class="fa fa-lg fa-arrow-right"></i>
                    </span>
                    <div class="clearfix"></div>
                </div>
            </a>
            <?php endforeach; ?>

            <div class="card-footer text-center">
                <strong><?= __("Your Administered Shows"); ?></strong>
            </div>

            <?php foreach ( $payrollAdmShows as $item ): ?>
            <a class="text-primary" href="/payrolls/viewbyshow/<?= $item->show_id ?>">
                <div class="card-footer">
                    <span class="pull-left"><?= $item->showName ?></span>
                    <span class="pull-right" title="Outstanding Hours">
                        <span class="badge"><?= number_format($item->workTotal,2 ); ?></span> 
                        <i class="fa fa-lg fa-arrow-right"></i>
                    </span>
                    <div class="clearfix"></div>
                </div>
            </a>
            <?php endforeach; ?>  
        </div>
    </div>
    <?php endif; ?>

    <?php if ( $payrollAdmUsers->count() > 0 ) : ?>
    <div class="col-md-6">
        <div class="card mb-4 border-primary">
            <div class="card-body bg-primary">
                <div class="row">
                    <div class="col-sm-3">
                        <i class="fa fa-line-chart fa-5x"></i>
                    </div>
                    <div class="col-sm-9 text-right">
                        <div class="h1"><?= __("Payroll Users") ?></div>
                        <div><?= __("A listing of active system users that you have payroll access to.") ?></div>
                    </div>
                </div>
            </div>

            <a class="text-primary" href="/payrolls/indexuser">
                <div class="card-footer"><strong>
                    <span class="pull-left"><?= __("View User List") ?></span>
                    <span class="pull-right"><i class="fa fa-lg fa-arrow-right"></i></span>
                    <div class="clearfix"></div>
                </strong></div>
            </a>
            <a class="text-primary" href="/payrolls/viewbyuser/<?= $user->id ?>">
                <div class="card-footer"><strong>
                    <span class="pull-left"><?= __("View Yourself") ?></span>
                    <span class="pull-right"><i class="fa fa-lg fa-arrow-right"></i></span>
                    <div class="clearfix"></div>
                </strong></div>
            </a>

            <div class="card-footer text-center">
                <strong><?= __("Your Administered Users"); ?></strong>
            </div>

            <?php foreach ( $payrollAdmUsers as $item ): ?>
            <?php if ( $item->user_id > 0 ): ?>
            <a class="text-primary" href="/payrolls/viewbyuser/<?= $item->user_id ?>">
                <div class="card-footer">
                    <span class="pull-left"><?= $item->fullName ?></span>
                    <span class="pull-right" title="Outstanding Hours">
                        <span class="badge"><?= number_format($item->workTotal,2 ); ?></span> 
                        <i class="fa fa-lg fa-arrow-right"></i>
                    </span>
                    <div class="clearfix"></div>
                </div>
            </a>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    


</div>

<?php if ($WhoAmI): ?>
</div>
<div class="col-lg-4">

        <div class="card mb-4 border-warning">
            <div class="card-body bg-warning">
                <div class="row">
                    <div class="col-sm-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-sm-9 text-right">
                        <div class="h1"><?= __("Users") ?></div>
                        <div><?= __("The system wide user list.") ?></div>
                    </div>
                </div>
            </div>

            <a class="text-warning" href="/users/">
                <div class="card-footer"><strong>
                    <span class="pull-left">View User List</span>
                    <span class="pull-right">
                        <span class="badge"><?= $usercnt; ?></span> 
                        <i class="fa fa-lg fa-arrow-right"></i>
                    </span>
                    <div class="clearfix"></div>
                </strong></div>
            </a>

            <a class="text-warning" href="/users/add/">
                <div class="card-footer">
                    <span class="pull-left">Add User</span>
                    <span class="pull-right"><i class="fa fa-lg fa-arrow-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>

        <div class="card mb-4 border-warning">
            <div class="card-body bg-warning">
                <div class="row">
                    <div class="col-sm-3">
                        <i class="fa fa-file fa-5x"></i>
                    </div>
                    <div class="col-sm-9 text-right">
                        <div class="h1"><?= __("Stored Files") ?></div>
                        <div><?= __("The system wide stored files.") ?></div>
                    </div>
                </div>
            </div>

            <a class="text-warning" href="/files/">
                <div class="card-footer"><strong>
                    <span class="pull-left">View Files</span>
                    <span class="pull-right">
                        <span class="badge"><?= $files; ?></span> 
                        <i class="fa fa-lg fa-arrow-right"></i>
                    </span>
                    <div class="clearfix"></div>
                </strong></div>
            </a>

            <a class="text-warning" href="/files/add/">
                <div class="card-footer">
                    <span class="pull-left">Add File</span>
                    <span class="pull-right"><i class="fa fa-lg fa-arrow-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>

        <div class="card mb-4 border-warning">
            <div class="card-body bg-warning">
                <div class="row">
                    <div class="col-sm-3">
                        <i class="fa fa-clock-o fa-5x"></i>
                    </div>
                    <div class="col-sm-9 text-right">
                        <div class="h1"><?= __("Scheduled Tasks") ?></div>
                        <div><?= __("The system wide scheduled tasks list.") ?></div>
                    </div>
                </div>
            </div>

            <a class="text-warning" href="/schedules/">
                <div class="card-footer"><strong>
                    <span class="pull-left">View Scheduled Tasks</span>
                    <span class="pull-right">
                        <span class="badge"><?= $schedules; ?></span> 
                        <i class="fa fa-lg fa-arrow-right"></i>
                    </span>
                    <div class="clearfix"></div>
                </strong></div>
            </a>

            <a class="text-warning" href="/schedules/add/">
                <div class="card-footer">
                    <span class="pull-left">Add Scheduled Task</span>
                    <span class="pull-right"><i class="fa fa-lg fa-arrow-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>


        <div class="card mb-4 border-warning">
            <div class="card-body bg-warning">
                <div class="row">
                    <div class="col-sm-3">
                        <i class="fa fa-music fa-5x"></i>
                    </div>
                    <div class="col-sm-9 text-right">
                        <div class="h1"><?= __("Shows") ?></div>
                        <div><?= __("The system wide list of active shows, with an overview of permissions for each.") ?></div>
                    </div>
                </div>
            </div>

            <a class="text-warning" href="/shows/">
                <div class="card-footer"><strong>
                    <span class="pull-left">View Show List</span>
                    <span class="pull-right">
                        <span class="badge"><?= $showcnt; ?></span> 
                        <i class="fa fa-lg fa-arrow-right"></i>
                    </span>
                    <div class="clearfix"></div>
                </strong></div>
            </a>

            <a class="text-warning" href="/shows/add/">
                <div class="card-footer">
                    <span class="pull-left">Add Show</span>
                    <span class="pull-right"><i class="fa fa-lg fa-arrow-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>

            <div class="card-footer text-center">
                <strong><?= __("Open Shows (Budget/Admin/Paid/TaskUsr/TaskAdm/Cal)"); ?></strong>
            </div>

            <?php foreach ( $shows as $item ): ?>
            <a class="text-warning" href="/shows/editperm/<?= $item->id ?>">
                <div class="card-footer">
                    <span class="pull-left"><?= $item->name ?></span>
                    <span class="pull-right">
                        <span class="badge">
                            <?= 
                                $item->show_user_perms[0]->budgTotal . "/" .
                                $item->show_user_perms[0]->admnTotal . "/" .
                                $item->show_user_perms[0]->paidTotal . "/" .
                                $item->show_user_perms[0]->taskTotal . "/" .
                                $item->show_user_perms[0]->tadmTotal . "/" .
                                $item->show_user_perms[0]->calTotal
                            ?>
                        </span> 
                        <i class="fa fa-lg fa-arrow-right"></i>
                    </span>
                    <div class="clearfix"></div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>

</div>
</div>
<?php endif; ?>

<?= $this->Pretty->helpMeStart(__('Dashboard')); ?>
<p><?= __("This display shows a quick dashboard of your available tasks.") ?></p>
<?= $this->Pretty->helpMeEnd(); ?>
