<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Users2 $users2
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $users2->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $users2->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Users2'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="users2 form large-9 medium-8 columns content">
    <?= $this->Form->create($users2) ?>
    <fieldset>
        <legend><?= __('Edit Users2') ?></legend>
        <?php
            echo $this->Form->control('username');
            echo $this->Form->control('password');
            echo $this->Form->control('first');
            echo $this->Form->control('last');
            echo $this->Form->control('phone');
            echo $this->Form->control('is_active');
            echo $this->Form->control('is_password_expired');
            echo $this->Form->control('is_admin');
            echo $this->Form->control('last_login_at');
            echo $this->Form->control('created_at');
            echo $this->Form->control('updated_at');
            echo $this->Form->control('time_zone');
            echo $this->Form->control('reset_hash');
            echo $this->Form->control('reset_hash_time');
            echo $this->Form->control('verify_hash');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
