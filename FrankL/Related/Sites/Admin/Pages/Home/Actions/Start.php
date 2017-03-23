<?php
/**
  * Related Products App for osCommerce Online Merchant
  *
  * @copyright (c) 2016 Frank Ludriks; https://www.in-cartridge.com.au
  * @license MIT; https://www.oscommerce.com/license/mit.txt
  */

namespace OSC\Apps\FrankL\Related\Sites\Admin\Pages\Home\Actions;

class Start extends \OSC\OM\PagesActionsAbstract
{
    public function execute()
    {
        $this->page->data['action'] = 'Start';
    }
}