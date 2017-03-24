<?php
/**
  * Related Products App for osCommerce Online Merchant
  *
  * @copyright (c) 2016 Frank Ludriks; https://www.in-cartridge.com.au
  * @license MIT; https://www.oscommerce.com/license/mit.txt
  */

namespace OSC\Apps\FrankL\Related\Module\Hooks\Admin\Products;

use OSC\OM\HTML;
use OSC\OM\OSCOM;
use OSC\OM\Registry;

use OSC\Apps\FrankL\Related\Related as RelatedApp;

class Action implements \OSC\OM\Modules\HooksInterface
{
    protected $app;
    protected $ms;

    public function __construct()
    {
        if (!Registry::exists('Related')) {
            Registry::set('Related', new RelatedApp());
        }

        $this->app = Registry::get('Related');

        $this->ms = Registry::get('MessageStack');

        $this->app->loadDefinitions('hooks/admin/products/action');
    }

    public function execute()
    {

	
    }


}
