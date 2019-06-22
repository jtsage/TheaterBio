<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Users2[]|\Cake\Collection\CollectionInterface $users2
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Users2'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="users2 index large-9 medium-8 columns content">
    <h3><?= __('Users2') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('username') ?></th>
                <th scope="col"><?= $this->Paginator->sort('password') ?></th>
                <th scope="col"><?= $this->Paginator->sort('first') ?></th>
                <th scope="col"><?= $this->Paginator->sort('last') ?></th>
                <th scope="col"><?= $this->Paginator->sort('phone') ?></th>
                <th scope="col"><?= $this->Paginator->sort('is_active') ?></th>
                <th scope="col"><?= $this->Paginator->sort('is_password_expired') ?></th>
                <th scope="col"><?= $this->Paginator->sort('is_admin') ?></th>
                <th scope="col"><?= $this->Paginator->sort('last_login_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('time_zone') ?></th>
                <th scope="col"><?= $this->Paginator->sort('reset_hash') ?></th>
                <th scope="col"><?= $this->Paginator->sort('reset_hash_time') ?></th>
                <th scope="col"><?= $this->Paginator->sort('verify_hash') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users2 as $users2): ?>
            <tr>
                <td><?= h($users2->id) ?></td>
                <td><?= h($users2->username) ?></td>
                <td><?= h($users2->password) ?></td>
                <td><?= h($users2->first) ?></td>
                <td><?= h($users2->last) ?></td>
                <td><?= $this->Number->format($users2->phone) ?></td>
                <td><?= h($users2->is_active) ?></td>
                <td><?= h($users2->is_password_expired) ?></td>
                <td><?= h($users2->is_admin) ?></td>
                <td><?= h($users2->last_login_at) ?></td>
                <td><?= h($users2->created_at) ?></td>
                <td><?= h($users2->updated_at) ?></td>
                <td><?= h($users2->time_zone) ?></td>
                <td><?= h($users2->reset_hash) ?></td>
                <td><?= h($users2->reset_hash_time) ?></td>
                <td><?= h($users2->verify_hash) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $users2->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $users2->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $users2->id], ['confirm' => __('Are you sure you want to delete # {0}?', $users2->id)]) ?>
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
