<?php
/**
  * Related Products App for osCommerce Online Merchant
  *
  * @copyright (c) 2016 Frank Ludriks; https://www.in-cartridge.com.au
  * @license MIT; https://www.oscommerce.com/license/mit.txt
  */

namespace OSC\Apps\FrankL\Related\Module\Admin\Config\PIRELATED\Params;

class name_length extends \OSC\Apps\FrankL\Related\Module\Admin\Config\ConfigParamAbstract
{
    public $default = '36';
	public $sort_order = 40;
	

    protected function init()
    {
        $this->title = $this->app->getDef('cfg_related_name_length_title');
        $this->description = $this->app->getDef('cfg_related_name_length_desc');
    }
}
