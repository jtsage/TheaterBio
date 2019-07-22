<h2>Your Print Name</h2>
<h3 class="text-center"><?= h($user->print_name) ?></h3>

<h2 class="mt-5 mb-4">Current Bio/Headshot Pairs</h2>
<table cellpadding="0" cellspacing="0">
    <?php foreach ($user->bios as $bio): ?>
    <tr>
        <td class="p-2 align-top" style="width:190px">
        <?php foreach ( $user->photos as $photo ): ?>
            <img src="<?= preg_replace("/webroot/", "", $photo->dir) . "/" . $photo->file ?>" class="img-fluid">
        <?php endforeach; ?>
        </td>
        <td class="pb-4" >
        <h4><?= h($bio->purpose->name) ?></h4>
        <h5><?= h($bio->purpose->description) ?></h5>
        <?= $bio->text ?>
        </td>
        

    </tr>
    <?php endforeach; ?>
</table> 


<?= $this->Pretty->helpMeStart(__('Dashboard')); ?>
<p><?= __("This display shows a quick dashboard of your biographies and headshots.") ?></p>
<?= $this->Pretty->helpMeEnd(); ?>
