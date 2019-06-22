<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Bio $bio
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Bio'), ['action' => 'edit', $bio->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Bio'), ['action' => 'delete', $bio->id], ['confirm' => __('Are you sure you want to delete # {0}?', $bio->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Bios'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Bio'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Purposes'), ['controller' => 'Purposes', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Purpose'), ['controller' => 'Purposes', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="bios view large-9 medium-8 columns content">
    <h3><?= h($bio->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= h($bio->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $bio->has('user') ? $this->Html->link($bio->user->id, ['controller' => 'Users', 'action' => 'view', $bio->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Purpose') ?></th>
            <td><?= $bio->has('purpose') ? $this->Html->link($bio->purpose->name, ['controller' => 'Purposes', 'action' => 'view', $bio->purpose->id]) : '' ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Text') ?></h4>
        <?= $this->Text->autoParagraph(h($bio->text)); ?>
    </div>
</div>
