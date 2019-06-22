<div class="budgets view large-10 medium-9 columns">
    <h3>
        <?= h($show->name) ?>
        <div class="btn-group">
        <?php if ( $opsok ) { echo $this->Html->link(
            $this->Pretty->iconAdd($show->name . " " . __("Budget Item")),
            ['action' => 'add', $show->id],
            ['escape' => false, 'class' => 'btn btn-outline-success btn-sm']
        ); } ?>
        <?= $this->Html->link(
            $this->Pretty->iconDL($show->name . " " . __("Budget")),
            ['action' => 'viewcsv', $show->id],
            ['escape' => false, 'class' => 'btn btn-outline-dark btn-sm']
        ) ?>
        </div>
    </h3>
    <div id="tableTop">
    <table class="table table-striped table-bordered my-0">
        <thead style="position: sticky">
            <?= $this->Html->tableHeaders([
                __('Date'),
                __('Vendor'),
                __('Description'),
                [__('Price') => ['class' => 'text-right']],
                [__('Actions') => ['class' => 'text-center']]
            ]); ?>
        </thead>
    </table>
    </div>
    <div id="tableBod">
    <table class="table table-striped table-bordered">
        <tbody>
        <?php
            $lastcat = "";
            $total = 0;
            $subtotal = 0;

            foreach ( $budgets as $item ) {
                if ( $item->category <> $lastcat ) {
                    if ( $subtotal > 0 ) {
                        echo $this->Html->tableCells([
                            [
                                [ __('Category Sub-Total') . ": " . $lastcat , ['colspan' => 3]],
                                [$this->Number->currency($subtotal), ['class' => 'text-right']],
                                ""
                            ]
                        ], ['class' => 'success bold'], null, 1, false); 
                    }
                    echo $this->Html->tableCells([
                        [
                            [ __('Category') . ": " . $item->category , ['colspan' => 5]]
                        ]
                    ], ['class' => 'info bold'], null, 1, false); 

                    $subtotal = 0;
                    $lastcat = $item->category;
                }

                echo $this->Html->tableCells([
                    [
                        $item->date->i18nFormat([\IntlDateFormatter::MEDIUM, \IntlDateFormatter::NONE], 'UTC'),
                        $item->vendor,
                        $item->description,
                        [ $this->Number->currency($item->price), ['class' => 'text-right']],
                        [
                            "<div class='btn-group'>" .
                            ( ($opsok) ? $this->Html->link(
                                $this->Pretty->iconEdit($item->description),
                                ['action' => 'edit', $item->id],
                                ['escape' => false, 'class' => 'btn btn-outline-dark btn-sm' ] )
                            : "" ) .
                            ( ($opsok) ? $this->Form->postLink(
                                $this->Pretty->iconDelete($item->description),
                                ['action' => 'delete', $item->id],
                                ['escape' => false, 'confirm' => __('Are you sure you want to delete # {0}?', $item->id), 'class' => 'btn btn-outline-danger btn-sm' ] )
                            : "" ) .
                            "</div>",
                            ['class' => 'text-center']
                        ]
                    ]
                ]);

                $subtotal += $item->price;
                $total += $item->price;
            }

            echo $this->Html->tableCells([
                [
                    [ __('Category Sub-Total') . ": " . $lastcat , ['colspan' => 3]],
                    [$this->Number->currency($subtotal), ['class' => 'text-right']],
                    ""
                ]
            ], ['class' => 'success bold'], null, 1, false); 

            echo $this->Html->tableCells([
                [
                    [ __('Total Expenditure'), ['colspan' => 3]],
                    [$this->Number->currency($total), ['class' => 'text-right']],
                    ""
                ]
            ], ['class' => 'danger bold'], null, 1, false); 
        ?>
        </tbody>
    </table>
    </div>
</div>

<?= $this->Pretty->helpMeStart(__('View Detailed Budget')); ?>
<p><?= __("This display shows detailed budget of the current show, broken down by budget category.") ?></p>
<p><?= __("Next to the show title, there are two buttons:") ?></p>
<?= $this->Html->nestedList([
        $this->Pretty->helpButton('plus', 'success', __('Plus Button'), __('Add an expense to the show')),
        $this->Pretty->helpButton('cloud-download', 'default', __('Download Button'), __('Download a CSV file for offline editing or printing'))
    ], ['class' => 'list-group'], ['class' => 'list-group-item']
); ?>


<p><?= __("For each budget item, there are two buttoms:") ?></p>
<?= $this->Html->nestedList([
        $this->Pretty->helpButton('pencil', 'default', __('Pencil Button'), __('Edit this budget expense')),
        $this->Pretty->helpButton('trash', 'danger', __('Trash Button'), __('Remove this budget expense'))
    ], ['class' => 'list-group'], ['class' => 'list-group-item']
); ?>

<?= $this->Pretty->helpMeEnd(); ?>