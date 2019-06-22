<div class="users view large-10 medium-9 columns">
    <h3>Scheduled Task #<?= $schedule->id ?>
    <div class='btn-group'>
    <?= $this->Html->link(
        $this->Pretty->iconEdit($schedule->id),
        ['action' => 'edit', $schedule->id],
        ['escape' => false, 'class' => 'btn btn-default btn-sm']
    ) ?>
    <?= $this->Form->postLink(
        $this->Pretty->iconDelete($schedule->id),
        ['action' => 'delete', $schedule->id],
        ['escape' => false, 'confirm' => __('Are you sure you want to delete # {0}?', $schedule->id), 'class' => 'btn btn-danger btn-sm' ] 
    ); ?>
    </div>
    </h3>
    <div class="row">
        <div class="col-md-6">
            <h4><span class="label label-primary"><?= __('Job Type') ?></span></h4>
            <p><?php
                switch($schedule->jobtype) {
                    case "remind":
                        echo "Send Hour Reminders"; break;
                    case "unpaid":
                        echo "Send Un-Paid Report"; break;
                    case 'budget':
                        echo 'Send Budget Report'; break;
                    case 'tasks':
                        echo 'Send Task List'; break;
                    case 'today':
                        echo 'Send Today\'s Calendar Events'; break;
                }
            ?></p>
            <h4><span class="label label-primary"><?= __('E-Mail To') ?></span></h4>
            <p><?= h($schedule->sendto) ?></p>
            <h4><span class="label label-info"><?= __('First Valid Date/Time') ?></span></h4>
            <p><?= $schedule->start_time->i18nFormat(null, 'UTC') ?></p>
            <h4><span class="label label-info"><?= __('Period') ?></span></h4>
            <p><?= h($schedule->period) ?></p>
        </div>
        <div class="col-md-6">
            <h4><span class="label label-warning"><?= __('Last Successful Run At') ?></span></h4>
            <p><?= $schedule->last_run->i18nFormat(null, 'UTC') ?></p>
            <h4><span class="label label-warning"><?= __('Next Scheduled Run At') ?></span></h4>
            <p><?= $this->Pretty->next_run($schedule->start_time, $schedule->last_run, $schedule->period); ?></p>
            <h4><span class="label label-warning"><?= __('Task Created At') ?></span></h4>
            <p><?= $schedule->created_at->i18nFormat(null, 'UTC') ?></p>
            <h4><span class="label label-warning"><?= __('Last Update At') ?></span></h4>
            <p><?= $schedule->updated_at->i18nFormat(null, 'UTC') ?></p>
        </div>
    </div>
</div>

<?= $this->Pretty->helpMeStart(__('Scheduled Tasks')); ?>
<p><?= __("This display allows you to view detail of a scheduled task.") ?></p>

<table class="table table-condensed helptable">
<?= $this->Html->tableCells([
    [__("Job Type"),               __("The job type")],
    [__("Send-To Email"),          __("The e-mail address to send the report to")],
    [__("First Valid Date/Time"),  __("First valid date & time to send the report")],
    [__("Period"),                 __("How many days to repeat this task in. (Default 7, one week)")],
    [__("Last Run"),               __("Last Successful run of the task")],
    [__("Next Run"),               __("Next run of the tast (approximate, due to possible cron drift.  See readme.")],
]); ?>
</table>

<?= $this->Pretty->helpMeEnd(); ?>
