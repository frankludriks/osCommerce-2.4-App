<?php
/**
  * Related Products App for osCommerce Online Merchant
  *
  * @copyright (c) 2016 Frank Ludriks; https://www.in-cartridge.com.au
  * @license MIT; https://www.oscommerce.com/license/mit.txt
  */

namespace OSC\Apps\FrankL\Related\Module\Admin\Config\PIRELATED\Params;

use OSC\OM\HTML;

class insert_and_inherit extends \OSC\Apps\FrankL\Related\Module\Admin\Config\ConfigParamAbstract
{
    public $default = 'False';
    public $sort_order = 170;

    protected function init()
    {
        $this->title = $this->app->getDef('cfg_related_insert_and_inherit_title');
        $this->description = $this->app->getDef('cfg_related_insert_and_inherit_desc');
    }

    public function getInputField()
    {
        $value = $this->getInputValue();

        $input = '<div class="btn-group" data-toggle="buttons">' .
                 '  <label class="btn btn-info' . ($value == 'True' ? ' active' : '') . '">' . HTML::radioField($this->key, 'True', ($value == 'True')) . $this->app->getDef('cfg_related_insert_and_inherit_true') . '</label>' .
                 '  <label class="btn btn-info' . ($value == 'False' ? ' active' : '') . '">' . HTML::radioField($this->key, 'False', ($value == 'False')) . $this->app->getDef('cfg_related_insert_and_inherit_disabled') . '</label>' .
                 '</div>';

        return $input;
    }
}