<div class="payrolls form large-10 medium-9 columns">
    <?= $this->Form->create($payroll, ['data-toggle' => 'validator']) ?>
    <fieldset>
        <legend><?= __('Edit Payroll') ?></legend>
        <?php
            echo $this->Form->input('show_id', ['readonly' => 'readonly', 'label' => __('Show Name'), 'options' => $shows]);
            echo $this->Form->input('user_id', ['readonly' => 'readonly', 'label' => __('User'), 'options' => $users]);
            echo $this->Form->input('notes', ['label' => __('Notes')]);
            echo $this->Pretty->datePicker('date_worked', __('Date Worked'), $payroll->date_worked);
            echo $this->Pretty->clockPicker('start_time', __('Start Time'), $payroll->start_time->i18nFormat('H:mm', 'UTC'));
            echo $this->Pretty->clockPicker('end_time', __('End Time'), $payroll->end_time->i18nFormat('H:mm', 'UTC'));
            if ( isset($isAdmin) && $isAdmin ) {
                echo $this->Pretty->check('is_paid', $payroll->is_paid, [
                    'label-width' => '100',
                    'label-text' => __('Is Paid'),
                    'on-text' => __('YES'),
                    'off-text' => __('NO'),
                    'on-color' => 'success',
                    'off-color' => 'danger'
                ]);
            }
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
