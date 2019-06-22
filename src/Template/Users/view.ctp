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



<div class="related">
    <a name="messages"></a>
    <div class="column large-12">
    <h4 class="subheader"><?= __('Biographies') ?></h4>
    <?php if (!empty($user->bios)): ?>
    <table class="table table-striped">
        <tr>
            <th><?= __('Created At') ?></th>
            <th><?= __('Message') ?></th>
            
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
        <?php foreach ($user->messages as $messages): ?>
        <tr>
            <td><?= $messages->created_at->i18nFormat(null, $tz); ?></td>
            <td><?= h($messages->note) ?></td>
            

            <td class="actions">
                 <?= $this->Form->postLink(
                    $this->Pretty->iconDelete($messages->id),
                    ['controller' => 'Messages', 'action' => 'delete', $messages->id],
                    ['escape' => false, 'confirm' => __('Are you sure you want to delete # {0}?', $messages->id)]
                ) ?>
            </td>
        </tr>

        <?php endforeach; ?>
    </table>
    <?php endif; ?>
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

<h4><?= __('Permissions') ?></h4>
<p><?= __('These lists show the shows that the current user has permissions on. Permissions in TDTracX are on a per-show basis, granting permission on one show does not grant it on any other show.') ?></p>
<table class="table table-condensed helptable">
<?= $this->Html->tableCells([
    [[__("Budget User"), ['class' => 'info']],          __("Budget Users have the ability to add, edit, and delete budget items from the show.")],
    [[__("Payroll Admin"), ['class' => 'danger']],      __("Payroll admin's have the ability to add, edit, and delete payroll items for any \"Payroll User\" of the show.  Most useful for group supervisors that do not need full system administrator access.  Payroll admin's may also view the payroll report from the show. System administrators can not automatically add payroll items, although they may view any payroll report from any show.")],
    [[__("Payroll User"), ['class' => 'success']],      __("Payroll users may add payroll items to the show.  They may edit or delete those payroll hours that have not yet been marked as \"paid\". Only payroll users appear on the payroll report for the show.")],
]); ?>
</table>

<h4><?= __('Messages') ?></h4>
<p><?= __('Finally, if there are any messages waiting for the user, they are shown at the bottom of this display, with a delete button.  At this time, there is very little internal messaging used, preferring e-mail to the internal system.') ?></p>
<?= $this->Pretty->helpMeEnd(); ?>