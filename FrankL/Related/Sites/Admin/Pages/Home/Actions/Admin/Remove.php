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

class Remove extends \OSC\OM\PagesActionsAbstract
{
    public function execute()
    {
        $OSCOM_MessageStack = Registry::get('MessageStack');
        $OSCOM_Related = Registry::get('Related');
		$OSCOM_Db = Registry::get('Db');

            if (isset($_GET['pop_id'])) {
				
				$pop_id = isset($_GET['pop_id']) ? HTML::sanitize($_GET['pop_id']) : '';
				$page = isset($_GET['page']) ? HTML::sanitize($_GET['page']) : '';
				$products_id_master = isset($_GET['products_id_master']) ? HTML::sanitize($_GET['products_id_master']) : '';
				$products_id_view = $products_id_master;
				$products_name_master = isset($_GET['products_name_master']) ? HTML::sanitize($_GET['products_name_master']) : '';
				$products_name_slave = isset($_GET['products_name_slave']) ? HTML::sanitize($_GET['products_name_slave']) : '';

				$Qdelete = $OSCOM_Db->prepare('delete from :table_products_related_products where pop_id like :pop_id');
				$Qdelete->bindValue(':pop_id', $pop_id);
				$Qdelete->execute();


				 $OSCOM_MessageStack->add($OSCOM_Related->getDef('alert_product_removed_success', ['master' => $products_name_master, 'slave' => $products_name_slave]), 'success', 'Related');
			} else {
				$OSCOM_MessageStack->add($OSCOM_Related->getDef('alert_product_removed_falure'), 'warning', 'Related');
			}


        $OSCOM_Related->redirect('Admin&page=' . $page);
    }
}