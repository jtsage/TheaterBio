<div class="users form large-10 medium-9 columns">
    <?= $this->Form->create($user, ['data-toggle' => 'validator', 'autocomplete' => 'off']) ?>
    <fieldset>
        <legend><?= __('Edit Your Account') ?>: <?= $user->username; ?></legend>
        <?php
            echo $this->Form->input('first', ['label' => __("First Name")]);
            echo $this->Form->input('last', ['label' => __("Last Name")]);
            echo $this->Form->input('phone', ['label' => __("Phone Number")]);
        ?>
        <div class="form-group"><label class="control-label"><?= __("Time Zone") ?></label>
        <?php
            echo $this->Form->select(
                'time_zone',
                array_combine(timezone_identifiers_list(), timezone_identifiers_list()),
                [ 'default' => 'America/Detroit', 'class' => 'form-control' ]
            );
        ?>
        </div>
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
    [__("Phone Number"),    __("User's 10-digit phone number, no punctuation.")],
    [__("Time Zone"),       __("User's time zone. Defaults to EST/EDT (USA).")]
]); ?>
</table>
<?= $this->Pretty->helpMeEnd(); ?>