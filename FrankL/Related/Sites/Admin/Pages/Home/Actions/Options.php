<?php
/**
  * Related Products App for osCommerce Online Merchant
  *
  * @copyright (c) 2016 Frank Ludriks; https://www.in-cartridge.com.au
  * @license MIT; https://www.oscommerce.com/license/mit.txt
  */

namespace OSC\Apps\FrankL\Related\Sites\Admin\Pages\Home\Actions;

use OSC\OM\Registry;

class Options extends \OSC\OM\PagesActionsAbstract
{
    public function execute()
    {
        $OSCOM_Related = Registry::get('Related');

        $this->page->setFile('options.php');
        $this->page->data['action'] = 'Options';

        $OSCOM_Related->loadDefinitions('admin/options');

        $data = [
            'OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_MODEL',
			'OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_NAME',
			'OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MODEL_SEPARATOR',
			'OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MAX_ROWS_LIST_OPTIONS',
			'OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MAX_NAME_LENGTH',
			'OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MAX_DISPLAY_LENGTH',
			'OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_CONFIRM_DELETE',
			'OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_INSERT_AND_INHERIT'
        ];

        foreach ($data as $key) {
            if (!defined($key)) {
                $OSCOM_Related->saveCfgParam($key, '');
            }
        }
    }
}
