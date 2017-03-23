<?php
/**
  * Related Products App for osCommerce Online Merchant
  *
  * @copyright (c) 2016 Frank Ludriks; https://www.in-cartridge.com.au
  * @license MIT; https://www.oscommerce.com/license/mit.txt
  */

namespace OSC\Apps\FrankL\Related\Module\Admin\Menu;

use OSC\OM\Registry;

use OSC\Apps\FrankL\Related\Related as RelatedApp;

class Related implements \OSC\OM\Modules\AdminMenuInterface
{
    public static function execute()
    {
        if (!Registry::exists('Related')) {
            Registry::set('Related', new RelatedApp());
        }

        $OSCOM_Related = Registry::get('Related');

        $OSCOM_Related->loadDefinitions('admin/modules/boxes/related');
		
		$related_menu = [
            [
                'code' => $OSCOM_Related->getVendor() . '\\' . $OSCOM_Related->getCode(),
                'title' => $OSCOM_Related->getDef('module_admin_menu_install'),
                'link' => $OSCOM_Related->link()
            ]
        ];

		$related_menu_check = [
            'OSCOM_APP_FRANKL_RELATED_STATUS'
        ];
		
        foreach ($related_menu_check as $value) {
            if (defined($value) && !empty(constant($value))) {
                $related_menu = [
                    [
                        'code' => $OSCOM_Related->getVendor() . '\\' . $OSCOM_Related->getCode(),
                        'title' => $OSCOM_Related->getDef('module_admin_menu_admin'),
                        'link' => $OSCOM_Related->link('Admin')
                    ],
					[
                        'code' => $OSCOM_Related->getVendor() . '\\' . $OSCOM_Related->getCode(),
                        'title' => $OSCOM_Related->getDef('module_admin_menu_configure'),
                        'link' => $OSCOM_Related->link('Configure')
                    ]
					
                ];

                break;
            }
		}

        return array('heading' => $OSCOM_Related->getDef('module_admin_menu_title'),
                     'apps' => $related_menu);
    }
}