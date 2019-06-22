<h3>Login</h3>
<?= $this->Form->create() ?>
<?= $this->Form->input('username', ['label' => __("E-Mail Address") ]) ?>
<?= $this->Form->input('password', ['label' => __("Password") ]) ?>
<?= $this->Form->button(__('Login'), ['class' => 'w-100']) ?>
<?= $this->Form->end() ?><br />
<?= $this->Form->postButton(__('Forgot Password'), "/users/forgot_password", ['class' => 'w-100 btn-danger']) ?>
<?= $this->Html->link(__('Register as New'), "/users/register", ['class' => 'w-100 mt-2 btn btn-warning']) ?>

<?= $this->Pretty->helpMeStart(__('Welcome to TDTracX')); ?>
<p><?= __("Please login with the e-mail address and password you were issued.  Lost passwords may be retrieved by contacting your system administrator."); ?></p>
<p><em><?= __("This is a private computing system; its use is restricted to authorized individuals. Actual or attempted unauthorized use of this computer system will result in criminal and/or civil prosecution. We reserve the right to view, monitor and record activity on the system without notice or permission. Any information obtained by monitoring, reviewing or recording is subject to review by law enforcement organizations in connection with the investigation or prosecution of possible criminal activity on the system. If you are not an authorized user of this system or do not consent to continued monitoring, exit the system at this time."); ?></em></p>
<?= $this->Pretty->helpMeEnd(); ?>