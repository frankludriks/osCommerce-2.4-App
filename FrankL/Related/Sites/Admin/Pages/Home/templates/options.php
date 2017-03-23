<?php
use OSC\OM\HTML;
use OSC\OM\Registry;

$OSCOM_Page = Registry::get('Site')->getPage();
require(__DIR__ . '/template_top.php');
?>

<form name="RelatedCredentials" action="<?= $OSCOM_Related->link('Options&Process'); ?>" method="post">

<div class="panel panel-warning">
  <div class="panel-heading">
    <?= $OSCOM_Related->getDef('app_related_options_title'); ?>
  </div>

  <div class="panel-body">
    <div class="row">
      <div class="col-sm-6">
        <div class="btn-group" data-toggle="buttons">
          <label for="use_model"><?= $OSCOM_Related->getDef('app_related_options_use_model'); ?></label><br>
          <?php echo '  <label class="btn btn-info' . (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_MODEL == 'True' ? ' active' : '') . '">' . HTML::radioField('use_model', 'True', (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_MODEL == 'True')) . $OSCOM_Related->getDef('app_related_options_true') . '</label>' .
          '  <label class="btn btn-info' . (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_MODEL == 'False' ? ' active' : '') . '">' . HTML::radioField('use_model', 'False', (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_MODEL == 'False')) . $OSCOM_Related->getDef('app_related_options_false') . '</label>';
		  ?>
		</div>
		<div class="help-block"><?= $OSCOM_Related->getDef('app_related_options_use_model_desc'); ?></div>

        <div class="btn-group" data-toggle="buttons">
          <label for="use_name"><?= $OSCOM_Related->getDef('app_related_options_use_name'); ?></label><br>
          <?php echo '  <label class="btn btn-info' . (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_NAME == 'True' ? ' active' : '') . '">' . HTML::radioField('use_name', 'True', (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_NAME == 'True')) . $OSCOM_Related->getDef('app_related_options_true') . '</label>' .
          '  <label class="btn btn-info' . (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_NAME == 'False' ? ' active' : '') . '">' . HTML::radioField('use_name', 'False', (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_NAME == 'False')) . $OSCOM_Related->getDef('app_related_options_false') . '</label>';
		  ?>
		</div>
		<div class="help-block"><?= $OSCOM_Related->getDef('app_related_options_use_name_desc'); ?></div>

        <div class="form-group">
          <label for="model_separator"><?= $OSCOM_Related->getDef('app_related_options_model_separator'); ?></label>
          <?php echo HTML::inputField('model_separator', OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MODEL_SEPARATOR, 'id="model_separator"'); ?>
        </div>
		<div class="help-block"><?= $OSCOM_Related->getDef('app_related_options_model_separator_desc'); ?></div>
		
		<div class="btn-group" data-toggle="buttons">
          <label for="use_model"><?= $OSCOM_Related->getDef('app_related_options_confirm_delete'); ?></label><br>
          <?php echo '  <label class="btn btn-info' . (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_CONFIRM_DELETE == 'True' ? ' active' : '') . '">' . HTML::radioField('confirm_delete', 'True', (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_CONFIRM_DELETE == 'True')) . $OSCOM_Related->getDef('app_related_options_true') . '</label>' .
          '  <label class="btn btn-info' . (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_CONFIRM_DELETE == 'False' ? ' active' : '') . '">' . HTML::radioField('confirm_delete', 'False', (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_CONFIRM_DELETE == 'false')) . $OSCOM_Related->getDef('app_related_options_false') . '</label>';
		  ?>
		</div>
		<div class="help-block"><?= $OSCOM_Related->getDef('app_related_options_confirm_delete_desc'); ?></div>
      </div>

      <div class="col-sm-6">
        <div class="form-group">
          <label for="max_rows_list_options"><?= $OSCOM_Related->getDef('app_related_options_max_rows_list_options'); ?></label>
          <?php echo HTML::inputField('max_rows_list_options', OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MAX_ROWS_LIST_OPTIONS, 'id="max_rows_list_options"'); ?>
        </div>
		<div class="help-block"><?= $OSCOM_Related->getDef('app_related_options_max_rows_list_options_desc'); ?></div>

        <div class="form-group">
          <label for="max_name_length"><?= $OSCOM_Related->getDef('app_related_options_max_name_length'); ?></label>
          <?php echo HTML::inputField('max_name_length', OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MAX_NAME_LENGTH, 'id="max_name_length"'); ?>
        </div>
		<div class="help-block"><?= $OSCOM_Related->getDef('app_related_options_max_name_length_desc'); ?></div>

        <div class="form-group">
          <label for="max_display_length"><?= $OSCOM_Related->getDef('app_related_options_max_display_length'); ?></label>
          <?php echo HTML::inputField('max_display_length', OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MAX_DISPLAY_LENGTH, 'id="max_display_length"'); ?>
        </div>
		<div class="help-block"><?= $OSCOM_Related->getDef('app_related_options_max_display_length_desc'); ?></div>
		<div class="btn-group" data-toggle="buttons">
          <label for="insert_and_inherit"><?= $OSCOM_Related->getDef('app_related_options_insert_and_inherit'); ?></label><br>
          <?php echo '  <label class="btn btn-info' . (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_INSERT_AND_INHERIT == 'True' ? ' active' : '') . '">' . HTML::radioField('insert_and_inherit', 'True', (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_INSERT_AND_INHERIT == 'True')) . $OSCOM_Related->getDef('app_related_options_true') . '</label>' .
          '  <label class="btn btn-info' . (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_INSERT_AND_INHERIT == 'False' ? ' active' : '') . '">' . HTML::radioField('insert_and_inherit', 'False', (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_INSERT_AND_INHERIT == 'False')) . $OSCOM_Related->getDef('app_related_options_false') . '</label>';
		  ?>
		</div>
		<div class="help-block"><?= $OSCOM_Related->getDef('app_related_options_insert_and_inherit_desc'); ?></div>
      </div>
    </div>
  </div>
</div>

<p><?= HTML::button($OSCOM_Related->getDef('button_save'), null, null, null, 'btn-success'); ?></p>

</form>

<?php
require(__DIR__ . '/template_bottom.php');
?>
