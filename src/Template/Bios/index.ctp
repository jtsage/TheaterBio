<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Bio[]|\Cake\Collection\CollectionInterface $bios
 */
?>
<div class="bios index large-9 medium-8 columns content">
    <h3><?= __('Bios') ?>
    <?= $this->Html->link(
        $this->Pretty->iconAdd(__("Bio")),
        ['action' => 'add'],
        ['escape' => false, 'class' => 'btn btn-outline-success btn-sm']
    ) ?>
    </h3>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('user_id', __('User')) ?></th>
                <th scope="col"><?= $this->Paginator->sort('purpose_id', __('Purpose')) ?></th>
                <th scope="col" style="width:55%"><?= __('Bio') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bios as $bio): ?>
            <tr>
                <td><?= $bio->has('user') ? $bio->user->print_name : '' ?></td>
                <td><?= $bio->has('purpose') ? $this->Html->link($bio->purpose->name, ['controller' => 'Purposes', 'action' => 'view', $bio->purpose->id]) : '' ?></td>
                <td><?= $bio->text ?></td>
                <td class="actions"><div class="btn-group" role="group">
                    <?= $this->Html->link(
                        $this->Pretty->iconView($bio->purpose->name),
                        ['action' => 'view', $bio->id],
                        ['escape' => false,  'class' => 'btn btn-outline-dark btn-sm']
                    ) ?>
                    <?= $this->Html->link(
                        $this->Pretty->iconEdit($bio->purpose->name),
                        ['action' => 'edit', $bio->id],
                        ['escape' => false,  'class' => 'btn btn-outline-dark btn-sm']
                    ) ?>
                    <?= $this->Form->postLink(
                        $this->Pretty->iconDelete($bio->purpose->name),
                        ['action' => 'delete', $bio->id],
                        ['escape' => false, 'confirm' => __('Are you sure you want to delete # {0}?', $bio->id),  'class' => 'btn btn-outline-danger btn-sm']
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



<?= $this->Pretty->helpMeStart(__('Bios List')); ?>
<p><?= __("This display shows the bios that you have access to."); ?></p>
<p><?= __("Please note, you may only have one bio per show."); ?></p>
<p><?= __("Near the title, you may see one button:"); ?></p>
<?= $this->Html->nestedList([
        $this->Pretty->helpButton('plus', 'success', __('Plus Button'), __('Add a bio to the system (admin only)'))
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