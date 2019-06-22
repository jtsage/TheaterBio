<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Headshot $headshot
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Headshot'), ['action' => 'edit', $headshot->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Headshot'), ['action' => 'delete', $headshot->id], ['confirm' => __('Are you sure you want to delete # {0}?', $headshot->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Headshots'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Headshot'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Purposes'), ['controller' => 'Purposes', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Purpose'), ['controller' => 'Purposes', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="headshots view large-9 medium-8 columns content">
    <h3><?= h($headshot->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= h($headshot->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $headshot->has('user') ? $this->Html->link($headshot->user->id, ['controller' => 'Users', 'action' => 'view', $headshot->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Purpose') ?></th>
            <td><?= $headshot->has('purpose') ? $this->Html->link($headshot->purpose->name, ['controller' => 'Purposes', 'action' => 'view', $headshot->purpose->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('File') ?></th>
            <td><?= h($headshot->file) ?></td>
        </tr>
    </table>
</div>
