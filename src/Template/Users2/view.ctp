<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Users2 $users2
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Users2'), ['action' => 'edit', $users2->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Users2'), ['action' => 'delete', $users2->id], ['confirm' => __('Are you sure you want to delete # {0}?', $users2->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Users2'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Users2'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="users2 view large-9 medium-8 columns content">
    <h3><?= h($users2->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= h($users2->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Username') ?></th>
            <td><?= h($users2->username) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Password') ?></th>
            <td><?= h($users2->password) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('First') ?></th>
            <td><?= h($users2->first) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Last') ?></th>
            <td><?= h($users2->last) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Time Zone') ?></th>
            <td><?= h($users2->time_zone) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Reset Hash') ?></th>
            <td><?= h($users2->reset_hash) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Verify Hash') ?></th>
            <td><?= h($users2->verify_hash) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Phone') ?></th>
            <td><?= $this->Number->format($users2->phone) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Last Login At') ?></th>
            <td><?= h($users2->last_login_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created At') ?></th>
            <td><?= h($users2->created_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated At') ?></th>
            <td><?= h($users2->updated_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Reset Hash Time') ?></th>
            <td><?= h($users2->reset_hash_time) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Active') ?></th>
            <td><?= $users2->is_active ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Password Expired') ?></th>
            <td><?= $users2->is_password_expired ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Admin') ?></th>
            <td><?= $users2->is_admin ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
</div>
