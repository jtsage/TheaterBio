<div class="shows view large-10 medium-9 columns">
    <h3>
        <?= h($show->name) . " " . __("Permissions") ?>
    </h3>
    <div class="row">
        <div class="col-md-6">
            <h4><span class="badge badge-primary"><?= __('Name') ?></span></h4>
            <p><?= h($show->name) ?></p>
            <h4><span class="badge badge-primary"><?= __('Location') ?></span></h4>
            <p><?= h($show->location) ?></p>
        </div>
        <div class="col-md-6">
            <h4><span class="badge badge-success"><?= __('Active Show?') ?></span></h4>
            <p><?= $this->Bool->prefYes($show->is_active) ?></p>
            <h4><span class="badge badge-success"><?= __('End Date') ?></span></h4>
            <p><?= $show->end_date->i18nFormat([\IntlDateFormatter::MEDIUM, \IntlDateFormatter::NONE], 'UTC'); ?></p>
        </div>
    </div>
</div>

<div class="related">
    <div class="column large-12">
    <h4 class="subheader"><?= __('User Permissions') ?></h4>
    
    <?= $this->Form->create($show) ?>
    <div id="tableTop">
    <table class="my-0 table table-bordered">
        <thead>
            <?= $this->Html->tableHeaders([
                __("Full Name"),

                [ __("Budget User") . ' ' . "<div class='btn-group'>" .
                    $this->Pretty->jqButton(
                        'toggle-on',
                        'default',
                        'buserAllOn',
                        'toggleState',
                        __('Toggle All YES') ) .
                    $this->Pretty->jqButton(
                        'toggle-off',
                        'default',
                        'buserAllOff',
                        'toggleState',
                        __('Toggle All NO') ) .
                    "</div>"
                    => ['class' => 'info text-center' ]
                ],
                
                [ __("Payroll Admin") . ' ' . "<div class='btn-group'>" .
                    $this->Pretty->jqButton(
                        'toggle-on',
                        'default',
                        'padminAllOn',
                        'toggleState',
                        __('Toggle All YES') ) .
                    $this->Pretty->jqButton(
                        'toggle-off',
                        'default',
                        'padminAllOff',
                        'toggleState',
                        __('Toggle All NO') ) .
                    "</div>"
                    => ['class' => 'danger text-center' ]
                ],

                [ __("Payroll User") . ' ' . "<div class='btn-group'>" .
                    $this->Pretty->jqButton(
                        'toggle-on',
                        'default',
                        'paidAllOn',
                        'toggleState',
                        __('Toggle All YES') ) .
                    $this->Pretty->jqButton(
                        'toggle-off',
                        'default',
                        'paidAllOff',
                        'toggleState',
                        __('Toggle All NO') ) .
                    "</div>"
                    => ['class' => 'success text-center' ]
                ],

                [ __("Task Admin") . ' ' . "<div class='btn-group'>" .
                    $this->Pretty->jqButton(
                        'toggle-on',
                        'default',
                        'tadmAllOn',
                        'toggleState',
                        __('Toggle All YES') ) .
                    $this->Pretty->jqButton(
                        'toggle-off',
                        'default',
                        'tadmAllOff',
                        'toggleState',
                        __('Toggle All NO') ) .
                    "</div>"
                    => ['class' => 'warning text-center' ]
                ],

                [ __("Task User") . ' ' . "<div class='btn-group'>" .
                    $this->Pretty->jqButton(
                        'toggle-on',
                        'default',
                        'taskAllOn',
                        'toggleState',
                        __('Toggle All YES') ) .
                    $this->Pretty->jqButton(
                        'toggle-off',
                        'default',
                        'taskAllOff',
                        'toggleState',
                        __('Toggle All NO') ) .
                    "</div>"
                    => ['class' => 'warning text-center' ]
                ],

                [ __("Calendar User") . ' ' . "<div class='btn-group'>" .
                    $this->Pretty->jqButton(
                        'toggle-on',
                        'default',
                        'calAllOn',
                        'toggleState',
                        __('Toggle All YES') ) .
                    $this->Pretty->jqButton(
                        'toggle-off',
                        'default',
                        'calAllOff',
                        'toggleState',
                        __('Toggle All NO') ) .
                    "</div>"
                    => ['class' => 'active text-center' ]
                ],
            ]); ?>
        </thead>
    </table>
</div>
<div id="tableBod">
    <table class="table table-bordered">
        <tbody>
        <?php
            foreach ( $users as $user ) {
                 echo $this->Html->tableCells([
                    [
                        "<input type='hidden' name='users[]' value='" . $user->id . "'>" . $user->first . " " . $user->last,
                        [
                            $this->Pretty->check(
                                'budget[' . $user->id . ']',
                                $user->perms['is_budget'],
                                [
                                    'on-text' => __('YES'),
                                    'off-text' => __('NO'),
                                    'on-color' => 'success',
                                    'off-color' => 'danger'
                                ]
                            ), ['class' => 'text-center']
                        ],
                        [
                            $this->Pretty->check(
                                'padmin[' . $user->id . ']',
                                $user->perms['is_pay_admin'],
                                [
                                    'on-text' => __('YES'),
                                    'off-text' => __('NO'),
                                    'on-color' => 'success',
                                    'off-color' => 'danger'
                                ]
                            ), ['class' => 'text-center']
                        ],
                        [
                            $this->Pretty->check(
                                'paid[' . $user->id . ']',
                                $user->perms['is_paid'],
                                [
                                    'on-text' => __('YES'),
                                    'off-text' => __('NO'),
                                    'on-color' => 'success',
                                    'off-color' => 'danger'
                                ]
                            ), ['class' => 'text-center']
                        ],
                        [
                            $this->Pretty->check(
                                'task_admin[' . $user->id . ']',
                                $user->perms['is_task_admin'],
                                [
                                    'on-text' => __('YES'),
                                    'off-text' => __('NO'),
                                    'on-color' => 'success',
                                    'off-color' => 'danger'
                                ]
                            ), ['class' => 'text-center']
                        ],
                        [
                            $this->Pretty->check(
                                'task_user[' . $user->id . ']',
                                $user->perms['is_task_user'],
                                [
                                    'on-text' => __('YES'),
                                    'off-text' => __('NO'),
                                    'on-color' => 'success',
                                    'off-color' => 'danger'
                                ]
                            ), ['class' => 'text-center']
                        ],
                        [
                            $this->Pretty->check(
                                'cal[' . $user->id . ']',
                                $user->perms['is_cal'],
                                [
                                    'on-text' => __('YES'),
                                    'off-text' => __('NO'),
                                    'on-color' => 'success',
                                    'off-color' => 'danger'
                                ]
                            ), ['class' => 'text-center']
                        ],
                    ]
                ]);
            }
        ?>
        </tbody>
    </table>
</div>

    <?= $this->Form->button(__('Save'), ['class' => 'btn-default']) ?>
    <?= $this->Form->end() ?>
    </div>
</div>


<?= $this->Pretty->helpMeStart(__('Edit Show Permissions')); ?>
<p><?= __("This display allows you to edit the show's permissions for each active user.") ?></p>
<p><?= __("Near each permission type, you will see two buttons:"); ?></p>
<?= $this->Html->nestedList([
        $this->Pretty->helpButton('toggle-on', 'default', _('Toggle On Button'), _('Toggle this permission ON for all active users')),
        $this->Pretty->helpButton('toggle-off', 'default', _('Toggle Off Button'), _('Toggle this permission OFF for all active users'))
    ], ['class' => 'list-group'], ['class' => 'list-group-item']
); ?>
<table class="table table-condensed helptable">
<?= $this->Html->tableCells([
    [[__("Budget User"), ['class' => 'info']],          __("Budget Users have the ability to add, edit, and delete budget items from the show.")],
    [[__("Payroll Admin"), ['class' => 'danger']],      __("Payroll admin's have the ability to add, edit, and delete payroll items for any \"Payroll User\" of the show.  Most useful for group supervisors that do not need full system administrator access.  Payroll admin's may also view the payroll report from the show. System administrators can not automatically add payroll items, although they may view any payroll report from any show.")],
    [[__("Payroll User"), ['class' => 'success']],      __("Payroll users may add payroll items to the show.  They may edit or delete those payroll hours that have not yet been marked as \"paid\". Only payroll users appear on the payroll report for the show.")],
    [[__("Task Admin"), ['class' => 'success']],      __("Task admins can accept, edit, and mark tasks finished.")],
    [[__("Task User"), ['class' => 'success']],      __("Task users can add tasks for later acceptance and action. They can edit their own tasks.")],
]); ?>
</table>

<?= $this->Pretty->helpMeEnd(); ?>