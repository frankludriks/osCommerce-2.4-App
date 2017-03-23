<?php

  use OSC\OM\FileSystem;
  use OSC\OM\HTML;
  use OSC\OM\OSCOM;
  use OSC\OM\Registry;
  
  $OSCOM_Page = Registry::get('Site')->getPage();

  require(__DIR__ . '/template_top.php');

  if (!isset($_GET['page']) || !is_numeric($_GET['page'])) {
    $_GET['page'] = 1;
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  
  $products_id_slave = '';
  $products_id_master = '';
  $products_id_view = '';
  
  $products_id_view = (isset($_GET['products_id_view']) && $_GET['products_id_view'] != 0) ? (int)$_GET['products_id_view'] : null;
  $products_id_master = (isset($_GET['products_id_master']) && $_GET['products_id_master'] != 0) ? (int)$_GET['products_id_master'] : null;
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
<div class="text-right">
  <?= HTML::button($OSCOM_Related->getDef('app_related_button_options'), null, $OSCOM_Related->link('Options'), null, 'btn-info'); ?>
</div>
<h2><i class="fa fa-arrows-h"></i> <a href="<?= $OSCOM_Related->link('Admin'); ?>"><?= $OSCOM_Related->getDef('app_related_admin_heading_title'); ?></a></h2>

<?php
	if ( defined('OSCOM_APP_FRANKL_RELATED_STATUS') ) 
	{ // check if product info module installed
?>
<div class="relatedShowAll form-inline col-sm-6">
<?php
    		$related_what = "p.products_id";    			
  			$related_from =  " from :table_products p, :table_products_related_products pa";
    		$related_where =  " where pa.pop_products_id_master = p.products_id";
  			if (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_MODEL == 'True') {
  				$related_what .= ", p.products_model";
  			}
  			if (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_NAME == 'True') {
  				$related_what .= ", pd.products_name";
  				$related_from .=  ", :table_products_description pd";
  				$related_where .=  " and p.products_id = pd.products_id";
  				$related_where .=  " and pd.language_id = :languages_id";
    		}
			
			$Qrelated = $OSCOM_Related->db->prepare('select distinct ' . $related_what . $related_from . $related_where . $MenuOrderBy);
			$Qrelated->bindInt(':languages_id', $OSCOM_Language->getId());
			$Qrelated->execute();
    		
    		$products_array[] = array('id' => '', 
	  								  'text' => $OSCOM_Related->getDef('app_related_admin_show_all'));
									  
			while ($Qrelated->fetch()) {
				$model = (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_MODEL == 'True')? $Qrelated->value('products_model') . OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MODEL_SEPARATOR : '' ;
	  			$name = (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_NAME == 'True')? $Qrelated->value('products_name') : '' ;
	  			$products_array[] = array('id' => $Qrelated->value('products_id'),
            	    	              'text' => $model . $name );
        }
		
		echo '<label for="products_id_master">Select Product:</label>' .
				    HTML::selectField('products_id_view', $products_array, (isset($_GET['products_id_view']) ? $_GET['products_id_view'] : ''), 'onchange="document.location.href=\'' . $OSCOM_Related->link("Admin&products_id_view='+this.value+'", "&products_id_view='+this.value+'").'\'"');
?>
</div>
<table class="oscom-table table table-hover">
  <thead>
    <tr class="info">
      <th class="text-left"><?php echo $OSCOM_Related->getDef('app_related_admin_table_heading_rel_id'); ?></th>
      <th class="text-left"><?php echo ((OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_MODEL == 'True') ? $OSCOM_Related->getDef('app_related_admin_table_heading_model') . ' ' . OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MODEL_SEPARATOR . ' ' : '') . ((OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_NAME == 'True') ? $OSCOM_Related->getDef('app_related_admin_table_heading_product') : '') . ' ' . OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MODEL_SEPARATOR . ' ' . $OSCOM_Related->getDef('app_related_admin_table_heading_related'); ?></th>
      <th class="text-left"><?php echo ((OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_MODEL == 'True') ? $OSCOM_Related->getDef('app_related_admin_table_heading_model') . ' ' . OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MODEL_SEPARATOR . ' ' : '') . ((OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_NAME == 'True') ? $OSCOM_Related->getDef('app_related_admin_table_heading_product') : ''); ?></th>
	  <th class="text-center"><?php echo $OSCOM_Related->getDef('app_related_admin_table_heading_order'); ?></th>
      <th class="text-center"><?php echo $OSCOM_Related->getDef('app_related_admin_table_heading_action'); ?></th>
    </tr>
  </thead>
  <tbody>
  
    <?php
    $related_query = "select SQL_CALC_FOUND_ROWS pa.*, pd.products_id, p.products_model
                		from :table_products_related_products pa
                		left join :table_products_description pd
                		on pa.pop_products_id_master = pd.products_id
                		and pd.language_id = :languages_id
						left join :table_products p
						on p.products_id = pd.products_id";


		 if ($products_id_view) { 
          	$related_query .= " where pd.products_id = '$products_id_view'"; 
		}
          	$related_query .= "$MenuOrderBy, pa.pop_order_id, pa.pop_id";
			$related_query .= " limit :page_set_offset, :page_set_max_results";
	$Qrelated = $OSCOM_Db->prepare($related_query);
	$Qrelated->bindInt(':languages_id', $OSCOM_Language->getId());
    $Qrelated->setPageSet('20');
    $Qrelated->execute();
	

	$next_id = 1;

  	$rows = 0;
  	$mId = null;
  	$sId = null;
  	$mModel = null;
  	$sModel = null;  				
  	while ($Qrelated->fetch()) {
  		if (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_MODEL == 'True') {
  		  $mModel = $OSCOM_Related->osc_get_products_model($Qrelated->valueInt('pop_products_id_master')) . OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MODEL_SEPARATOR . ' ';
  		  $sModel = $OSCOM_Related->osc_get_products_model($Qrelated->valueInt('pop_products_id_slave')) . OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MODEL_SEPARATOR . ' ';
  		}
  		if (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_NAME == 'True') {
  		  $products_name_master = tep_get_products_name($Qrelated->valueInt('pop_products_id_master'));
  		  $products_name_slave = tep_get_products_name($Qrelated->valueInt('pop_products_id_slave'));
  		}

  		$pop_order_id = $Qrelated->valueInt('pop_order_id');
		?>
			
		
		<?php
  		$rows++;
	
	?>
    <tr>
	  <td>&nbsp;<?php echo $Qrelated->valueInt("pop_id"); ?>&nbsp;</td>
      <td>&nbsp;<?php echo $mId  ?><?php echo $mModel ?><?php echo (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MAX_DISPLAY_LENGTH== '0')?$products_name_master:substr($products_name_master, 0, OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MAX_DISPLAY_LENGTH); ?>&nbsp;</td>
      <td>&nbsp;<?php echo $sId  ?><?php echo $sModel ?><?php echo (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MAX_DISPLAY_LENGTH== '0')?$products_name_slave:substr($products_name_slave, 0, OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MAX_DISPLAY_LENGTH); ?>&nbsp;</td>
      <td  class="text-center">&nbsp;<?php echo $pop_order_id; ?>&nbsp;</td>
      <td class="text-center smallText">
		<?php 
		  echo HTML::button($OSCOM_Related->getDef('app_related_button_edit'), null, $OSCOM_Related->link('Admin&Edit&pop_id=' . $Qrelated->valueInt("pop_id") . '&page=' . $_GET['page'] .'&products_id_master=' . $Qrelated->valueInt('pop_products_id_master') . '&products_id_slave=' . $Qrelated->valueInt('pop_products_id_slave') . '&products_name_master=' . $products_name_master . '&products_name_slave=' . $products_name_slave . '&pop_order_id=' . $pop_order_id), null, 'btn-default btn-xs');
		  if (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_CONFIRM_DELETE == 'False') {
            echo '<span class="pull-right">' . HTML::button($OSCOM_Related->getDef('app_related_button_delete'), null, $OSCOM_Related->link('Admin&Remove&pop_id=' . $Qrelated->valueInt("pop_id") . '&page=' . $_GET['page'] .'&products_id_master=' . $Qrelated->valueInt('pop_products_id_master')), null, 'btn-warning btn-xs') . '</span>';
			
          } else {
            echo '<span class="pull-right">' . HTML::button($OSCOM_Related->getDef('app_related_button_delete'), null, '#', ['params' => 'data-toggle="modal" data-target="#relatedDeleteModal' . $Qrelated->valueInt("pop_id") . '"'], 'btn-warning btn-xs') . '</span>';
			?>
			<div id="relatedDeleteModal<?php echo $Qrelated->valueInt("pop_id"); ?>" class="modal" tabindex="-1" role="dialog">
			  <div class="modal-dialog" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"><?= $OSCOM_Related->getDef('app_related_dialog_related_delete_title', ['master' => $products_name_master]); ?></h4>
				  </div>
				  <div class="modal-body">
					<?= $OSCOM_Related->getDef('app_related_dialog_related_delete_body', ['slave' => $products_name_slave]); ?> 
				  </div>
				  <div class="modal-footer">
					<?= HTML::button($OSCOM_Related->getDef('app_related_button_delete'), null, $OSCOM_Related->link('Admin&Remove&pop_id=' . $Qrelated->valueInt("pop_id") . '&page=' . $_GET['page'] .'&products_id_master=' . $Qrelated->valueInt('pop_products_id_master') . '&products_name_master=' . $products_name_master . '&products_name_slave=' . $products_name_slave), null, 'btn-danger'); ?>
					<?= HTML::button($OSCOM_Related->getDef('button_cancel'), null, '#', ['params' => 'data-dismiss="modal"'], 'btn-link'); ?>
				  </div>
				</div>
			  </div>
			</div>
		<?php
          }
		?>
			
	  </td></tr>
	<?php
    }
	?>
  </tbody>
</table>

<table class="oscom-table table table-hover">
  <tr class="info">
      <th class="text-left"><?php echo $OSCOM_Related->getDef('app_related_admin_table_heading_new'); ?></th>
  <tr>
   <td>
	<form class="form-inline" name="relatedDropdown" method="post">
		<div class="form-group col-sm-3">
		  <label for="products_id_master"><?php echo $OSCOM_Related->getDef('app_related_select_master'); ?>:</label>
		    <select class="form-control" name="products_id_master">									
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

				if (!$products_id_master) { 
					$products_id_master = $products_id_view; 
				}
				

				while ($Qrelated_select->fetch()) {	
					$model = (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_MODEL == 'True')?$Qrelated_select->value('products_model') . OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MODEL_SEPARATOR:'';
					$name = (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_NAME == 'True')?$Qrelated_select->value('products_name'):'';
					$product_name = (OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MAX_NAME_LENGTH == '0')?$name:substr($name, 0, OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MAX_NAME_LENGTH);
					echo '<option id="c' . $Qrelated_select->value('products_id') . '" value="' . $Qrelated_select->valueInt('products_id') . '"' . (($products_id_master == $Qrelated_select->valueInt('products_id'))? ' selected' : '') . '>' . $model . $product_name . '</option>';
											
				}
?>
            </select>
		</div>
		<div class="form-group col-sm-3">
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
					echo '<option id="d' . $Qrelated_select->value('products_id') . '" value="' . $Qrelated_select->value('products_id') . '"' . (($products_id_slave == $Qrelated_select->value('products_id'))? ' selected' : '') . '>' . $model . $product_name . '</option>';
				}
			?>
            </select>
		  </div>
		  <div class="form-group col-sm-3">
			<label for="pop_order_id"><?php echo $OSCOM_Related->getDef('app_related_sort_order'); ?>:</label>
			  <?php echo HTML::inputField('pop_order_id', '', 'id="pop_order_id"'); ?>
		  </div>
		  <div class="form-group col-sm-3"><?= HTML::button($OSCOM_Related->getDef('app_related_button_insert'), null, null, array('params' => 'formaction="' . $OSCOM_Related->link('Admin&Insert') . '"'), 'btn-success'); ?> <?= HTML::button($OSCOM_Related->getDef('app_related_button_reciprocate'), null, null, array('params' => 'formaction="' . $OSCOM_Related->link('Admin&Reciprocate') . '"'), 'btn-default'); ?> <?= HTML::button($OSCOM_Related->getDef('app_related_button_inherit'), null, null, array('params' => 'formaction="' . $OSCOM_Related->link('Admin&Inherit') . '"'), 'btn-info'); ?></div>
	</form>
   </td>
  </tr>
  <tr>
	<td><small><?php echo $OSCOM_Related->getDef('app_related_mini_instructions'); ?></small></td>
  </tr>
</table>

<div>
  <span class="pull-right"><?= $Qrelated->getPageSetLinks(tep_get_all_get_params(array('page'))); ?></span>
  <?= $Qrelated->getPageSetLabel($OSCOM_Related->getDef('app_text_display_number_of_related')); ?>
</div>

<?php
  } else { //App is not installed
	  $OSCOM_MessageStack->add($OSCOM_Related->getDef('alert_app_not_installed'), 'warning', 'Related');
  }

  require(__DIR__ . '/template_bottom.php');
?>
