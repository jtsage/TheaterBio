<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Headshot[]|\Cake\Collection\CollectionInterface $headshots
 */
?>
<?php $last_purpose_id = "" ?>
<div class="headshots index large-9 medium-8 columns content">
    <h2><?= __('Headshots') ?>
    <?= $this->Html->link(
        $this->Pretty->iconAdd(__("Headshot")),
        ['action' => 'add'],
        ['escape' => false, 'class' => 'btn btn-outline-success btn-sm']
    ) ?>
    </h2>
    <div class="row mb-4">
    <?php foreach ($headshots as $headshot): ?>
    <?php 
        if ( $last_purpose_id <> $headshot->purpose_id ) {
            echo "</div><h3>" . $headshot->purpose->name . "</h3><h5>" . $headshot->purpose->description . "</h5><div class='row mb-5'>";
            $last_purpose_id = $headshot->purpose_id;
        }
    ?>
    <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="text-center border rounded-top py-2"></strong><?= $headshot->user->print_name ?></strong></div>
        <?= $this->Html->link(
            $this->Pretty->iconDL(__("Headshot")),
            ['action' => 'download', $headshot->id],
            ['escape' => false, 'class' => 'btn btn-outline-info w-100 rounded-0 btn-sm']
        ) ?>
        <a href="<?= preg_replace("/webroot/", "", $headshot->dir) . "/" . $headshot->file ?>" target="_blank">
        <img src="<?= preg_replace("/webroot/", "", $headshot->dir) . "/" . $headshot->file ?>" class="img-fluid border">
        </a><br />
        <?= $this->Form->postLink(
            $this->Pretty->iconDelete($headshot->user->print_name),
            ['action' => 'delete', $headshot->id],
            ['escape' => false, 'confirm' => __('Are you sure you want to delete # {0}?', $headshot->id),  'class' => 'btn w-100 btn-outline-danger btn-sm']
        ) ?>
        
    </div>
    <?php endforeach; ?>
</div>
<?= $this->Pretty->helpMeStart(__('View Headshots')); ?>

<p><?= __('View the headshots in the system') ?></p>
<p><?= __('Note you may only have 1 headshot per purpose in the system') ?></p>
<?= $this->Pretty->helpMeEnd(); ?>