<h3><?= __("Calendars"); ?></h3>

<div class="row">
<?php $rowcount = 0; ?>

<?php foreach ($shows as $show): ?>
<?php if ( $rowcount == 2 ) { echo "</div><div class='row'>"; $rowcount=0; } $rowcount++; ?>
<div class="col-md-6">
    <div class="card border-success mb-3">
        <div class="card-body bg-success">
            <div class="row">
                <div class="col-sm-3">
                    <i class="fa fa-calendar fa-5x"></i>
                </div>
                <div class="col-sm-9 text-right">
                    <div class="h1"><?= $show->name ?></div>
                    <div><?= __("taking place at {0}{2}{1} and ending on {0}{3}{1}.", [
                        "<strong>",
                        "</strong>",
                        $show->location,
                        $show->end_date->i18nFormat([\IntlDateFormatter::MEDIUM, \IntlDateFormatter::NONE], 'UTC')
                    ]); ?></div>
                </div>
            </div>
        </div>
        <table class="table table-bordered mb-0">
            <tr><th>Today's Events</th><td style="text-align: center"><?= $showcal['today'][$show->id] ?></td></tr>
            <tr><th>Future Events</th><td style="text-align: center"><?= $showcal['future'][$show->id] ?></td></tr>
            <tr><th>Past Events</th><td style="text-align: center"><?= $showcal['past'][$show->id] ?></td></tr>
        </table>
        <a href="/calendars/add/<?= $show->id; ?>">
            <div class="card-footer">
                <span class="pull-left"><?= __('Add Event'); ?></span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
            </div>
        </a>
        <a href="/calendars/view/<?= $show->id; ?>/<?= date('Y') ?>/<?= date('m')?>">
            <div class="card-footer">
                <span class="pull-left"><?= __('View Calendar'); ?></span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
            </div>
        </a>
    </div>
</div>
<?php endforeach; ?>
</div>

<?php
    // Admin Only, show inactive //
    if ( isset($inactshows) && !empty($inactshows)) { 
        echo "<h3>" . __("Closed Calendars") . "</h3>";
    }
?>

<div class="row">
<?php $rowcount = 0; ?>

<?php foreach ($inactshows as $show): ?>
<?php if ( $rowcount == 2 ) { echo "</div><div class='row'>"; $rowcount=0; } $rowcount++; ?>
<div class="col-md-6">
    <div class="card border-success mb-3">
        <div class="card-body bg-success">
            <div class="row">
                <div class="col-sm-3">
                    <i class="fa fa-calendar fa-5x"></i>
                </div>
                <div class="col-sm-9 text-right">
                    <div class="h1"><?= $show->name ?></div>
                    <div><?= __("taking place at {0}{2}{1} and ending on {0}{3}{1}.", [
                        "<strong>",
                        "</strong>",
                        $show->location,
                        $show->end_date->i18nFormat([\IntlDateFormatter::MEDIUM, \IntlDateFormatter::NONE], 'UTC')
                    ]); ?></div>
                </div>
            </div>
        </div>
        <table class="table table-bordered mb-0">
            <tr><th>Today's Events</th><td style="text-align: center"><?= $showcal['today'][$show->id] ?></td></tr>
            <tr><th>Future Events</th><td style="text-align: center"><?= $showcal['future'][$show->id] ?></td></tr>
            <tr><th>Past Events</th><td style="text-align: center"><?= $showcal['past'][$show->id] ?></td></tr>
        </table>
        <a href="/calendars/add/<?= $show->id; ?>">
            <div class="card-footer">
                <span class="pull-left"><?= __('Add Event'); ?></span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
            </div>
        </a>
        <a href="/calendars/view/<?= $show->id; ?>/<?= date('Y') ?>/<?= date('m')?>">
            <div class="card-footer">
                <span class="pull-left"><?= __('View Calendar'); ?></span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
            </div>
        </a>
    </div>
</div>
<?php endforeach; ?>
</div>

<?= $this->Pretty->helpMeStart(__('View Calendars')); ?>
<p><?= _("This display shows the calendars of the shows you have access to.") ?></p>
<p><?= _("Additionally, if you are a system administrator, you can view the calendars from closed (inactive) shows.") ?></p>
<?= $this->Pretty->helpMeEnd(); ?>