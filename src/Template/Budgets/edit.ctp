<div class="budgets form large-10 medium-9 columns">
    <?= $this->Form->create($budget) ?>
    <fieldset>
        <legend><?= __('Edit Budget Item') ?></legend>
        <?php
            echo $this->Form->input('show_id', ['label' => __('Show'), 'options' => $shows]);
            echo $this->Form->input('category', ['label' => __('Budget Category'), 'autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $cat]);
            echo $this->Form->input('vendor', ['label' => __('Store or Vendor'), 'autocomplete' => 'off', 'data-provide' => 'typeahead', 'data-source' => $vend]);
            echo $this->Form->input('description', ['label' => 'Description']);
            echo $this->Pretty->money('price', __('Price'), $budget->price);
            echo $this->Pretty->datePicker('date', __('Date'), $budget->date);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit'), ['class' => 'btn-default']) ?>
    <?= $this->Form->end() ?>
</div>

<?= $this->Pretty->helpMeStart(__('Edit Budget Item')); ?>
<p><?= __("This display allows you to edit and existing budget item.") ?></p>

<table class="table table-condensed helptable">
<?= $this->Html->tableCells([
    [__("Show"),               __("locked to the current show.")],
    [__("Budget Category"),    __("A grouping category for this budget expense.")],
    [__("Store or Vendor"),    __("The Vendor this budget expense was transacted with.")],
    [__("Description"),        __("A description of the expense.")],
    [__("Price"),              __("Price, without dollar sign of the expense.")],
    [__("Date"),               __("Date of the expense.  Defaults to today.")]
]); ?>
</table>

<?= $this->Pretty->helpMeEnd(); ?>