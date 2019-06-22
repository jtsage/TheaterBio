<div class="users form large-10 medium-9 columns">
    <?= $this->Form->create($user, ['data-toggle' => 'validator', 'autocomplete' => 'off']) ?>
    <fieldset>
        <legend><?= __('Add User') ?></legend>
        <?php
            echo $this->Form->input('username', ['label' => __("E-Mail Address")]);
            echo $this->Form->input('password', ['label' => 'Password', 'data-minlength' => 6]);
            echo $this->Form->input('first', ['label' => __("First Name")]);
            echo $this->Form->input('last', ['label' => __("Last Name")]);
            echo $this->Form->input('print_name', ['label' => __("Print Name")]);
        ?>
        <?php
            function doEet($matches) {
                if ( isset(CINFO[$matches[1]]) ) {
                    return CINFO[$matches[1]];
                }
                return "!!Variable-Not-Defined!!";
            }
            $welcomeMailText = CINFO['welcomemail'];
            $welcomeMailText = preg_replace_callback("/{{(\w+)}}/m", "doEet", $welcomeMailText);
        ?>
        <div class="form-group">
            <label for="welcomeEmail">Welcome E-Mail</label>
            <textarea class="form-control" id="welcomeEmail" name="welcomeEmail" rows="12"><?= $welcomeMailText ?></textarea>
        </div>
        <?= $this->Pretty->check('welcomeEmailSend', 1, [
                'label-width' => '200',
                'label-text' => __('Send E-Mail'),
                'on-text' => __('YES'),
                'off-text' => __('NO'),
                'on-color' => 'success',
                'off-color' => 'danger'
        ]); ?>
        <?= $this->Pretty->check('welcomeEmailSendCopy', 1, [
                'label-width' => '200',
                'label-text' => __('Send E-Mail (copy to admin)'),
                'on-text' => __('YES'),
                'off-text' => __('NO'),
                'on-color' => 'success',
                'off-color' => 'danger'
        ]); ?>
    </fieldset>
    <?= $this->Form->button(__('Add'), ['class' => 'btn-default']) ?>
    <?= $this->Form->end() ?>
</div>

<?= $this->Pretty->helpMeStart(__('Add User')); ?>

<p><?= __('This display allows you to add a new user in the system. This display is only available to system administrators.') ?></p>
<table class="table table-condensed helptable">
<?= $this->Html->tableCells([
    [__("E-Mail Address"),  __("User's e-mail address, used for login and notifications.")],
    [__("Password"),        __("User's initial password.")],
    [__("First Name"),      __("User's first name")],
    [__("Last Name"),       __("User's last name")],
    [__("Phone Number"),    __("User's 10-digit phone number, no punctuation.")],
    [__("Is Salary"),          __("User is a salary employee, mark hours paid by default")],
    [__("Time Zone"),       __("User's time zone. Defaults to EST/EDT (USA).")],


]); ?>
</table>

<?= $this->Pretty->helpMeEnd(); ?>