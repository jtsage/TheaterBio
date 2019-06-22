<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\File $file
 */
?>

<div class="files form large-9 medium-8 columns content">
    <?= $this->Form->create($file, ['type' => 'file']) ?>
    <fieldset>
        <legend><?= __('Add File') ?></legend>
        <?php
            echo $this->Form->input('name', ['label' => __('Name (filename w/ extension)')]);
            echo $this->Form->input('dsc', ['label' => __('Description')]);
            echo $this->Form->control('uppy', ['label' => __('File'), 'type' => 'file']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

<?= $this->Pretty->helpMeStart(__('Add File')); ?>
<p><?= __("This display allows you to add a system file.") ?></p>

<table class="table table-condensed helptable">
<?= $this->Html->tableCells([
    [__("Name"),               __("Name of the file (filename).")],
    [__("Description"),        __("A description of the file.")],
    [__("File"),               __("Data file from your PC.")]
]); ?>
</table>

<?= $this->Pretty->helpMeEnd(); ?>