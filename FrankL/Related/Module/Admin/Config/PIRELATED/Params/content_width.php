<?php
/**
  * Related Products App for osCommerce Online Merchant
  *
  * @copyright (c) 2016 Frank Ludriks; https://www.in-cartridge.com.au
  * @license MIT; https://www.oscommerce.com/license/mit.txt
  */

namespace OSC\Apps\FrankL\Related\Module\Admin\Config\PIRELATED\Params;

use OSC\OM\HTML;

class content_width extends \OSC\Apps\FrankL\Related\Module\Admin\Config\ConfigParamAbstract
{
    public $default = '12';
    public $sort_order = 4;

    protected function init()
    {
        $this->title = $this->app->getDef('cfg_related_content_width_title');
        $this->description = $this->app->getDef('cfg_related_content_width_desc');
    }

    public function getInputField()
    {
		
		for ($i=1; $i<13; $i++) {
		$width_array[] = array('id' => $i, 'text' => $i);
	}


        $input = HTML::selectField($this->key, $width_array, $this->getInputValue());

        return $input;
    }
}