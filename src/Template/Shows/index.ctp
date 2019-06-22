<h3><?= __("Show List"); ?>
    <?= (($isAdmin) ? $this->Html->link(
        $this->Pretty->iconAdd(__("Show")),
        ['action' => 'add'],
        ['escape' => false, 'class' => 'btn btn-outline-success btn-sm']
    ) : "" ) ?>
</h3>
<div class="shows index large-10 medium-9 columns">
    <table class="table table-hover">
    <thead>
        <tr>
            <?= $this->Html->tableHeaders([
                $this->Paginator->sort('name', __("Name")),
                $this->Paginator->sort('location', __("Location")),
                $this->Paginator->sort('end_date', __("End Date")),
                $this->Paginator->sort('is_active', __('Is Open'), ['direction' => 'DESC']),
                "Reminders Sent?",
                [__('Actions') => ['class' => 'text-center']]
            ]); ?>
        </tr>
    </thead>
    <tbody>
    <?php 
    $last_status = 1;
    foreach ($shows as $show) {
        if ( $show->is_active <> $last_status ) {
            echo $this->Html->tableCells([
                [  
                    [ __('Closed Shows'), ['colspan' => '6', 'class' => 'text-center danger'] ]
                ]
            ], ['class' => 'bold'], null, 1, false);
            $last_status = 0;
        }
        echo $this->Html->tableCells([
            [
                h($show->name),
                h($show->location),
                $show->end_date->i18nFormat([
                        \IntlDateFormatter::MEDIUM,
                        \IntlDateFormatter::NONE
                    ], 'UTC'),
                $this->Bool->prefYes($show->is_active),
                $this->Bool->prefNo($show->is_reminded),
                [
                    '<div class="btn-group" role="group">' .
                    $this->Html->link(
                        $this->Pretty->iconView($show->name),
                        ['action' => 'view', $show->id],
                        ['escape' => false,  'class' => 'btn btn-outline-dark btn-sm']
                    ) .
                    (( $isAdmin ) ? $this->Html->link(
                        $this->Pretty->iconEdit($show->name),
                        ['action' => 'edit', $show->id],
                        ['escape' => false,  'class' => 'btn btn-outline-dark btn-sm']
                    ) : "" ) .
                    (( $isAdmin ) ? $this->Html->link(
                        $this->Pretty->iconPerm($show->name),
                        ['action' => 'editperm', $show->id],
                        ['escape' => false,  'class' => 'btn btn-outline-warning btn-sm']
                    ) : "" ) .
                    (( $isAdmin ) ? $this->Form->postLink(
                        $this->Pretty->iconDelete($show->name),
                        ['action' => 'delete', $show->id],
                        ['escape' => false, 'confirm' => __('Are you sure you want to delete # {0}?', $show->id),  'class' => 'btn btn-outline-danger btn-sm']
                    ) : "" ) .
                    '</div>',
                    ['class' => 'text-center']
                ]
            ]
        ]);
    } ?>

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

<?= $this->Pretty->helpMeStart(__('Show List')); ?>
<p><?= __("This display shows the shows that you have access to."); ?></p>
<p><?= __("Near the title, you may see one button:"); ?></p>
<?= $this->Html->nestedList([
        $this->Pretty->helpButton('plus', 'success', __('Plus Button'), __('Add a show to the system (admin only)'))
    ], ['class' => 'list-group'], ['class' => 'list-group-item']
); ?>

<p><?= _("For each show, you may see up to four buttons:") ?></p>
<?= $this->Html->nestedList([
        $this->Pretty->helpButton('eye', 'default', __('Eye Button'), __('View a detailed show record')),
        $this->Pretty->helpButton('pencil', 'default', __('Pencil Button'), __('Edit the show record')),
        $this->Pretty->helpButton('cogs', 'warning', __('Gears Button'), __('Change the show\'s permission sets')),
        $this->Pretty->helpButton('trash', 'danger', __('Trash Button'), __('Permanantly remove the show from the system, and all historical data about it.  Very, very destructive - use with extream caution.'))
    ], ['class' => 'list-group'], ['class' => 'list-group-item']
); ?>

<?= $this->Pretty->helpMeEnd(); ?>
