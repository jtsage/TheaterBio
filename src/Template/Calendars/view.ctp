<h3>
    <?= h($show->name) ?>
    <div class="btn-group">
    <?php if ( $opsok ) { echo $this->Html->link(
        $this->Pretty->iconAdd($show->name . " " . __("Event Item")),
        ['action' => 'add', $show->id],
        ['escape' => false, 'class' => 'btn btn-outline-success btn-sm']
    ); } ?>
    <?php echo $this->Html->link(
            $this->Pretty->makeIcon(__("Today"), "refresh", __("Goto")),
            ['action' => 'view', $show->id, date('Y'), date('m')],
            ['escape' => false, 'class' => 'btn btn-outline-dark btn-sm']
        ); ?>
    </div>
</h3>

<div class="text-center"><h3>
    <?php
        echo "<div class='btn-group'>";
        echo $this->Html->link(
            $this->Pretty->makeIcon(__("Previous"), "fast-backward", __("Year")),
            ['action' => 'view', $show->id, $year-1, $month_num],
            ['escape' => false, 'class' => 'btn btn-outline-dark btn-sm']
        ); 
        echo $this->Html->link(
            $this->Pretty->makeIcon(__("Previous"), "step-backward", __("Month")),
            ['action' => 'view', $show->id, $prev[0], $prev[1]],
            ['escape' => false, 'class' => 'btn btn-outline-dark btn-sm']
        ); 
        echo "</div>";
        echo " " . $month . " " . $year . " ";
        echo "<div class='btn-group'>";
        echo $this->Html->link(
            $this->Pretty->makeIcon(__("Next"), "step-forward", __("Month")),
            ['action' => 'view', $show->id, $next[0], $next[1]],
            ['escape' => false, 'class' => 'btn btn-outline-dark btn-sm']
        ); 
        echo $this->Html->link(
            $this->Pretty->makeIcon(__("Next"), "fast-forward", __("Year")),
            ['action' => 'view', $show->id, $year+1, $month_num],
            ['escape' => false, 'class' => 'btn btn-outline-dark btn-sm']
        ); 
        echo "</div>";
    ?>
</h3></div>

<table id="bigcal" class="table table-bordered">
    <thead>
        <?= $this->Html->tableHeaders([
            [__("Sunday")    => ["class" => "text-center", "style" => "width:14.28%"]],
            [__("Monday")    => ["class" => "text-center", "style" => "width:14.28%"]],
            [__("Tuesday")   => ["class" => "text-center", "style" => "width:14.28%"]],
            [__("Wednesday") => ["class" => "text-center", "style" => "width:14.28%"]],
            [__("Thursday")  => ["class" => "text-center", "style" => "width:14.28%"]],
            [__("Friday")    => ["class" => "text-center", "style" => "width:14.28%"]],
            [__("Saturday")  => ["class" => "text-center", "style" => "width:14.28%"]],
        ]); ?>
    </thead>
    <tbody>

<?php 
    $colCount = 0;
    $currentDate = 1;
    $foundFirst = false;
    $foundLast = false;

    echo "<tr style='height: 110px;'>\n";
    while ( $colCount < 7 || !$foundLast ) {
        echo "  <td style='padding-left:0; padding-right:0;'>";
        if ( !$foundLast && ( $foundFirst || $colCount == $first_day_of_week) ) {
            $foundFirst = true;
            $extra = (( $today_is == $currentDate ) ? ["<span style='padding: .2em; border-radius: .5em; border:1px solid #aaa;'>", "</span>"] : ["", ""]);
            echo "<table class='table' style='table-layout:fixed; width:100%'>";
            echo $this->Html->tableCells([
                [
                    ['&nbsp', ['style' => 'border:0; width: 20%']],
                    ['&nbsp', ['style' => 'border:0; width: 60%']],
                    ['<strong>' . $extra[0] . $currentDate . $extra[1] . '</strong>', ['class' => 'text-center', 'style' => 'border:0']]
                ],
                [
                    ['&nbsp', ['colspan' => 3, 'style' => 'padding:0; border:0; font-size:40%']]
                ]
            ]);
            foreach ( $big_event[$currentDate] as $this_event ) {
                if ( $this_event['all_day'] ) {
                    echo $this->Html->tableCells([
                        [
                            [ 
                                "<a data-toggle=\"modal\" data-target=\"#event_". $this_event['id'] ."\">" . $this_event['title'] . "</a>",
                                [ 
                                    'style' => 'vertical-align:middle; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 12px; padding: .6em .2em;', 
                                    'class' => 'text-center', 
                                    'colspan' => 3
                                ]
                            ]
                        ]
                    ], ['style' => 'border-top: 1px #ccc solid; border-bottom: 1px #ccc solid;', 'class' => 'bg-' . $this_event['category']], null, false, false);
                } else {
                    echo $this->Html->tableCells([
                        [
                            [ $this_event['start_time']->i18nFormat("H:mm", 'UTC'), ['style' => 'padding: .6em .2em; vertical-align: middle; font-size: 11px', 'class' => 'text-center']],

                            ["<a data-toggle=\"modal\" data-target=\"#event_". $this_event['id'] ."\">" . $this_event['title'] . "</a>",
                               [ 'style' => 'vertical-align: middle; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 12px; padding: .6em .2em;']],

                            [ $this_event['end_time']->i18nFormat("H:mm", 'UTC'), ['style' => 'padding: .6em .2em; vertical-align: middle; font-size: 11px', 'class' => 'text-center']],
                        ]
                    ], ['style' => 'border-top: 1px #ccc solid; border-bottom: 1px #ccc solid;', 'class' => 'bg-' . $this_event['category']], null, false, false);
                }
            }
            echo "</table>";
            $currentDate++;
        } else { echo "&nbsp;"; }
        $colCount++;
        if ( $currentDate > $last_day_num ) { $foundLast = true; }
        echo "</td>\n";
        if ( $colCount == 7 && !$foundLast ) { echo "</tr>\n<tr style='height: 110px;'>\n"; $colCount = 0; }
    }
    echo "</tr>";
?>

    </tbody>
</table>

<?php foreach ( $big_event as $days_event ) : ?>
<?php foreach ( $days_event as $this_event ) : ?>
    <div class="modal fade" id="event_<?= $this_event['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-<?= $this_event['category'] ?>">
            <h5 class="modal-title" id="myModalLabel"><?= $this_event['title'] ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <table class="table">
                <tr><th colspan="2">Description</th></tr>
                <tr><td colspan="2" style="border-top:0;"><?= $this_event['note'] ?></td></tr>
                <tr><th>Category</th><td><?= ['default' => 'No Color', 'active' => 'Active Color (grey)', 'success' => 'Success Color (green)', 'info' => 'Info Color (lt. blue)', 'warning' => 'Warning Color (yellow)', 'danger' => 'Danger Color (red)' ][$this_event['category']] ?></td></tr>
                <?php if ( $this_event['all_day'] ) : ?>
                    <tr><th>All Day Event</th><td>YES</td></tr>
                <?php else: ?>
                    <tr><th>Start Time</th><td><?= $this_event['start_time']->i18nFormat([\IntlDateFormatter::NONE, \IntlDateFormatter::SHORT], 'UTC') ?></td></tr>
                    <tr><th>End Time</th><td><?= $this_event['end_time']->i18nFormat([\IntlDateFormatter::NONE, \IntlDateFormatter::SHORT], 'UTC') ?></td></tr>
                <?php endif; ?>
                <tr><th>Created at</th><td><?= $this_event['created_at']->i18nFormat([\IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT], 'UTC') ?></td></tr>
                <tr><th>Updated at</th><td><?= $this_event['updated_at']->i18nFormat([\IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT], 'UTC') ?></td></tr>
            </table>
          </div>
          <div class="modal-footer"><div class="btn-group">
            <a class="btn btn-outline-secondary btn-sm" href="/calendars/edit/<?= $this_event['id'] ?>"><?= $this->Pretty->iconEdit($this_event['title']) ?> Edit</a>
            <?= ( $opsok ? $this->Form->postLink(
                    $this->Pretty->iconDelete($this_event['title']) . 'Delete',
                    ['action' => 'delete', $this_event['id']],
                    ['escape' => false, 'confirm' => __('Are you sure you want to delete # {0}?', $this_event['id']), 'class' => 'btn btn-outline-danger btn-sm' ] 
                ) : "") ?>
            <button type="button" class="btn btn-sm btn-outline-dark" data-dismiss="modal">Close</button>
          </div></div>
        </div>
      </div>
    </div>

<?php endforeach; ?>
<?php endforeach; ?>
<?php if ( !is_null($show->sec_string) ) : ?>
    <p class="text-center">iCal Link: <a href="http://<?= $_SERVER['HTTP_HOST']?>/calendars/ics/<?= $show->id ?>/<?= $show->sec_string ?>"><?= $_SERVER['HTTP_HOST']?>/calendars/ics/<?= $show->id ?>/<?= $show->sec_string ?></a></p>
<?php endif; ?>

<?= $this->Pretty->helpMeStart(__('View Calendar')); ?>
<p><?= _("This display shows the calendar of the show you have selected.") ?></p>
<?= $this->Pretty->helpMeEnd(); ?>
