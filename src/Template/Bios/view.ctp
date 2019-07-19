<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Bio $bio
 */
?>
<div class="bios view large-9 medium-8 columns content">
    <h3><?= h($bio->user->print_name) . " - " . $bio->role . " - " . $bio->purpose->name ?>
        <?= $this->Html->link(
            $this->Pretty->iconEdit($bio->user->print_name . " - " . $bio->purpose->name),
            ['action' => 'edit', $bio->id],
            ['escape' => false, 'class' => 'btn btn-outline-dark btn-sm']
        ) ?>
    </h3>
    <table class="table">
        <tr>
            <th scope="row"><?= __('Last Edit') ?></th>
            <td><?= $bio->updated_at->i18nFormat(\IntlDateFormatter::FULL, $tz); ?></td>
        </tr>
    </table>
    <?= $bio->text ?>

    
</div>



<?= $this->Pretty->helpMeStart(__('View Bio')); ?>
<p><?= __("This display shows your current bio."); ?></p>
<p><?= __("Please note, you may only have one bio per show."); ?></p>

<?= $this->Pretty->helpMeEnd(); ?>
