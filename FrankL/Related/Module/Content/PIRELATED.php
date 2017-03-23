<?php
/**
  * Related Products App for osCommerce Online Merchant
  *
  * @copyright (c) 2016 Frank Ludriks; https://www.in-cartridge.com.au
  * @license MIT; https://www.oscommerce.com/license/mit.txt
  */

  namespace OSC\Apps\FrankL\Related\Module\Content;

  use OSC\OM\HTML;
  use OSC\OM\OSCOM;
  use OSC\OM\Registry;
  use OSC\Apps\FrankL\Related\Related as RelatedApp;

  class PIRELATED implements \OSC\OM\Modules\ContentInterface {
    public $code, $group, $title, $description, $sort_order, $enabled, $app;

    function __construct() {
      if (!Registry::exists('Related')) {
        Registry::set('Related', new RelatedApp());
      }

	  $this->app = Registry::get('Related');
      $this->app->loadDefinitions('modules/PIRELATED/PIRELATED');

      $this->code = 'PIRELATED';
      $this->group = 'product_info';

      $this->title = $this->app->getDef('module_related_title');
	  $this->description = '<div align="center">' . HTML::button($this->app->getDef('module_related_legacy_admin_app_button'), null, $this->app->link('Configure&module=PIRELATED'), null, 'btn-primary') . '</div>';
      $this->sort_order = defined('OSCOM_APP_FRANKL_PIRELATED_SORT_ORDER') ? OSCOM_APP_FRANKL_PIRELATED_SORT_ORDER : 0;
	  
          if ( OSCOM_APP_FRANKL_PIRELATED_STATUS < '1' ) {

		    $this->enabled = false;
		  } else {
			$this->enabled = true;
		  }
	}
	
	function execute() {
      global $oscTemplate, $languages_id, $currencies, $currency, $PHP_SELF, $product_info;
      
      $content_width = OSCOM_APP_FRANKL_PIRELATED_CONTENT_WIDTH;
      $product_width = OSCOM_APP_FRANKL_PIRELATED_DISPLAY_EACH;

      $optional_rel_prods_content = NULL;
      
      $orderBy = 'order by ';
      $orderBy .= (OSCOM_APP_FRANKL_PIRELATED_RANDOMIZE == 'True')?'rand()':'pop_order_id, pop_id';
      $orderBy .= (OSCOM_APP_FRANKL_PIRELATED_MAX_DISP > 0)?' limit ' . OSCOM_APP_FRANKL_PIRELATED_MAX_DISP:'';

      $optional_rel_prods = "SELECT ";
      $specials_query = '';

      if (OSCOM_APP_FRANKL_PIRELATED_SHOW_QUANTITY == 'True')
      	$optional_rel_prods .= "products_quantity, ";

      if (OSCOM_APP_FRANKL_PIRELATED_SHOW_MODEL == 'True')
      	$optional_rel_prods .= "products_model, ";

      if (OSCOM_APP_FRANKL_PIRELATED_SHOW_THUMBS == 'True')
      	$optional_rel_prods .= "products_image, ";
                                       
      if (OSCOM_APP_FRANKL_PIRELATED_SHOW_PRICE == 'True') {
      	$optional_rel_prods .= "products_tax_class_id, products_price, IF(s.status, s.specials_new_products_price, NULL) as specials_products_price, ";
      	$specials_query = " left join :table_specials s on s.products_id = pb.products_id ";
      }

      if (OSCOM_APP_FRANKL_PIRELATED_SHOW_NAME == 'True')
      	$optional_rel_prods .= "products_name, ";        

      if (OSCOM_APP_FRANKL_PIRELATED_SHOW_DESCRIPTION == 'True')
        $optional_rel_prods .= "substring_index(pa.products_description, ' ', " . OSCOM_APP_FRANKL_PIRELATED_DESCRIPTION_LENGTH . ") as products_description, ";
        
      $optional_rel_prods .= "pop_products_id_slave ";
      
      $optional_rel_prods .= "from 
         											:table_products_related_products, 
         											:table_products pb
                              left join :table_products_description pa on pa.products_id = pb.products_id 
         											" . $specials_query . "
         											where pop_products_id_slave = pa.products_id
         											and pa.products_id = pb.products_id
         											and language_id = :languages_id
         											and pop_products_id_master = :products_id
         											and products_status='1' " . $orderBy;
	  
			$Qrelated = $this->app->db->prepare($optional_rel_prods);
			$Qrelated->bindInt(':languages_id', $this->app->lang->getId()); //(int)$languages_id
			$Qrelated->bindInt(':products_id', $_GET['products_id']); //(int)$_GET['products_id']
			$Qrelated->execute();

				if ($Qrelated->rowCount() > 0) {
  
				$optional_rel_prods_content .= '<h3>' . $this->app->getDef('module_related_title') . '</h3>';
        
					$optional_rel_prods_content .= '  <div id="products" class="row list-group" itemtype="http://schema.org/ItemList">';
					while ($Qrelated->fetch()) {				
					if (OSCOM_APP_FRANKL_PIRELATED_SHOW_QUANTITY == 'True')
						$products_qty_slave = '<span itemprop="inventoryLevel">' . $Qrelated->value('products_quantity') . '</span>';
					
					$optional_rel_prods_content .= '  <div class="item col-sm-' .  $product_width . ' grid-group-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/Product">';
					$optional_rel_prods_content .= '    <meta itemprop="url" content="' . OSCOM::link('product_info.php', 'products_id=' . $Qrelated->value('pop_products_id_slave')) . '" />';


					
					$optional_rel_prods_content .= '    <div class="productHolder equal-height text-center">';

					// show thumb image if Enabled
					if (OSCOM_APP_FRANKL_PIRELATED_SHOW_THUMBS == 'True') {
						$optional_rel_prods_content .= '<a href="' . OSCOM::link('product_info.php', 'products_id=' . $Qrelated->value('pop_products_id_slave')) . '">' . HTML::image('images/' . $Qrelated->value('products_image'), $Qrelated->value('products_name'), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
					}
      
					$optional_rel_prods_content .= '      <div class="caption">';

					if (OSCOM_APP_FRANKL_PIRELATED_SHOW_NAME == 'True') {
						$optional_rel_prods_content .= '<h2 class="group inner list-group-item-heading"><a href="' . OSCOM::link('product_info.php', 'products_id=' . $Qrelated->value('pop_products_id_slave')) . '"><span itemprop="name">' . $this->osc_truncate_text_rel_prod($Qrelated->value('products_name'), OSCOM_APP_FRANKL_PIRELATED_NAME_LENGTH, OSCOM_APP_FRANKL_PIRELATED_MAX_WORD_LENGTH) . '</span></a></h2 >';
					}
					
					if (OSCOM_APP_FRANKL_PIRELATED_SHOW_MODEL == 'True') {
						$optional_rel_prods_content .=  '<p class="text-center small" itemprop="model">' . $Qrelated->value('products_model') . '</p>';
					}
	        		
					$optional_rel_prods_content .= '<hr>';
				
        	if (OSCOM_APP_FRANKL_PIRELATED_SHOW_DESCRIPTION == 'True') {
						$optional_rel_prods_content .= '<p class="text-center"><span itemprop="description">' . $this->osc_truncate_text_rel_prod(strip_tags($Qrelated->value('products_description')), OSCOM_APP_FRANKL_PIRELATED_DESCRIPTION_LENGTH, OSCOM_APP_FRANKL_PIRELATED_MAX_WORD_LENGTH) . '</span><br>';
        	}

        	if (OSCOM_APP_FRANKL_PIRELATED_SHOW_PRICE == 'True') {
        		$optional_rel_prods_content .= '<p class="text-center" itemprop="offers" itemscope itemtype="http://schema.org/Offer"><meta itemprop="priceCurrency" content="' . HTML::outputProtected($currency) . '">';
        		if (tep_not_null($Qrelated->value('specials_products_price'))) {
        			$optional_rel_prods_content .= '<del>' . $currencies->display_price($Qrelated->value('products_price'), tep_get_tax_rate($Qrelated->value('products_tax_class_id'))) . '</del>&nbsp;';
        			$optional_rel_prods_content .= '<span class="productSpecialPrice" itemprop="price" content="' . $currencies->display_raw($Qrelated->value('specials_products_price'), tep_get_tax_rate($Qrelated->value('products_tax_class_id'))) . '">' . $currencies->display_price($Qrelated->value('specials_products_price'), tep_get_tax_rate($Qrelated->value('products_tax_class_id'))) . '</span><br>';
        		} else {
        		$optional_rel_prods_content .= '<span itemprop="price" content="' . $currencies->display_raw($Qrelated->value('products_price'), tep_get_tax_rate($Qrelated->value('products_tax_class_id'))) . '">' . $currencies->display_price($Qrelated->value('products_price'), tep_get_tax_rate($Qrelated->value('products_tax_class_id'))) . '</span><br>';
        		}
        		$optional_rel_prods_content .= '</p>';
        	}
					
					if (OSCOM_APP_FRANKL_PIRELATED_SHOW_QUANTITY == 'True') {
						$optional_rel_prods_content .= '<p class="text-center" itemprop="offers" itemscope itemtype="http://schema.org/Offer">' . $this->app->getDef('module_related_products_quantity') . ' ' . $products_qty_slave . '</p>';
					}
      
					$optional_rel_prods_content .= '      </div>'; // caption
					$optional_rel_prods_content .= '    </div>'; // thumbnail
					$optional_rel_prods_content .= '  </div>'; // col-sm-' .  $product_width . '" 
				}
				
				$optional_rel_prods_content .= '  </div>'; // end list of products 
			}

			ob_start();
		include(__DIR__ . '/templates/PIRELATED.php');
        $template = ob_get_clean();

        $oscTemplate->addContent($template, $this->group);
    }
	
	function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('OSCOM_APP_FRANKL_PIRELATED_STATUS');
    }

    function install() {
      $this->app->redirect('Configure&Install&module=PIRELATED');
    }

    function remove() {
      $this->app->redirect('Configure&Uninstall&module=PIRELATED');
    }

    function keys() {
    }
	
	    protected function osc_limit_text_rel_prod ($text, $maxchar, $wordlength = 40) {
    	$text = str_replace ("\n", ' ', $text);
    	$text = str_replace ("\r", ' ', $text);
    	$text = str_replace ('<br>', ' ', $text);
    	$text = wordwrap ($text, $wordlength, ' ', true);
    	$text = preg_replace ("/[ ]+/", ' ', $text);
    	$text_length = strlen ($text);
    	$text_array = explode (" ", $text);

    	$newtext = '';
    	for ($array_key = 0, $length = 0; $length <= $text_length; $array_key++) {
    		$length = strlen ($newtext) + strlen ($text_array[$array_key]) + 1;
    		if ($length > $maxchar) break;
    		$newtext = $newtext . ' ' . $text_array[$array_key];
    	}

    	return $newtext;
    }
    
    protected function osc_truncate_text_rel_prod ($products_text, $maxchar, $wordlength = 40) {
    	$products_text = ($products_text);
			if ($maxchar > 0) {
				$products_text_length = strlen ($products_text);
				if ($products_text_length > $maxchar) {
					$products_text = $this->osc_limit_text_rel_prod ($products_text, $maxchar, $wordlength);
					$products_text .= '&nbsp;...';
				}
			}
				
			return $products_text;
   } 
   
   
  }
?>
