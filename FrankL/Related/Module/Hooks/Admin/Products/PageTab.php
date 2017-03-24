<?php
/**
  * Related Products App for osCommerce Online Merchant
  *
  * @copyright (c) 2016 Frank Ludriks; https://www.in-cartridge.com.au
  * @license MIT; https://www.oscommerce.com/license/mit.txt
  */

namespace OSC\Apps\FrankL\Related\Module\Hooks\Admin\Products;

use OSC\OM\HTML;
use OSC\OM\OSCOM;
use OSC\OM\Registry;

use OSC\Apps\FrankL\Related\Related as RelatedApp;

class PageTab implements \OSC\OM\Modules\HooksInterface
{
    protected $app;

    public function __construct()
    {
        if (!Registry::exists('Related')) {
            Registry::set('Related', new RelatedApp());
        }

        $this->app = Registry::get('Related');
    }

    public function display()
    {
        global $oID;

        if (!defined('OSCOM_APP_FRANKL_RELATED_STATUS')) {
            return false;
        }

        $this->app->loadDefinitions('hooks/admin/products/tab');

        $output = '';

        $status = [];
		
		$info = '';
		
		$data = '';

        
		//check for masters
			$Qcheck = $this->app->db->prepare('select * from :table_products_related_products where pop_products_id_master = :products_id and pop_products_id_slave != :products_id');
			$Qcheck->bindInt(':products_id', $_GET['pID']);
			$Qcheck->execute();
			if ($Qcheck->fetch() !== false) {
				$master_query = 'select pop_products_id_slave
								  from :table_products_related_products 
								  where pop_products_id_master = :products_id';
				$Qmaster = $this->app->db->prepare($master_query);
				$Qmaster->bindInt(':products_id', $_GET['pID']);
				$Qmaster->execute();
				
				$info .=  '<div class="col-sm-5"><table class="table table-hover">' .
							' <thead>' .
							'	<tr class="info">' .
							'	  <th>' . $this->app->getDef('heading_master') . '</th>' .
							'	</tr>' .
							'  </thead>';
				
				  while ($Qmaster->fetch()) {
				
					$related_query = 'select pa.*, pd.products_id, pd.products_name
									  from :table_products_related_products pa
									  left join :table_products_description pd
									  on pa.pop_products_id_slave = pd.products_id
									  and pd.language_id = :languages_id
									  left join :table_products p
									  on p.products_id = pd.products_id 
									  where pd.products_id = :products_id';
					$Qrelated = $this->app->db->prepare($related_query);
					$Qrelated->bindInt(':products_id', $Qmaster->value('pop_products_id_slave'));
					$Qrelated->bindInt(':languages_id', $this->app->lang->getId());
					$Qrelated->execute();
				  
				     while ($Qrelated->fetch()) {
						$info .= '  <tr>' .
							'	<td>' .
							'	  ' . $Qrelated->value('products_name') .
							'   </td>' .
							'  </tr>';
					}
				   
				  }
				
				$info .=  '</table></div>';
				
			} else {
				
				$info .=    '<div class="col-sm-5"><table class="table table-hover">' .
							' <thead>' .
							'	<tr class="info">' .
							'	  <th>' . $this->app->getDef('heading_master') . '</th>' .
							'	</tr>' .
							'  </thead>' .
							'   <tr>' .
							'	  <td>' .
							'		<div class="alert alert-warning col-sm-5" role="alert">' . $this->app->getDef('no_products') . '</div>' .
							'	  </td>' .
							'	</tr>' .
							'</table></div>';
					
			}
				//$info .= '  <div class="clearfix"></div>';
				
		//check for slaves
				
			$Qcheck = $this->app->db->prepare('select * from :table_products_related_products where pop_products_id_slave = :products_id');
			$Qcheck->bindInt(':products_id', $_GET['pID']);
			$Qcheck->execute();
			  if ($Qcheck->fetch() !== false) {
				$slave_query = 'select pop_products_id_master
								from :table_products_related_products 
								where pop_products_id_slave = :products_id';
				$Qslave = $this->app->db->prepare($slave_query);
				$Qslave->bindInt(':products_id', $_GET['pID']);
				$Qslave->execute();
				
				
				$info .=  '<div class="col-sm-5"><table class="table table-hover">' .
							' <thead>' .
							'	<tr class="info">' .
							'	  <th>' . $this->app->getDef('heading_slave') . '</th>' .
							'	</tr>' .
							'  </thead>';
				
				
				  while ($Qslave->fetch()) {
				
					$related_query = 'select pa.*, pd.products_id, pd.products_name
									  from :table_products_related_products pa
									  left join :table_products_description pd
									  on pa.pop_products_id_master = pd.products_id
									  and pd.language_id = :languages_id
									  left join :table_products p
									  on p.products_id = pd.products_id 
									  where pd.products_id = :products_id and pa.pop_products_id_slave = :pop_products_id_slave';
					$Qrelated = $this->app->db->prepare($related_query);
					$Qrelated->bindInt(':products_id', $Qslave->value('pop_products_id_master'));
					$Qrelated->bindInt(':pop_products_id_slave', $_GET['pID']);
					$Qrelated->bindInt(':languages_id', $this->app->lang->getId());
					$Qrelated->execute();

					 while ($Qrelated->fetch()) {
						$info .= '  <tr>' .
								 '	<td>' .
								 '	  ' . $Qrelated->value('products_name') .
								 '   </td>' .
								 '  </tr>';
				     
					 }
				  
				  }
				    
					$info .=  '</table></div>';
				   
				   } else {
				$info .=    '<div class="col-sm-5"><table class="table table-hover">' .
							' <thead>' .
							'	<tr class="info">' .
							'	  <th>' . $this->app->getDef('heading_slave') . '</th>' .
							'	</tr>' .
							'  </thead>' .
							'   <tr>' .
							'	  <td>' .
							'		<div class="alert alert-warning col-sm-5" role="alert">' . $this->app->getDef('no_products') . '</div>' .
							'	  </td>' .
							'	</tr>' .
							'</table></div>';
					
			  }
				
				//$info .= '  <div class="clearfix"></div>';
				

                $related_button = HTML::button($this->app->getDef('button_manage'), 'fa fa-arrows-h', $this->app->link('Admin&products_id_view=' . $_GET['pID']), null, 'btn-info');
				
				$data .= '<div class="panel panel-info oscom-panel">' .
						'  <div class="panel-body">' .
						'	<div class="container-fluid">' .
						'	  <div class="row">' .
						'		' . $info .
						'	  </div>' .
						'	  <div class="text-center">' .
						'		' . $related_button .
						'	  </div>' .
						'	</div>' .
						'  </div>' .
						'</div>';
				
                $tab_title = addslashes($this->app->getDef('tab_title'));

                $output = <<<EOD
<div id="section_relatedAppRelated_content" class="tab-pane oscom-m-top-15">
  {$data}
</div>

<script>
$('#section_relatedAppRelated_content').appendTo('#productTabs .tab-content');
$('#productTabs .nav-tabs').append('<li><a data-target="#section_relatedAppRelated_content" data-toggle="tab">{$tab_title}</a></li>');
</script>
EOD;

        return $output;
    }


}
