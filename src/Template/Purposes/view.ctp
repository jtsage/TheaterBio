<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Purpose $purpose
 */
?>
<div class="purposes view large-9 medium-8 columns content">
    <h3><?= h($purpose->name) ?>
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
                <td style="width:150px">
                <?php foreach ( $user->headshots as $headshot ): ?>
                <?php if ( $headshot->purpose_id == $purpose->id ) : ?>
                    <img src="/headshots/<?= $headshot->file ?>" style="width: 150px; height:auto;">
                <?php endif; ?>
                <?php endforeach; ?>
                </td>
                <td class="pb-4" ><h4><?= h($user->print_name) ?></h4>
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
