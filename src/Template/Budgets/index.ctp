<h3><?= __("Show Budgets"); ?></h3>

<div class="row">
<?php $rowcount = 0; ?>

<?php foreach ($shows as $show): ?>
<?php if ( $rowcount == 2 ) { echo "</div><div class='row'>"; $rowcount=0; } $rowcount++; ?>
<div class="col-md-6">
    <?php
        $total = 0;
        foreach ( $budget as $budgetitem ) {
            if ( $budgetitem->show_id == $show->id ) {
                $total += $budgetitem->total;
            }
        }
    ?>
    <div class="card border-primary mb-5">
        <div class="card-body bg-primary">
            <div class="row">
                <div class="col-sm-3">
                    <i class="fa fa-bar-chart fa-5x"></i>
                </div>
                <div class="col-sm-9 text-right">
                    <div class="h1"><?= $show->name ?></div>
                    <div><?= __("taking place at {0}{2}{1} and ending on {0}{3}{1}, with a current total expenditure of {0}{4}{1}", [
                        "<strong>",
                        "</strong>",
                        $show->location,
                        $show->end_date->i18nFormat([\IntlDateFormatter::MEDIUM, \IntlDateFormatter::NONE], 'UTC'),
                        $this->Number->currency($total)
                    ]); ?></div>
                </div>
            </div>
        </div>
        <a href="/budgets/add/<?= $show->id; ?>">
            <div class="card-footer">
                <span class="pull-left"><?= __('Add Budget Item'); ?></span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
            </div>
        </a>
        <a href="/budgets/view/<?= $show->id; ?>">
            <div class="card-footer">
                <span class="pull-left"><?= __('View Details'); ?></span>
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
        echo "<h3>" . __("Closed Show Budgets") . "</h3>";
    }
?>

<div class="row">
<?php $rowcount = 0; ?>

<?php foreach ($inactshows as $show): ?>
<?php if ( $rowcount == 2 ) { echo "</div><div class='row'>"; $rowcount=0; } $rowcount++; ?>
<div class="col-md-6">
    <?php
        $total = 0;
        foreach ( $budget as $budgetitem ) {
            if ( $budgetitem->show_id == $show->id ) {
                $total += $budgetitem->total;
            }
        }
    ?>
    <div class="card border-danger mb-5">
        <div class="card-body bg-danger">
            <div class="row">
                <div class="col-sm-3">
                    <i class="fa fa-bar-chart fa-5x"></i>
                </div>
                <div class="col-sm-9 text-right">
                    <div class="h1"><?= $show->name ?></div>
                    <div><?= __("taking place at {0}{2}{1}, ended on {0}{3}{1}, with a total expenditure of {0}{4}{1}", [
                        "<strong>",
                        "</strong>",
                        $show->location,
                        $show->end_date->i18nFormat([\IntlDateFormatter::MEDIUM, \IntlDateFormatter::NONE], 'UTC'),
                        $this->Number->currency($total)
                    ]); ?></div>
                </div>
            </div>
        </div>
        <a href="/budgets/view/<?= $show->id; ?>">
            <div class="card-footer">
                <span class="pull-left"><?= __('View Details'); ?></span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
            </div>
        </a>
    </div>
</div>
<?php endforeach; ?>
</div>


<?= $this->Pretty->helpMeStart(__('View Show Budgets')); ?>
<p><?= _("This display shows the budgets of the shows you have access, along with the current expenditure broken down by budget category.") ?></p>
<p><?= _("For each show, you have the option of viewing a detailed budget report, or adding a budget item to the report") ?></p>
<p><?= _("Additionally, if you are a system administrator, you can view the budgets from closed (inactive) shows.") ?></p>
<?= $this->Pretty->helpMeEnd(); ?>