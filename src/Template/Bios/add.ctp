<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Bio $bio
 */
?>
<div class="bios form large-9 medium-8 columns content">
    <?= $this->Form->create($bio) ?>
    <fieldset>
        <legend><?= __('Add Bio') ?></legend>
        <?php
            echo $this->Form->control('user_id', ['options' => $users]);
            echo $this->Form->control('purpose_id', ['options' => $purposes]);
            echo $this->Form->control('role', ['label' => 'Role and/or Title (i.e. Javert']);
            echo $this->Form->control('text', ['label' => 'Text Body']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

<?= $this->Pretty->helpMeStart(__('Add Biography')); ?>

<p><?= __('Add a bio to the system') ?></p>
<p><?= __('Note you may only have 1 bio per purpose in the system') ?></p>
<?= $this->Pretty->helpMeEnd(); ?>
