<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Photo $photo
 */
?>


<div class="photos form large-9 medium-8 columns content">
    <?= $this->Form->create($photo, ['type' => 'file']) ?>
    <fieldset>
        <legend><?= __('Add Headshot') ?></legend>
        <p>You may have only 1 headshot in the system.  PNG is the preferred format, but JPG is also accepted.  Please send the highest quality version of the image you have.  Uploads are limited to 20Mb in size.</p>

        <?php
            echo $this->Form->control('user_id', ['options' => $users]);
            echo $this->Form->control('file', ['type' => 'file', 'accept' => 'image/jpeg,image/gif,image/png'] );
        ?>
    </fieldset>
    <?= $this->Form->button(__('Save')) ?>
    <?= $this->Form->end() ?>
</div>

<?= $this->Pretty->helpMeStart(__('Add Headshot')); ?>

<p><?= __('Add a headshot to the system') ?></p>
<p><?= __('Note you may only have 1 headshot per user in the system') ?></p>
<?= $this->Pretty->helpMeEnd(); ?>