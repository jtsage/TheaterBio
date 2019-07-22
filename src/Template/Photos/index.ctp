<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Photo[]|\Cake\Collection\CollectionInterface $photos
 */
?>
<h2><?= __('Headshots') ?>
    <?= $this->Html->link(
        $this->Pretty->iconAdd(__("Headshot")),
        ['action' => 'add'],
        ['escape' => false, 'class' => 'btn btn-outline-success btn-sm']
    ) ?>
</h2>
<p>You may have only 1 headshot in the system.  PNG is the preferred format, but JPG is also accepted.  Please send the highest quality version of the image you have.  Uploads are limited to 20Mb in size.</p>

<div class="row mb-4">
<?php foreach ($photos as $photo): ?>
<div class="col-sm-6 col-md-4 col-lg-3">
    <div class="text-center border rounded-top py-2"></strong><?= $photo->user->print_name ?></strong></div>
    <?= $this->Html->link(
        $this->Pretty->iconDL(__("Headshot")),
        ['action' => 'download', $photo->id],
        ['escape' => false, 'class' => 'btn btn-outline-info w-100 rounded-0 btn-sm']
    ) ?>
    <?= $this->Html->link(
        "<img src=\"" . preg_replace("/webroot/", "", $photo->dir) . "/" . $photo->file . "\" class=\"img-fluid border\">",
        ['action' => 'view', $photo->id],
        ['escape' => false, 'target' => '_blank']
    ) ?><br />
    <?= $this->Form->postLink(
        $this->Pretty->iconDelete($photo->user->print_name),
        ['action' => 'delete', $photo->id],
        ['escape' => false, 'confirm' => __('Are you sure you want to delete # {0}?', $photo->id),  'class' => 'btn w-100 btn-outline-danger btn-sm']
    ) ?>
    
</div>
<?php endforeach; ?>
</div>

<?= $this->Pretty->helpMeStart(__('View Headshots')); ?>

<p><?= __('View the headshots in the system') ?></p>
<p><?= __('Note you may only have 1 headshot per user in the system') ?></p>
<?= $this->Pretty->helpMeEnd(); ?>