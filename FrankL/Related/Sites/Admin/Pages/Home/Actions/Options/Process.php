<?php
/**
  * Related Products App for osCommerce Online Merchant
  *
  * @copyright (c) 2016 Frank Ludriks; https://www.in-cartridge.com.au
  * @license MIT; https://www.oscommerce.com/license/mit.txt
  */

namespace OSC\Apps\FrankL\Related\Sites\Admin\Pages\Home\Actions\Options;

use OSC\OM\HTML;
use OSC\OM\Registry;

class Process extends \OSC\OM\PagesActionsAbstract
{
    public function execute()
    {
        $OSCOM_MessageStack = Registry::get('MessageStack');
        $OSCOM_Related = Registry::get('Related');

        //$current_module = $this->page->data['current_module'];

        $data = [];

             $data = [
				'OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_MODEL'  => isset($_POST['use_model']) ? HTML::sanitize($_POST['use_model']) : '',
				'OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_NAME' => isset($_POST['use_name']) ? HTML::sanitize($_POST['use_name']) : '',
				'OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MODEL_SEPARATOR' => isset($_POST['model_separator']) ? HTML::sanitize($_POST['model_separator']) : '',
				'OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MAX_ROWS_LIST_OPTIONS' => isset($_POST['max_rows_list_options']) ? HTML::sanitize($_POST['max_rows_list_options']) : '',
				'OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MAX_NAME_LENGTH' => isset($_POST['max_name_length']) ? HTML::sanitize($_POST['max_name_length']) : '',
				'OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MAX_DISPLAY_LENGTH' => isset($_POST['max_display_length']) ? HTML::sanitize($_POST['max_display_length']) : '',
				'OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_CONFIRM_DELETE' => isset($_POST['confirm_delete']) ? HTML::sanitize($_POST['confirm_delete']) : '',
				'OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_INSERT_AND_INHERIT' => isset($_POST['insert_and_inherit']) ? HTML::sanitize($_POST['insert_and_inherit']) : ''
            ];
       
        //}

        foreach ($data as $key => $value) {
            $OSCOM_Related->saveCfgParam($key, $value);
        }

        $OSCOM_MessageStack->add($OSCOM_Related->getDef('alert_credentials_saved_success'), 'success', 'Related');

        $OSCOM_Related->redirect('Admin');
    }
}
