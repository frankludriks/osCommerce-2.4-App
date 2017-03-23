<?php
/**
  * Related Products App for osCommerce Online Merchant
  *
  * @copyright (c) 2016 Frank Ludriks; https://www.in-cartridge.com.au
  * @license MIT; https://www.oscommerce.com/license/mit.txt
  */

namespace OSC\Apps\FrankL\Related\Module\Admin\Config\PIRELATED;

use OSC\OM\OSCOM;

class PIRELATED extends \OSC\Apps\FrankL\Related\Module\Admin\Config\ConfigAbstract
{
    protected $_cm_code = 'product_info/cm_pi_related_products';

    public $is_uninstallable = true;
    public $is_migratable = true;
    public $sort_order = 1000;

    protected function init()
    {
        $this->title = $this->app->getDef('module_related_title');
        $this->short_title = $this->app->getDef('module_related_short_title');
        $this->introduction = $this->app->getDef('module_related_introduction');

        $this->is_installed = defined('OSCOM_APP_FRANKL_PIRELATED_STATUS') && (trim(OSCOM_APP_FRANKL_PIRELATED_STATUS) != '');

    }

    public function install()
    {
        parent::install();

        $installed = explode(';', MODULE_CONTENT_INSTALLED);
        $installed[] = 'product_info/' . $this->app->vendor . '\\' . $this->app->code . '\\' . $this->code;

        $this->app->saveCfgParam('MODULE_CONTENT_INSTALLED', implode(';', $installed));
    }

    public function uninstall()
    {
        parent::uninstall();

        $installed = explode(';', MODULE_CONTENT_INSTALLED);
        $installed_pos = array_search('product_info/' . $this->app->vendor . '\\' . $this->app->code . '\\' . $this->code, $installed);

        if ($installed_pos !== false) {
            unset($installed[$installed_pos]);

            $this->app->saveCfgParam('MODULE_CONTENT_INSTALLED', implode(';', $installed));
        }
    }
}