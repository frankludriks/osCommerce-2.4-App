<?php
/**
  * Related Products App for osCommerce Online Merchant
  *
  * @copyright (c) 2016 Frank Ludriks; https://www.in-cartridge.com.au
  * @license MIT; https://www.oscommerce.com/license/mit.txt
  */

namespace OSC\Apps\FrankL\Related\Sites\Admin\Pages\Home\Actions\Admin;

use OSC\OM\Registry;
use OSC\OM\HTML;

class Inherit extends \OSC\OM\PagesActionsAbstract
{
    public function execute()
    {
        $OSCOM_MessageStack = Registry::get('MessageStack');
        $OSCOM_Related = Registry::get('Related');
		$OSCOM_Db = Registry::get('Db');


                $products_id_master = isset($_POST['products_id_master']) ? HTML::sanitize($_POST['products_id_master']) : '';
                $products_id_slave = isset($_POST['products_id_slave']) ? HTML::sanitize($_POST['products_id_slave']) : '';
                $pop_order_id = isset($_POST['pop_order_id']) ? HTML::sanitize($_POST['pop_order_id']) : '';
				$products_id_view = $products_id_master;


        if ($products_id_master != $products_id_slave) {
		  if (OSCOM_APP_RELATED_PIRELATED_INSERT_AND_INHERIT == 'True') {
			
			$Qcheck = $OSCOM_Db->prepare('select pop_id from :table_products_related_products where pop_products_id_master = :products_id_master and pop_products_id_slave = :products_id_slave');
			$Qcheck->bindInt(':products_id_master', $products_id_master);
			$Qcheck->bindInt(':products_id_slave', $products_id_slave);
			$Qcheck->execute();
			if ($Qcheck->fetch() === false) {
			  $Qinsert = $OSCOM_Db->prepare('insert into :table_products_related_products (pop_products_id_master, pop_products_id_slave, pop_order_id) values (:products_id_master, :products_id_slave, :pop_order_id)');
			  $Qinsert->bindInt(':products_id_master', $products_id_master);
			  $Qinsert->bindInt(':products_id_slave', $products_id_slave);
			  $Qinsert->bindInt(':pop_order_id', $pop_order_id);
			  $Qinsert->execute();
		    }
		  }
		  
		  $Qproducts = $OSCOM_Db->prepare('select p.pop_products_id_slave from :table_products_related_products p where p.pop_products_id_master = :products_id_slave order by p.pop_id');
		  $Qproducts->bindInt(':products_id_slave', $products_id_slave);
		  $Qproducts->execute();
		  
		  while ($Qproducts->fetch()) {
			  $products_id_slave2 = $Qproducts->value('pop_products_id_slave');
				if ($products_id_master != $products_id_slave2) {
				  $Qcheck = $OSCOM_Db->prepare('select pop_id from :table_products_related_products where pop_products_id_master = :products_id_master and pop_products_id_slave = :products_id_slave');
				  $Qcheck->bindInt(':products_id_master', $products_id_master);
				  $Qcheck->bindInt(':products_id_slave', $products_id_slave2);
				  $Qcheck->execute();
					if ($Qcheck->fetch() === false) {
						
					  $Qinsert = $OSCOM_Db->prepare('insert into :table_products_related_products (pop_products_id_master, pop_products_id_slave, pop_order_id) values (:products_id_master, :products_id_slave, :pop_order_id)');
					  $Qinsert->bindInt(':products_id_master', $products_id_master);
					  $Qinsert->bindInt(':products_id_slave', $products_id_slave2);
					  $Qinsert->bindInt(':pop_order_id', $pop_order_id);
					  $Qinsert->execute();
					}
				}
		  }


				 $OSCOM_MessageStack->add($OSCOM_Related->getDef('alert_product_added_success_inherit'), 'success', 'Related');
			} else {
				$OSCOM_MessageStack->add($OSCOM_Related->getDef('alert_product_added_failure'), 'warning', 'Related');
			}

        $OSCOM_Related->redirect('Admin&products_id_master=' . $products_id_master . '&products_id_slave=' . $products_id_slave . '&products_id_view=' . $products_id_view);
    }
}