<?php
/**
  * Related Products App for osCommerce Online Merchant
  *
  * @copyright (c) 2016 Frank Ludriks; https://www.in-cartridge.com.au
  * @license MIT; https://www.oscommerce.com/license/mit.txt
  */

namespace OSC\Apps\FrankL\Related\Sites\Admin\Pages\Home;

use OSC\OM\Apps;
use OSC\OM\OSCOM;
use OSC\OM\Registry;

use OSC\Apps\FrankL\Related\Related;

class Home extends \OSC\OM\PagesAbstract
{
    public $app;

    protected function init()
    {
       
         $OSCOM_Related = new Related();
        Registry::set('Related', $OSCOM_Related);

        $this->app = $OSCOM_Related;
        
        		
		$this->app->loadDefinitions('admin');
		$this->app->loadDefinitions('admin/install');
        
    }
}