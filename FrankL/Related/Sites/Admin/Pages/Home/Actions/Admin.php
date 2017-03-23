<?php
/**
  * Related Products App for osCommerce Online Merchant
  *
  * @copyright (c) 2016 Frank Ludriks; https://www.in-cartridge.com.au
  * @license MIT; https://www.oscommerce.com/license/mit.txt
  */

namespace OSC\Apps\FrankL\Related\Sites\Admin\Pages\Home\Actions;

use OSC\OM\Registry;

class Admin extends \OSC\OM\PagesActionsAbstract
{
    public function execute()
    {
        $OSCOM_Related = Registry::get('Related');

        $this->page->setFile('admin.php');
        $this->page->data['action'] = 'Admin';

        $OSCOM_Related->loadDefinitions('admin/admin');
		$OSCOM_Related->loadDefinitions('admin/edit');
    }
}