<div class="users view large-10 medium-9 columns">
    <h3><?= h($user->first) . " " . h($user->last) ?>
    <div class='btn-group'>
    <?= $this->Html->link(
        $this->Pretty->iconEdit($user->username),
        ['action' => 'edit', $user->id],
        ['escape' => false, 'class' => 'btn btn-outline-dark btn-sm']
    ) ?>
    <?= $this->Html->link(
        $this->Pretty->iconLock($user->username),
        ['action' => 'changepass', $user->id],
        ['escape' => false, 'class' => 'btn btn-outline-dark btn-sm']
    ) ?>
    </div>
    </h3>
    <div class="row">
        <div class="col-md-4">
            <h4><span class="badge badge-primary"><?= __('Username') ?></span></h4>
            <p><?= h($user->username) ?></p>
            <h4><span class="badge badge-primary"><?= __('Full Name') ?></span></h4>
            <p><?= h($user->first) ?> <?= h($user->last) ?></p>
            <h4><span class="badge badge-info"><?= __('Print Name') ?></span></h4>
            <p><?= h($user->print_name) ?></p>
        </div>
        <div class="col-md-4">
            <h4><span class="badge badge-warning"><?= __('Last Login At') ?></span></h4>
            <p><?= $user->last_login_at->i18nFormat(null, $tz); ?></p>
            <h4><span class="badge badge-warning"><?= __('User Created At') ?></span></h4>
            <p><?= $user->created_at->i18nFormat(null, $tz); ?></p>
            <h4><span class="badge badge-warning"><?= __('Last Update At') ?></span></h4>
            <p><?= $user->updated_at->i18nFormat(null, $tz); ?></p>
        </div>
        <div class="col-md-4">
            <h4><span class="badge badge-success"><?= __('Active User?') ?></span></h4>
            <p><?= $this->Bool->prefYes($user->is_active) ?></p>
            <h4><span class="badge badge-success"><?= __('Expired Password?') ?></span></h4>
            <p><?= $this->Bool->prefNo($user->is_password_expired); ?></p>
            <h4><span class="badge badge-success"><?= __('E-Mail Verified?') ?></span></h4>
            <p><?= $this->Bool->prefYes($user->is_verified); ?></p>
            <h4><span class="badge badge-success"><?= __('Administrator?') ?></span></h4>
            <p><?= $this->Bool->prefNo($user->is_admin); ?></p>
        </div>
    </div>
</div>




<?= $this->Pretty->helpMeStart(__('View User Details')); ?>

<p><?= __('This display shows details of the user record, along with the currently assigned permissions') ?></p>
<p><?= __('Near the user\'s full name, you will see two buttons:') ?></p>
<?= $this->Html->nestedList([
        $this->Pretty->helpButton('pencil', 'default', __('Pencil Button'), __('Edit the user')),
        $this->Pretty->helpButton('lock', 'default', __('Lock Button'), __('Change the user\'s password'))
    ], ['class' => 'list-group'], ['class' => 'list-group-item']
); ?>
<?= $this->Pretty->helpMeEnd(); ?>