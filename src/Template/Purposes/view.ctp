<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Purpose $purpose
 */
?>
<div class="purposes view large-9 medium-8 columns content">
    <h3><?= h($purpose->name) ?>
    <?= $this->Html->link(
            $this->Pretty->iconDL($purpose->name),
            ['action' => 'download', $purpose->id],
            ['escape' => false, 'class' => 'btn btn-outline-info btn-sm']
        ) ?>
    <?= $this->Html->link(
            $this->Pretty->iconEdit($purpose->name),
            ['action' => 'edit', $purpose->id],
            ['escape' => false, 'class' => 'btn btn-outline-dark btn-sm']
        ) ?>
    </h3>
    <h5><?= h($purpose->description) ?> <small>(<?= ( $purpose->is_active ? "Active" : "Closed" ) ?>)</small></h5>

    <div class="related mt-5">
        <h4><?= __('Related Bios and Headshots') ?></h4>
        <?php if (!empty($purpose->users)): ?>
        <table cellpadding="0" cellspacing="0">
            <?php foreach ($purpose->users as $user): ?>
            <tr>
                <td class="p-2 align-top" style="width:190px">
                <?php foreach ( $user->headshots as $headshot ): ?>
                <?php if ( $headshot->purpose_id == $purpose->id ) : ?>
                    <img src="<?= preg_replace("/webroot/", "", $headshot->dir) . "/" . $headshot->file ?>" class="img-fluid">
                    <?= $this->Html->link(
                        $this->Pretty->iconDL(__("Headshot")),
                        ['controller' => 'headshots', 'action' => 'download', $headshot->id],
                        ['escape' => false, 'class' => 'btn btn-outline-info w-100 rounded-0 btn-sm']
                    ) ?>
                <?php endif; ?>
                <?php endforeach; ?>
                </td>
                <td class="pb-4" ><h4><?= h($user->print_name) ?>
                <?= $this->Html->link(
                    $this->Pretty->iconDL(__("Bio")),
                    ['controller' => 'bios', 'action' => 'download', $user->_joinData->id],
                    ['escape' => false, 'class' => 'btn btn-outline-info btn-sm']
                ) ?>
                </h4>
                <?= $user->_joinData->text ?>
                </td>
                

            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>


<?= $this->Pretty->helpMeStart(__('View Purpose')); ?>

<p><?= __('This shows a purpose\'s details, and any associated bios / headshots') ?></p>
<?= $this->Pretty->helpMeEnd(); ?>
