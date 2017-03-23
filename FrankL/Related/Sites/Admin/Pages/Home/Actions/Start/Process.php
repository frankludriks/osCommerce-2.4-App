<?php
/**
  * Related Products App for osCommerce Online Merchant
  *
  * @copyright (c) 2016 Frank Ludriks; https://www.in-cartridge.com.au
  * @license MIT; https://www.oscommerce.com/license/mit.txt
  */

namespace OSC\Apps\FrankL\Related\Sites\Admin\Pages\Home\Actions\Start;

use OSC\OM\HTTP;
use OSC\OM\OSCOM;
use OSC\OM\Registry;

class Process extends \OSC\OM\PagesActionsAbstract
{
	
	public function execute()
    {
        $OSCOM_MessageStack = Registry::get('MessageStack');
        $OSCOM_Related = Registry::get('Related');

		if (!defined('OSCOM_APP_FRANKL_RELATED_STATUS')) {
		$OSCOM_Db = Registry::get('Db');
		$OSCOM_Db->save('configuration', [
        'configuration_title' => 'Enable Related Products App',
        'configuration_key' => 'OSCOM_APP_FRANKL_RELATED_STATUS',
        'configuration_value' => 'True',
        'configuration_description' => 'Should we install optional_related_products ?',
        'configuration_group_id' => '6',
        'sort_order' => '2',
        'set_function' => 'tep_cfg_select_option(array(\'True\', \'False\'), ',
        'date_added' => 'now()'
      ]);
		}
		
		$data = [
                'OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_MODEL'  => 'False',
				'OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_USE_NAME' => 'True',
				'OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MODEL_SEPARATOR' => ' | ',
				'OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MAX_ROWS_LIST_OPTIONS' => '20',
				'OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MAX_NAME_LENGTH' => '30',
				'OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_MAX_DISPLAY_LENGTH' => '30',
				'OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_CONFIRM_DELETE' => 'True',
				'OSCOM_APP_FRANKL_RELATED_RELATED_PRODUCTS_INSERT_AND_INHERIT' => 'False'
            ];

        foreach ($data as $key => $value) {
            $OSCOM_Related->saveCfgParam($key, $value);
        }
		
		$Qcheck = $OSCOM_Db->query('show tables like ":table_products_related_products"');

        if ($Qcheck->fetch() === false) {
            $sql = <<<EOD
CREATE TABLE :table_products_related_products (
  pop_id int(11) NOT NULL auto_increment,
  pop_products_id_master int(11) NOT NULL DEFAULT '0',
  pop_products_id_slave int(11) NOT NULL DEFAULT '0',
  pop_order_id smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (pop_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;
EOD;

            $OSCOM_Db->exec($sql);
        }

        $OSCOM_Related->redirect('Configure');
    }
}