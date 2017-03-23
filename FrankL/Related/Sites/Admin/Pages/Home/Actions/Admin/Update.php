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

class Update extends \OSC\OM\PagesActionsAbstract
{
    public function execute()
    {
        $OSCOM_MessageStack = Registry::get('MessageStack');
        $OSCOM_Related = Registry::get('Related');
		$OSCOM_Db = Registry::get('Db');

            if (isset($_POST['pop_id'])) {
				
				$pop_id = isset($_POST['pop_id']) ? HTML::sanitize($_POST['pop_id']) : '';
				$pop_order_id = isset($_POST['pop_order_id']) ? HTML::sanitize($_POST['pop_order_id']) : '';
				$page = isset($_POST['page']) ? HTML::sanitize($_POST['page']) : '';
				$products_id_master = isset($_POST['products_id_master']) ? HTML::sanitize($_POST['products_id_master']) : '';
				$products_id_slave = isset($_POST['products_id_slave']) ? HTML::sanitize($_POST['products_id_slave']) : '';
				$products_id_view = $products_id_master;
				$products_name_master = isset($_POST['products_name_master']) ? HTML::sanitize($_POST['products_name_master']) : '';
				$products_name_slave = isset($_POST['products_name_slave']) ? HTML::sanitize($_POST['products_name_slave']) : '';

				$Qupdate = $OSCOM_Db->prepare('update :table_products_related_products set pop_products_id_master = :products_id_master, pop_products_id_slave = :products_id_slave, pop_order_id = :pop_order_id where pop_id = :pop_id');
				$Qupdate->bindValue(':products_id_master', $products_id_master);
				$Qupdate->bindValue(':products_id_slave', $products_id_slave);
				$Qupdate->bindValue(':pop_order_id', $pop_order_id);
				$Qupdate->bindValue(':pop_id', $pop_id);
				$Qupdate->execute();


				 $OSCOM_MessageStack->add($OSCOM_Related->getDef('alert_product_edit_success', ['master' => $products_name_master, 'slave' => $products_name_slave]), 'success', 'Related');
			} else {
				$OSCOM_MessageStack->add($OSCOM_Related->getDef('alert_product_edit_falure'), 'warning', 'Related');
			}


        $OSCOM_Related->redirect('Admin&products_id_view=' . $products_id_view . '&page=' . $page);
    }
}
