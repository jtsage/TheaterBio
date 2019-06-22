<h3><?= __("Scheduled Tasks"); ?>
    <div class="btn-group">
    <?php echo $this->Html->link(
        $this->Pretty->iconAdd(__("Scheduled Task")),
        ['action' => 'add'],
        ['escape' => false, 'class' => 'btn btn-outline-success btn-sm']
    ); ?>
    </div>
</h3>

<div class="schedules index large-9 medium-8 columns content">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('jobtype', __('Job Type')) ?></th>
                <th scope="col"><?= $this->Paginator->sort('sendto', __('Send-To E-Mail')) ?></th>
                <th scope="col"><?= $this->Paginator->sort('show_id', __('Show')) ?></th>
                <th scope="col"><?= $this->Paginator->sort('start_time') ?></th>
                <th scope="col"><?= $this->Paginator->sort('period') ?></th>
                <th scope="col"><?= $this->Paginator->sort('last_run') ?></th>
                <th scope="col">Next Run</th>
                <th scope="col" class="text-center actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($schedules as $schedule): ?>
            <tr>
                <td><?php
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
                ?></td>
                <td><?= h($schedule->sendto) ?></td>
                <td><?= $shows[$schedule->show_id] ?></td>
                <td><?= $schedule->start_time->i18nFormat("eee, MMM d, YYYY @ h:mm a", 'UTC') ?></td>
                <td><?= $this->Number->format($schedule->period) ?></td>
                <td><?= h($schedule->last_run) ?></td>

                <td><?= $this->Pretty->next_run($schedule->start_time, $schedule->last_run, $schedule->period); ?></td>
                <td class="text-center actions">
                    <div class='btn-group'>
                        <?= $this->Html->link(
                                $this->Pretty->iconView($schedule->id),
                                ['action' => 'view', $schedule->id],
                                ['escape' => false, 'class' => 'btn btn-default btn-sm' ]
                            );
                        ?>
                        <?= $this->Html->link(
                                $this->Pretty->iconEdit($schedule->id),
                                ['action' => 'edit', $schedule->id],
                                ['escape' => false, 'class' => 'btn btn-default btn-sm' ]
                            );
                        ?>
                        <?= $this->Form->postLink(
                                $this->Pretty->iconDelete($schedule->id),
                                ['action' => 'delete', $schedule->id],
                                ['escape' => false, 'confirm' => __('Are you sure you want to delete # {0}?', $schedule->id), 'class' => 'btn btn-danger btn-sm' ] 
                            );
                        ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>

<?= $this->Pretty->helpMeStart(__('Scheduled Tasks')); ?>
<p><?= __("This display allows you to view all scheduled tasks.") ?></p>

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
