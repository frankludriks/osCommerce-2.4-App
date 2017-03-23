<?php
/**
  * Related Products App for osCommerce Online Merchant
  *
  * @copyright (c) 2016 Frank Ludriks; https://www.in-cartridge.com.au
  * @license MIT; https://www.oscommerce.com/license/mit.txt
  */

namespace OSC\Apps\FrankL\Related\Sites\Admin\Pages\Home\Actions;

use OSC\OM\Registry;

class Configure extends \OSC\OM\PagesActionsAbstract
{
    public function execute()
    {
        $OSCOM_Related = Registry::get('Related');

        $this->page->setFile('configure.php');
        $this->page->data['action'] = 'Configure';

        $OSCOM_Related->loadDefinitions('admin/configure');

        $modules = $OSCOM_Related->getConfigModules();

        $default_module = 'PIRELATED';

        foreach ($modules as $m) {
            if ($OSCOM_Related->getConfigModuleInfo($m, 'is_installed') === true ) {
                $default_module = $m;
                break;
            }
        }

        $this->page->data['current_module'] = (isset($_GET['module']) && in_array($_GET['module'], $modules)) ? $_GET['module'] : $default_module;

    }
}