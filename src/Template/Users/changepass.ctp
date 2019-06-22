<div class="users form large-10 medium-9 columns">
    <?= $this->Form->create($user, ['data-toggle' => 'validator', 'autocomplete' => 'off']) ?>
    <fieldset>
        <legend><?= __('Change Password') ?></legend>
        <?php
            echo $this->Form->input('password', ['label' => __("New Password"), 'data-minlength' => 6, 'value' => '']);
        ?>
        <input type="hidden" name="is_password_expired" value="0">
    </fieldset>
    <?= $this->Form->button(__('Change Password'), ['class' => 'btn-default']) ?>
    <?= $this->Form->end() ?>
</div>

<?= $this->Pretty->helpMeStart(__('Change Password')); ?>
<p><?= __("This display allows you change your password. If you forget your password, you can reset it at the login screen via e-mail"); ?></p>
<?= $this->Pretty->helpMeEnd(); ?>