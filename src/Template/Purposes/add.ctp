<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Purpose $purpose
 */
?>
<div class="purposes form large-9 medium-8 columns content">
    <?= $this->Form->create($purpose) ?>
    <fieldset>
        <legend><?= __('Add Purpose') ?></legend>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('description');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
