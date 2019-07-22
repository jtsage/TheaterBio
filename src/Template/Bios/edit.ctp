<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Bio $bio
 */
?>

<div class="bios form large-9 medium-8 columns content">
    <?= $this->Form->create($bio) ?>
    <fieldset>
        <legend><?= __('Edit Bio') ?></legend>
        <?php
            echo $this->Form->control('user_id', ['options' => $users]);
            echo $this->Form->control('purpose_id', ['options' => $purposes]);
            echo $this->Form->control('role', ['label' => 'Role and/or Title (i.e. Javert']);
            echo "<h5>Please select Production or Cast member below</h5>";
            echo $this->Pretty->check('is_prod', $bio->is_prod, [
                'label-width' => '100',
                'label-text' => __('Member Of'),
                'on-text' => __('Production'),
                'off-text' => __('Cast'),
                'on-color' => 'success',
                'off-color' => 'info'
            ]);
            echo $this->Form->control('text', ['label' => 'Text Body']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Save')) ?>
    <?= $this->Form->end() ?>
</div>

<?= $this->Pretty->helpMeStart(__('Edit Biography')); ?>

<p><?= __('Edit a bio in the system') ?></p>
<p><?= __('Note you may only have 1 bio per purpose in the system') ?></p>
<?= $this->Pretty->helpMeEnd(); ?>