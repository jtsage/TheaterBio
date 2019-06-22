<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Purpose[]|\Cake\Collection\CollectionInterface $purposes
 */
?>
<h3><?= __("Purposes"); ?> 
<?= $this->Html->link(
        $this->Pretty->iconAdd(__("Purpose")),
        ['action' => 'add'],
        ['escape' => false, 'class' => 'btn btn-outline-success btn-sm']
    ) ?>
</h3>
<p><?= __("These are the purposes users can submit bios &amp; headshots for") ?></p>

<div class="purposes index large-9 medium-8 columns content">
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('name', __("Name")) ?></th>
                <th style="width:55%" scope="col"><?= $this->Paginator->sort('description', __("Description")) ?></th>
                <th scope="col"><?= $this->Paginator->sort('is_active', __("Is Active?")) ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($purposes as $purpose): ?>
            <tr>
                <td><?= h($purpose->name) ?></td>
                <td><?= h($purpose->description) ?></td>
                <td><?= $this->Bool->prefYes($purpose->is_active) ?></td>
                <td class="actions"><div class="btn-group" role="group">
                    <?= $this->Html->link(
                        $this->Pretty->iconView($purpose->name),
                        ['action' => 'view', $purpose->id],
                        ['escape' => false,  'class' => 'btn btn-outline-dark btn-sm']
                    ) ?>
                    <?= $this->Html->link(
                        $this->Pretty->iconEdit($purpose->name),
                        ['action' => 'edit', $purpose->id],
                        ['escape' => false,  'class' => 'btn btn-outline-dark btn-sm']
                    ) ?>
                    <?= $this->Form->postLink(
                        $this->Pretty->iconDelete($purpose->name),
                        ['action' => 'delete', $purpose->id],
                        ['escape' => false, 'confirm' => __('Are you sure you want to delete # {0}?', $purpose->id),  'class' => 'btn btn-outline-danger btn-sm']
                    ) ?>
                </div></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>


<?= $this->Pretty->helpMeStart(__('Purpose List')); ?>
<p><?= __("This display shows the purposes that you have access to."); ?></p>
<p><?= __("Near the title, you may see one button:"); ?></p>
<?= $this->Html->nestedList([
        $this->Pretty->helpButton('plus', 'success', __('Plus Button'), __('Add a purpose to the system (admin only)'))
    ], ['class' => 'list-group'], ['class' => 'list-group-item']
); ?>

<p><?= _("For each purpose, you may see up to three buttons:") ?></p>
<?= $this->Html->nestedList([
        $this->Pretty->helpButton('eye', 'default', __('Eye Button'), __('View a detailed purpose record')),
        $this->Pretty->helpButton('pencil', 'default', __('Pencil Button'), __('Edit the purpose record')),
        $this->Pretty->helpButton('trash', 'danger', __('Trash Button'), __('Permanantly remove the purpose from the system, and all historical data about it (including associated bios and headshots).  Very, very destructive - use with extream caution.'))
    ], ['class' => 'list-group'], ['class' => 'list-group-item']
); ?>

<?= $this->Pretty->helpMeEnd(); ?>
