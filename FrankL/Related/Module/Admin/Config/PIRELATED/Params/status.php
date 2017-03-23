<?php
/**
  * Related Products App for osCommerce Online Merchant
  *
  * @copyright (c) 2016 Frank Ludriks; https://www.in-cartridge.com.au
  * @license MIT; https://www.oscommerce.com/license/mit.txt
  */

namespace OSC\Apps\FrankL\Related\Module\Admin\Config\PIRELATED\Params;

use OSC\OM\HTML;

class status extends \OSC\Apps\FrankL\Related\Module\Admin\Config\ConfigParamAbstract
{
    public $default = '1';
    public $sort_order = 1;

    protected function init()
    {
        $this->title = $this->app->getDef('cfg_related_status_title');
        $this->description = $this->app->getDef('cfg_related_status_desc');
    }

    public function getInputField()
    {
        $value = $this->getInputValue();

        $input = '<div class="btn-group" data-toggle="buttons">' .
                 '  <label class="btn btn-info' . ($value == '1' ? ' active' : '') . '">' . HTML::radioField($this->key, '1', ($value == '1')) . $this->app->getDef('cfg_related_status_true') . '</label>' .
                 '  <label class="btn btn-info' . ($value == '-1' ? ' active' : '') . '">' . HTML::radioField($this->key, '-1', ($value == '-1')) . $this->app->getDef('cfg_related_status_disabled') . '</label>' .
                 '</div>';

        return $input;
    }
}