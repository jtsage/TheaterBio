<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Headshot[]|\Cake\Collection\CollectionInterface $headshots
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Headshot'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Purposes'), ['controller' => 'Purposes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Purpose'), ['controller' => 'Purposes', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="headshots index large-9 medium-8 columns content">
    <h3><?= __('Headshots') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('purpose_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('file') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($headshots as $headshot): ?>
            <tr>
                <td><?= h($headshot->id) ?></td>
                <td><?= $headshot->has('user') ? $this->Html->link($headshot->user->id, ['controller' => 'Users', 'action' => 'view', $headshot->user->id]) : '' ?></td>
                <td><?= $headshot->has('purpose') ? $this->Html->link($headshot->purpose->name, ['controller' => 'Purposes', 'action' => 'view', $headshot->purpose->id]) : '' ?></td>
                <td><?= h($headshot->file) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $headshot->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $headshot->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $headshot->id], ['confirm' => __('Are you sure you want to delete # {0}?', $headshot->id)]) ?>
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
