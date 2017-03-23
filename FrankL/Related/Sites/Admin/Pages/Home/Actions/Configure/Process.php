<?php
/**
  * Related Products App for osCommerce Online Merchant
  *
  * @copyright (c) 2016 Frank Ludriks; https://www.in-cartridge.com.au
  * @license MIT; https://www.oscommerce.com/license/mit.txt
  */

namespace OSC\Apps\FrankL\Related\Sites\Admin\Pages\Home\Actions\Configure;

use OSC\OM\Registry;

class Process extends \OSC\OM\PagesActionsAbstract
{
    public function execute()
    {
        $OSCOM_MessageStack = Registry::get('MessageStack');
        $OSCOM_Related = Registry::get('Related');

        $current_module = 'PIRELATED';

        $m = Registry::get('RelatedAdminConfig' . $current_module);

        foreach ($m->getParameters() as $key) {
            $p = strtolower($key);

            if (isset($_POST[$p])) {
                $OSCOM_Related->saveCfgParam($key, $_POST[$p]);
            }
        }

        $OSCOM_MessageStack->add($OSCOM_Related->getDef('alert_cfg_saved_success'), 'success', 'Related');

        $OSCOM_Related->redirect('Configure&module=' . $current_module);
    }
}