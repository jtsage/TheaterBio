<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Purpose $purpose
 */
?>

<div class="purposes form large-9 medium-8 columns content">
    <?= $this->Form->create($purpose) ?>
    <fieldset>
        <legend><?= __('Edit Purpose') ?></legend>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('description');
            echo $this->Pretty->check('is_active', $purpose->is_active, [
                'label-width' => '100',
                'label-text' => __('Is Active'),
                'on-text' => __('YES'),
                'off-text' => __('NO'),
                'on-color' => 'success',
                'off-color' => 'danger'
            ]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Save')) ?>
    <?= $this->Form->end() ?>
</div>

<?= $this->Pretty->helpMeStart(__('Edit Purpose')); ?>

<p><?= __('Edit a purpose in the system') ?></p>
<?= $this->Pretty->helpMeEnd(); ?>