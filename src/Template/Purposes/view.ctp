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
                <td class="border-bottom p-2 align-top pt-3" style="width:190px">
                <?php foreach ( $user->photos as $photo ): ?>
                    <img src="<?= preg_replace("/webroot/", "", $photo->dir) . "/" . $photo->file ?>" class="img-fluid">
                    <?= $this->Html->link(
                        $this->Pretty->iconDL(__("Headshot")),
                        ['controller' => 'photos', 'action' => 'download', $photo->id],
                        ['escape' => false, 'class' => 'btn btn-outline-info w-100 rounded-0 btn-sm']
                    ) ?>
                <?php endforeach; ?>
                </td>
                <td class="border-bottom pb-4 pt-3" ><h4><?= h($user->print_name) ?>
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
