<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Headshot $headshot
 */
?>

<div class="headshots form large-9 medium-8 columns content">
    <?= $this->Form->create($headshot, ['type' => 'file']) ?>
    <fieldset>
        <legend><?= __('Add Headshot') ?></legend>
        <p class='text-success'>Note: For most things, square cropped headshots are preffered.  Please use the highest quality image you have available.</p>
        <p class='text-primary'><strong>JPEG, GIF, and PNG images are accepted.  No PDF's.  PNG is preferred.</p>
        <?php
            echo $this->Form->control('user_id', ['options' => $users]);
            echo $this->Form->control('purpose_id', ['options' => $purposes]);
            echo $this->Form->control('file', ['type' => 'file', 'accept' => 'image/jpeg,image/gif,image/png'] );
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

<?= $this->Pretty->helpMeStart(__('Add Headshot')); ?>

<p><?= __('Add a headshot to the system') ?></p>
<p><?= __('Note you may only have 1 headshot per purpose in the system') ?></p>
<?= $this->Pretty->helpMeEnd(); ?>