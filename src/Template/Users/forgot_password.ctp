<h3>Send Password Reset E-Mail</h3>
<?= $this->Form->create() ?>
<?= $this->Form->input('username', ['label' => __("E-Mail Address") ]) ?>
<?= $this->Form->button(__('Send Reset E-Mail'), ['class' => 'btn-default']) ?>
<?= $this->Form->end() ?><br />

<?= $this->Pretty->helpMeStart(__('Welcome to TDTracX')); ?>
<p><?= __("This form can be used if you have forgotten your password to send reset instructions."); ?></p>
<p><em><?= __("This is a private computing system; its use is restricted to authorized individuals. Actual or attempted unauthorized use of this computer system will result in criminal and/or civil prosecution. We reserve the right to view, monitor and record activity on the system without notice or permission. Any information obtained by monitoring, reviewing or recording is subject to review by law enforcement organizations in connection with the investigation or prosecution of possible criminal activity on the system. If you are not an authorized user of this system or do not consent to continued monitoring, exit the system at this time."); ?></em></p>
<?= $this->Pretty->helpMeEnd(); ?>