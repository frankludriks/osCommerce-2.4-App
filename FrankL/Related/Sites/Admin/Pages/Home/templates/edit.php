<?php
use OSC\OM\HTML;

require(__DIR__ . '/template_top.php');

  $products_id_slave = '';
  $products_id_master = '';
  $products_id_view = '';
  
  $products_id_view = (isset($_GET['products_id_view']) && $_GET['products_id_view'] != 0) ? (int)$_GET['products_id_view'] : null;
  $products_id_master = (isset($_GET['products_id_master']) && $_GET['products_id_master'] != 0) ? (int)$_GET['products_id_master'] : null;
  $pop_order_id = (isset($_GET['pop_order_id']) && $_GET['pop_order_id'] != 0) ? (int)$_GET['pop_order_id'] : null;
  $page = (isset($_GET['page']) && $_GET['page'] != 0) ? (int)$_GET['page'] : null;
  if ($products_id_master) { 
    $products_id_view = $products_id_master; 
  }
  
  if (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_MODEL == 'True' && OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_NAME == 'True') {
		$MenuOrderBy = ' order by pd.products_name, p.products_model';
  } elseif (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_NAME == 'True') {
		$MenuOrderBy = ' order by pd.products_name';
  } elseif (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_MODEL == 'True') {
		$MenuOrderBy = ' order by p.products_model';
  }
?>

<div class="row">
  <div class="col-sm-12">
    <div class="panel panel-primary">
      <div class="panel-heading"><?= $OSCOM_Related->getDef('app_related_edit_title'); ?></div>
      <div class="panel-body">
	  <form class="form-inline" name="editRelated" method="post">
		<div class="form-group col-sm-4">
		  <label for="products_id_master"><?php echo $OSCOM_Related->getDef('app_related_select_master'); ?>:</label>
		    <select class="form-control" name="products_id_master">		
			
			<?php
			
			echo 'this-> ' . $OSCOM_Language->getId();
    			$related_what = "p.products_id";    			
    			$related_from =  " from :table_products p";
    			$related_where =  " where p.products_id > '0'";
    			if (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_MODEL == 'True') {
    				$related_what .= ", p.products_model";
    			}
    			if (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_NAME == 'True') {
					$related_what .= ", pd.products_name";
    				$related_from .=  ", :table_products_description pd";
    				$related_where .=  " and p.products_id = pd.products_id";
    				$related_where .=  " and pd.language_id = :languages_id";
    			}
    			
				$related_query_raw = "select distinct " . $related_what . $related_from . $related_where . ' ' . $MenuOrderBy;
									
				$Qrelated_select = $OSCOM_Related->db->prepare($related_query_raw);
				$Qrelated_select->bindInt(':languages_id', '1'); 
				$Qrelated_select->execute();

				if (!$products_id_master) { 
					$products_id_master = $products_id_view; 
				}
				

				while ($Qrelated_select->fetch()) {	
					$model = (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_MODEL == 'True')?$Qrelated_select->value('products_model') . OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MODEL_SEPARATOR:'';
					$name = (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_NAME == 'True')?$Qrelated_select->value('products_name'):'';
					$product_name = (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MAX_NAME_LENGTH == '0')?$name:substr($name, 0, OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MAX_NAME_LENGTH);
					echo '<option id="c' . $Qrelated_select->value('products_id') . '" value="' . $Qrelated_select->valueInt('products_id') . '"' . (($_GET['products_id_master'] == $Qrelated_select->value('products_id'))? ' selected' : '') . '>' . $model . $product_name . '</option>';
											
				}
?>
            </select>
		</div>
		<div class="form-group col-sm-4">
		  <label for="products_id_slave"><?php echo $OSCOM_Related->getDef('app_related_select_slave'); ?>:</label>
		    <select class="form-control" name="products_id_slave">
			<?php
    			$related_what = "p.products_id";    			
    			$related_from =  " from :table_products p";
    			$related_where =  " where p.products_id > '0'";
    			if (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_MODEL == 'True') {
    				$related_what .= ", p.products_model";
    			}
    			if (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_NAME == 'True') {
    				$related_what .= ", pd.products_name";
    				$related_from .=  ", :table_products_description pd";
    				$related_where .=  " and p.products_id = pd.products_id";
    				$related_where .=  " and pd.language_id = :languages_id";
    			}
    			
    			$related_query_raw = "select distinct " . $related_what . $related_from . $related_where . ' ' . $MenuOrderBy;
    			$Qrelated_select = $OSCOM_Related->db->prepare($related_query_raw);
				$Qrelated_select->bindInt(':languages_id', $OSCOM_Language->getId()); 
				$Qrelated_select->execute();
										
				while ($Qrelated_select->fetch()) {
					$model = (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_MODEL == 'True')?$Qrelated_select->value('products_model') . OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MODEL_SEPARATOR:'';
					$name = (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_NAME == 'True')?$Qrelated_select->value('products_name'):'';
					$product_name = (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MAX_NAME_LENGTH == '0')?$name:substr($name, 0, OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MAX_NAME_LENGTH);
					echo '<option id="d' . $Qrelated_select->value('products_id') . '" value="' . $Qrelated_select->value('products_id') . '"' . (($_GET['products_id_slave'] == $Qrelated_select->value('products_id'))? ' selected' : '') . '>' . $model . $product_name . '</option>';
				}
			?>
            </select>
		  </div>
		  <div class="form-group col-sm-2">
			<label for="pop_order_id"><?php echo $OSCOM_Related->getDef('app_related_sort_order'); ?>:</label>
			  <?php echo HTML::inputField('pop_order_id', '', 'id="pop_order_id"') . HTML::hiddenField('page', $_GET['page']) . HTML::hiddenField('pop_id', $_GET['pop_id']) . HTML::hiddenField('products_name_master', $_GET['products_name_master']) . HTML::hiddenField('products_name_slave', $_GET['products_name_slave']); 
			  
			  
			  ?>
		  </div>
		  <div class="form-group col-sm-2"><?= HTML::button($OSCOM_Related->getDef('app_related_button_save'), null, null, array('params' => 'formaction="' . $OSCOM_Related->link('Admin&Update') . '"'), 'btn-success'); ?> <?= HTML::button($OSCOM_Related->getDef('app_related_button_cancel'), null, null, array('params' => 'formaction="' . $OSCOM_Related->link('Admin&products_id_view=' . $products_id_view) . '"'), 'btn-default'); ?>
		  
		  </div>	</form>
        
      </div>
    </div>
  </div>
</div>

<?php
require(__DIR__ . '/template_bottom.php');
?>