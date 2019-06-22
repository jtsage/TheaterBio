<div class="users form large-10 medium-9 columns">
    <?= $this->Form->create($user, ['data-toggle' => 'validator', 'autocomplete' => 'off']) ?>
    <fieldset>
        <legend><?= __('Edit Your Account') ?>: <?= $user->username; ?></legend>
        <?php
            echo $this->Form->input('first', ['label' => __("First Name")]);
            echo $this->Form->input('last', ['label' => __("Last Name")]);
            echo $this->Form->input('print_name', ['label' => __("Print Name")]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Save'), ['class' => 'btn-default']) ?>
    <?= $this->Form->end() ?>
</div>

<?= $this->Pretty->helpMeStart(__('Edit Your Account')); ?>
<p><?= __('This display allows you to edit your account details.  To change your login e-mail, notification settings or access level, you must contact your system administrator.') ?></p>
<table class="table table-condensed helptable">
<?= $this->Html->tableCells([
    [__("First Name"),      __("User's first name")],
    [__("Last Name"),       __("User's last name")],
    [__("Print Name"),    __("User's print name")]
]); ?>
</table>
<?= $this->Pretty->helpMeEnd(); ?>