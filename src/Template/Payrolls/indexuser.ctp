
<h3><?= __("Payroll By User"); ?></h3>

<div class="row">
<?php $rowcount = 0; ?>
<?php foreach ( $ulist as $id => $name ): ?>
<?php if ( $rowcount == 3 ) { echo "</div><div class='row'>"; $rowcount=0; } $rowcount++; ?>
<div class="col-md-4">
    <?php
        $worked_paid = 0;
        $worked_owed = 0;

        foreach ( $buddy as $pitem ) {
            if ( $pitem->user_id == $id ) {
                if ( $pitem->is_paid ) { 
                    $worked_paid = $pitem->totalwork;
                } else {
                    $worked_owed = $pitem->totalwork;
                }
            }
        }
        $class = (( $worked_owed  > 0 ) ? "warning" : (( $worked_paid > 0 ) ? "success" : "primary" ));
    ?>
    <div class="card border-<?= $class; ?>">
        <div class="card-body bg-<?= $class; ?>">
            <div class="row">
                <div class="col-sm-3">
                    <i class="fa fa-line-chart fa-5x"></i>
                </div>
                <div class="col-sm-9 text-right">
                    <div class="h1"><?= $name ?></div>
                    <div><?= __("with {0}{2}{1} outstanding hours, and {0}{3}{1} paid hours", [
                            "<strong>",
                            "</strong>",
                            number_format($worked_owed, 2),
                            number_format($worked_paid, 2)
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
        <a href="/payrolls/addtouser/<?= $id; ?>">
            <div class="card-footer">
                <span class="pull-left"><?= __('Add Payroll Item'); ?></span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
            </div>
        </a>
        <a href="/payrolls/viewbyuser/<?= $id; ?>">
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

<?= $this->Pretty->helpMeStart(__('View Payroll by User')); ?>
<p><?= __("This display shows the payroll reports of all active users, in shows you administer.  This display shows all active payroll users for system administrators.") ?></p>
<?= $this->Pretty->helpMeEnd(); ?>
