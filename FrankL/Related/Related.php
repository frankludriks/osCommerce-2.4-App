<?php
/**
  * Related Products App for osCommerce Online Merchant
  *
  * @copyright (c) 2016 Frank Ludriks; https://www.in-cartridge.com.au
  * @license MIT; https://www.oscommerce.com/license/mit.txt
  */

namespace OSC\Apps\FrankL\Related;

//use OSC\OM\FileSystem;
//use OSC\OM\HTTP;
use OSC\OM\OSCOM;
use OSC\OM\Registry;

class Related extends \OSC\OM\AppAbstract
{

    protected function init()
    {
		
    }
	public function getConfigModules()
    {
        static $result;

        if (!isset($result)) {
            $result = [];

            $directory = OSCOM::BASE_DIR . 'Apps/FrankL/Related/Module/Admin/Config';

            if ($dir = new \DirectoryIterator($directory)) {
                foreach ($dir as $file) {
                    if (!$file->isDot() && $file->isDir() && is_file($file->getPathname() . '/' . $file->getFilename() . '.php')) {
                        $class = 'OSC\Apps\FrankL\Related\Module\Admin\Config\\' . $file->getFilename() . '\\' . $file->getFilename();

                        if (is_subclass_of($class, 'OSC\Apps\FrankL\Related\Module\Admin\Config\ConfigAbstract')) {
                            $sort_order = $this->getConfigModuleInfo($file->getFilename(), 'sort_order');

                            if ($sort_order > 0) {
                                $counter = $sort_order;
                            } else {
                                $counter = count($result);
                            }

                            while (true) {
                                if (isset($result[$counter])) {
                                    $counter++;

                                    continue;
                                }

                                $result[$counter] = $file->getFilename();

                                break;
                            }
                        } else {
                            trigger_error('OSC\Apps\FrankL\Related\Related::getConfigModules(): OSC\Apps\FrankL\Related\Module\Admin\Config\\' . $file->getFilename() . '\\' . $file->getFilename() . ' is not a subclass of OSC\Apps\FrankL\Related\Module\Admin\Config\ConfigAbstract and cannot be loaded.');
                        }
                    }
                }

                ksort($result, SORT_NUMERIC);
            }
        }

        return $result;
	}
	
	public function getConfigModuleInfo($module, $info)
    {
        if (!Registry::exists('RelatedAdminConfig' . $module)) {
            $class = 'OSC\Apps\FrankL\Related\Module\Admin\Config\\' . $module . '\\' . $module;

            Registry::set('RelatedAdminConfig' . $module, new $class);
        }

        return Registry::get('RelatedAdminConfig' . $module)->$info;
    }
	
	public function osc_get_products_model($product_id) {
		
		$Qmodel = $this->db->prepare('select products_model from :table_products where products_id = :products_id');
		$Qmodel->bindInt(':products_id', (int)$product_id); 
		$Qmodel->execute();
    return $Qmodel->value('products_model');
	}
  
  
  }
?>
