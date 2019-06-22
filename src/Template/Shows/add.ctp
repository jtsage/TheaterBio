<div class="shows form large-10 medium-9 columns">
    <?= $this->Form->create($show, ['data-toggle' => 'validator']) ?>
    <fieldset>
        <legend><?= __('Add Show') ?></legend>
        <?php
            echo $this->Form->input('name', ['label' => __('Name'), 'data-minlength' => 5]);
            echo $this->Form->input('location', ['label' => __('Location'), 'data-minlength' => 5]);
            echo $this->Pretty->datePicker('end_date', __('End Date'));
            echo $this->Form->input('sec_string', ['label' => ('iCal Identifier'), 'data-minlength' => 32]);
        ?>
            <a href="#" onClick="$('#sec-string').val(guid())" class="btn btn-danger">Generate ICS Indentifier</a><br /><br />
    </fieldset>
    <?= $this->Form->button(__('Add'), ['class' => 'btn-default']) ?>
    <?= $this->Form->end() ?>
</div>


<script type="text/javascript">
function guid() {
  function s4() {
    return Math.floor((1 + Math.random()) * 0x10000)
      .toString(16)
      .substring(1);
  }
  return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
}
</script>

<?= $this->Pretty->helpMeStart(__('Add Show')); ?>
<p><?= __("This display allows you to add a new show."); ?></p>
<table class="table table-condensed helptable">
<?= $this->Html->tableCells([
    [__("Name"),        __("Name of the show")],
    [__("Location"),    __("Location of the show")],
    [__("End Date"),    __("End date of the show")]
]); ?>
</table>
<?= $this->Pretty->helpMeEnd(); ?>
