<div class="shows view large-10 medium-9 columns">
    <h3>
        <?php 
            switch ( $viewMode ) {
                case "show":
                case "showdate":
                    echo h($show->name) . __(" Payroll Expenditure");
                    $helpTitle = __("View Show Payroll Expenditure");
                    $returnTo = 0;
                    break;
                case "user":
                    echo h($user->first) . " " . h($user->last) . __("'s Payroll Expenditure");
                    $helpTitle = __("View User Payroll Expenditure");
                    $returnTo = 1;
                    break;
                case "unpaidshow":
                    echo __("Unpaid Payroll Expenditures by Show");
                    $helpTitle = __("View Unpaid Payroll Report by Show");
                    $returnTo = 2;
                    break;
                case "unpaiduser":
                    echo __("Unpaid Payroll Expenditures by User");
                    $helpTitle = __('View Unpaid Payroll Report by User');
                    $returnTo = 3;
                    break;
            }
        ?>
        <div class='btn-group'>
        <?php 
            switch ( $viewMode ) {
                case "show":
                    if ( $adminView ) { 
                        echo $this->Html->link(
                            $this->Pretty->iconAdd($show->name . " " . __("Payroll Item")),
                            ['action' => 'addtoshow', $show->id],
                            ['escape' => false, 'class' => 'btn btn-outline-success btn-sm d-print-none']
                        );
                        echo $this->Form->postLink(
                            $this->Pretty->iconMark($show->name),
                            ['action' => 'markshowpaid', $show->id],
                            ['escape' => false, 'confirm' => __('Are you sure you want to mark ALL paid for {0}?', $show->name),  'class' => 'btn btn-outline-warning btn-sm d-print-none']
                        );
                    } else {
                        echo $this->Html->link(
                            $this->Pretty->iconAdd($show->name . " " . __("Payroll Item")),
                            ['action' => 'addtoself', $show->id],
                            ['escape' => false, 'class' => 'btn btn-outline-success btn-sm d-print-none']
                        );
                    }
                    echo $this->Html->link(
                        $this->Pretty->iconDL($show->name . " " . __("Payroll Item")),
                        ['action' => 'viewbyshow', $show->id, 'csv'],
                        ['escape' => false, 'class' => 'btn btn-outline-dark btn-sm d-print-none']
                    );
                    break;
                case "showdate":
                    if ( $adminView ) { 
                        echo $this->Html->link(
                            $this->Pretty->iconAdd($show->name . " " . __("Payroll Item")),
                            ['action' => 'addtoshow', $show->id],
                            ['escape' => false, 'class' => 'btn btn-outline-success btn-sm d-print-none']
                        );
                    } else {
                        echo $this->Html->link(
                            $this->Pretty->iconAdd($show->name . " " . __("Payroll Item")),
                            ['action' => 'addtoself', $show->id],
                            ['escape' => false, 'class' => 'btn btn-outline-success btn-sm d-print-none']
                        );
                    }
                    echo $this->Html->link(
                        $this->Pretty->iconDL($show->name . " " . __("Payroll Item")),
                        ['action' => 'viewbyshowdate', $show->id, $start_date, $end_date, 'csv'],
                        ['escape' => false, 'class' => 'btn btn-outline-dark btn-sm d-print-none']
                    );
                    break;
                case "user":
                    if ( $adminView ) {
                        echo $this->Html->link(
                            $this->Pretty->iconAdd($user->first . " " . $user->last . " " . __("Payroll Item")),
                            ['action' => 'addtouser', $user->id],
                            ['escape' => false, 'class' => 'btn btn-outline-success btn-sm d-print-none']
                        );
                        echo $this->Form->postLink(
                            $this->Pretty->iconMark($user->first . " " . $user->last),
                            ['action' => 'markuserpaid', $user->id],
                            ['escape' => false, 'confirm' => __('Are you sure you want to mark ALL paid for {0}?', $user->first . " " . $user->last),  'class' => 'btn btn-outline-warning btn-sm d-print-none']);
                    }
                    echo $this->Html->link(
                        $this->Pretty->iconDL($user->first . " " . $user->last . __('&#39;s Payroll Report')),
                        ['action' => 'viewbyuser', $user->id, 'csv'],
                        ['escape' => false, 'class' => 'btn btn-outline-dark btn-sm d-print-none']
                    );
                    break;
                case "unpaidshow":
                    // if ( $adminView ) { 
                    //     echo $this->Html->link(
                    //         $this->Pretty->iconAdd($show->name . " " . __("Payroll Item")),
                    //         ['action' => 'addtoshow', $show->id],
                    //         ['escape' => false, 'class' => 'btn btn-success btn-sm d-print-none']
                    //     );
                    //     echo $this->Form->postLink(
                    //         $this->Pretty->iconMark($show->name),
                    //         ['action' => 'markshowpaid', $show->id],
                    //         ['escape' => false, 'confirm' => __('Are you sure you want to mark ALL paid for {0}?', $show->name),  'class' => 'btn btn-warning btn-sm d-print-none']
                    //     );
                    // } else {
                    //     echo $this->Html->link(
                    //         $this->Pretty->iconAdd($show->name . " " . __("Payroll Item")),
                    //         ['action' => 'addtoself', $show->id],
                    //         ['escape' => false, 'class' => 'btn btn-success btn-sm d-print-none']
                    //     );
                    // }
                    echo $this->Html->link(
                        $this->Pretty->iconDL(__("Payroll Items By Show")),
                        ['action' => 'unpaid', 'show', 'csv'],
                        ['escape' => false, 'class' => 'btn btn-outline-dark btn-sm d-print-none']
                    );
                    break;
                case "unpaiduser":
                    if ( $adminView == 2 ) {
                        echo $this->Form->postLink(
                            $this->Pretty->iconMark('ALL'),
                            ['action' => 'markallpaid'],
                            ['escape' => false, 'confirm' => __('Are you sure you want to mark ALL Payroll items paid?'),  'class' => 'btn btn-outline-warning btn-sm d-print-none']
                        );
                    }
                    echo $this->Html->link(
                        $this->Pretty->iconDL(__('Unpaid by User Payroll Report')),
                        ['action' => 'unpaid', 'user', 'csv'],
                        ['escape' => false, 'class' => 'btn btn-outline-dark btn-sm d-print-none']
                    );
                    break;
            }
        ?>
        </div>
    </h3>
</div>

<?php if( isset($orphans) ) : ?>
<div role="alert" class="alert alert-dismissible fade in alert-warning">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    This show has orphaned payroll records by:
<?php
    $list = [];
    switch ( $viewMode ) {
        case "show":
            echo "This show has orphaned payroll records by: ";
            foreach ( $orphans as $orphan ) { $list[] = $orphan->fullname; }
            echo join(', ', $list);
            echo '. <a class="alert-link" href="/shows/editperm/' . $show->id . '">Fix It</a>';
            break;
        case "user":
            echo "This user has orphaned payroll records in: ";
            foreach ( $orphans as $orphan ) { $list[] = "<a class='alert-link' href='/shows/editperm/" . $orphan->show_id . "'>" . $orphan->showname . "</a>"; }
            echo join(', ', $list);
            break;
    }
?>
</div>
<?php endif; ?>

<div id="tableTop">
<table class="my-0 table table-striped table-bordered">
    <thead>
        <?php 
            $colspan = [ 4, 7 ];
            $headers = [
                __("Date Worked"),
                __("Note"),
                [__("Start Time") => ['class' => 'text-right']],
                [__("End Time") => ['class' => 'text-right']],
                [__("Hours Worked") => ['class' => 'text-right']],
                [__("Is Paid?") => ['class' => 'text-center']],
                [__("Actions") => ['class' => 'text-center']]
            ];
            //if ( $viewMode == "unpaidshow" ) { array_unshift($headers, __("User")); }
            if ( $viewMode == "unpaiduser" ) { array_unshift($headers, __("Show")); }
            
            if ( $viewMode == "unpaiduser" ) {
                $colspan = [ 5, 8 ];
            }
            echo $this->Html->tableHeaders($headers);
        ?>
    </thead>
</table>
</div>
<div id="tableBod">
<table class="my-0 table table-striped table-bordered">
    <tbody>
        <?php 

        $total = 0;
        $subtotal = 0;
        $upsubtotal = 0;
        $uptotal = 0;
        $lastuser = "";

        foreach ( $payrolls as $item ) {
            switch ( $viewMode ) {
                case "show":
                case "unpaidshow":
                case "showdate":
                    $thisItem = $item->fullname;
                    $thisItemName = __("User");
                    $thisItemExtra = "";
                    break;
                case "user":
                case "unpaiduser":
                    $thisItem = $item->showname;
                    $thisItemName = __("Show");
                    $thisItemExtra = ( ( ! $item->activeshow ) ? " [" . __('Closed') . "]" : " [" . __("Ending") . ": " . $item->show->end_date->i18nFormat('yyyy-MM-dd', 'UTC') . "]" );
                    break;
            }
            if ( $thisItem <> $lastuser ) {
                if ( $subtotal > 0 ) {
                    echo $this->Html->tableCells([
                        [
                            [ $thisItemName . " " . __('Sub-Total') . ": " . $lastuser , ['colspan' => $colspan[0]]],
                            [number_format($subtotal,2), ['class' => 'text-right']],
                            [ "", ['colspan' => 2]]
                        ]
                    ], ['class' => 'success bold'], null, 1, false);
                    echo $this->Html->tableCells([
                        [
                            [ $thisItemName . " " . __('Un-Paid Sub-Total') . ": " . $lastuser , ['colspan' => $colspan[0]]],
                            [number_format($upsubtotal,2), ['class' => 'text-right']],
                            [ "", ['colspan' => 2]]
                        ]
                    ], ['class' => 'warning bold'], null, 1, false);
                }
                echo $this->Html->tableCells([
                    [
                        [ $thisItemName . ": " . $thisItem . $thisItemExtra , ['colspan' => $colspan[1]]]
                    ]
                ], ['class' => 'info bold'], null, 1, false); 

                $subtotal = 0;
                $upsubtotal = 0;
                $lastuser = $thisItem;
            }

            $thisTableCell = [
                $item->date_worked->i18nFormat('EEE, MMM dd, yyyy', 'UTC'),
                $item->notes,
                [$item->start_time->i18nFormat([\IntlDateFormatter::NONE, \IntlDateFormatter::SHORT], 'UTC'), ['class' => 'text-right']],
                [$item->end_time->i18nFormat([\IntlDateFormatter::NONE, \IntlDateFormatter::SHORT], 'UTC'), ['class' => 'text-right']],
                [ number_format($item->worked, 2), ['class' => 'text-right']],
                [
                    ( ( $adminView && !$item->is_paid ) ?
                        "<a href=\"#\" id=\"mark-paid-{$item->id}\" data-item=\"{$item->id}\" class=\"mark-paid-btn btn btn-outline-warning btn-sm d-print-none\">" . $this->Pretty->iconMark($item->notes) . "</a>" : "" ) . " " .
                    "<span>" . $this->Bool->prefYes($item->is_paid) . "</span>", ['class' => 'text-center']
                ],
                [
                    ( ( $adminView || !$item->is_paid ) ?
                        $this->Html->link(
                            $this->Pretty->iconEdit($item->notes),
                            ['action' => 'edit', $item->id, $returnTo],
                            ['escape' => false, 'class' => 'btn btn-outline-dark btn-sm d-print-none']) .
                        $this->Form->postLink(
                            $this->Pretty->iconDelete($item->notes),
                            ['action' => 'delete', $item->id, $returnTo],
                            ['escape' => false, 'confirm' => __('Are you sure you want to delete # {0}?', $item->id), 'class' => 'btn btn-outline-danger btn-sm d-print-none'])
                        : "" ),
                    ['class' => 'text-center']
                ]
            ];

            //if ( $viewMode == "unpaidshow" ) { array_unshift($thisTableCell, $item->fullname); }
            if ( $viewMode == "unpaiduser" ) { array_unshift($thisTableCell, $item->showname); }

            echo $this->Html->tableCells([$thisTableCell]);

            $subtotal += $item->worked;
            $total += $item->worked;
            if ( ! $item->is_paid ) {
                $upsubtotal += $item->worked;
                $uptotal += $item->worked;
            }
        }
        if ( $total > 0 ) {
            echo $this->Html->tableCells([
                [
                    [ $thisItemName . " " . __('Sub-Total') . ": " . $lastuser , ['colspan' => $colspan[0]]],
                    [number_format($subtotal,2), ['class' => 'text-right']],
                    [ "", ['colspan' => 2]]
                ]
            ], ['class' => 'success bold'], null, 1, false);
            echo $this->Html->tableCells([
                [
                    [ $thisItemName . " " . __('Un-Paid Sub-Total') . ": " . $lastuser , ['colspan' => $colspan[0]]],
                    [number_format($upsubtotal,2), ['class' => 'text-right']],
                    [ "", ['colspan' => 2]]
                ]
            ], ['class' => 'warning bold'], null, 1, false);

            echo $this->Html->tableCells([
                [
                    [ __('Total Hours'), ['colspan' => $colspan[0]]],
                    [number_format($total,2), ['class' => 'text-right']],
                    [ "", ['colspan' => 2]]
                ]
            ], ['class' => 'danger bold'], null, 1, false);
            echo $this->Html->tableCells([
                [
                    [ __('Un-Paid Total Hours'), ['colspan' => $colspan[0]]],
                    [number_format($uptotal,2), ['class' => 'text-right']],
                    [ "", ['colspan' => 2]]
                ]
            ], ['class' => 'warning bold'], null, 1, false);
        }

        ?>
    </tbody>
</table>
</div>

<?= $this->Pretty->helpMeStart($helpTitle); ?>
<p>
<?php
    switch ( $viewMode ) {
        case "show":
            echo __("This display shows the payroll report for the specified show, broken down by user");
            break;
        case "user":
            echo __("This display shows the payroll report for the specified user, broken down by show");
            break;
        case "unpaiduser":
            echo __("This display shows the payroll report for unpaid hours, broken down by user");
            break;
        case "unpaidshow":
            echo __("This display shows the payroll report for unpaid hours, broken down by show");
            break;
    }
?>
</p>
<p><?= __("After the title, you may see the following buttons:") ?></p>
<?= $this->Html->nestedList([
        $this->Pretty->helpButton('plus', 'success', __('Plus Button'), __('Add a payroll record to the show')),
        $this->Pretty->helpButton('check', 'warning', __('Check Button'), __('Mark ALL payroll records paid')),
        $this->Pretty->helpButton('cloud-download', 'default', __('Download Button'), __('Download a CSV file for offline printing or editing'))
    ], ['class' => 'list-group'], ['class' => 'list-group-item']
); ?>

<p><?= __("For each entry, you may see these three buttons:") ?></p>
<?= $this->Html->nestedList([
        $this->Pretty->helpButton('check', 'warning', __('Check Button'), __('Mark the payroll record paid')),
        $this->Pretty->helpButton('pencil', 'default', __('Pencil Button'), __('Edit the payroll record')),
        $this->Pretty->helpButton('trash', 'danger', __('Trash Button'), __('Remove the payroll record'))
    ], ['class' => 'list-group'], ['class' => 'list-group-item']
); ?>

<p><?= __("Only payroll admin's may mark records paid.  Regular payroll users may only edit or delete payroll records that have not yet been marked paid.") ?></p>

<h4><?= __("Orphaned Records Warning") ?></h4>
<p><?= __("System administrators may see a warning about orphaned records.  This is caused when a user adds payroll records and is later denied access to a show.  These records will not print on any reports, but they will cause the totals on the dashboard to be incorrect.  To fix these, you will need to re-grant access to that user before removing those records.") ?></p>
<?= $this->Pretty->helpMeEnd(); ?>
