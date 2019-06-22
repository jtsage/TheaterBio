<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\File[]|\Cake\Collection\CollectionInterface $files
 */
?>
<h3>
    <?= __("Files"); ?>
    <div class="btn-group">
        <?= (($opsok) ? $this->Html->link(
            $this->Pretty->iconAdd(__("File")),
            ['action' => 'add'],
            ['escape' => false, 'class' => 'btn btn-outline-success btn-sm']
        ) : ""); ?>
        </div>
</h3>

<div class="files index large-9 medium-8 columns content">
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('dsc') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($files as $file): ?>
            <tr>
                <td><?= $this->Number->format($file->id) ?></td>
                <td><?= h($file->name) ?></td>
                <td><?= h($file->dsc) ?></td>



                <td class="actions text-center">
                    <div class='btn-group'>
                            <?= $this->Html->link(
                                $this->Pretty->iconDL($file->dsc),
                                ['action' => 'view', $file->id],
                                ['escape' => false, 'class' => 'btn btn-outline-dark btn-sm' ] ) ?>
                            
                            <?= (($opsok) ? $this->Form->postLink(
                                $this->Pretty->iconDelete($file->dsc),
                                ['action' => 'delete', $file->id],
                                ['escape' => false, 'confirm' => __('Are you sure you want to delete # {0}?', $file->id), 'class' => 'btn btn-outline-danger btn-sm' ] ) : "") ?>
                            
                    </div>
                </td>
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

<?= $this->Pretty->helpMeStart(__('Stored Files')); ?>
<p><?= __("This display allows you to view all the currently stored files.") ?></p>
<p><?= __("These files are available to all site participants.  Store things like how-to instructions for your specific installation here.  They can also be attached to the new user welcome e-mail.") ?></p>

<?= $this->Pretty->helpMeEnd(); ?>
