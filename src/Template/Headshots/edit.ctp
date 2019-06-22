<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Headshot $headshot
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $headshot->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $headshot->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Headshots'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Purposes'), ['controller' => 'Purposes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Purpose'), ['controller' => 'Purposes', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="headshots form large-9 medium-8 columns content">
    <?= $this->Form->create($headshot) ?>
    <fieldset>
        <legend><?= __('Edit Headshot') ?></legend>
        <?php
            echo $this->Form->control('user_id', ['options' => $users]);
            echo $this->Form->control('purpose_id', ['options' => $purposes]);
            echo $this->Form->control('file');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
