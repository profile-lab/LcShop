# Levelcomplete 5 Corebase Module


## Install git submodule

        git submodule add https://github.com/profile-lab/LcShop
        OR 
        git submodule add https://github.com/profile-lab/LcShop <destination folder>

## Base Configuration 


Add LC5 psr4 namespace in App\Config\Autoload.php
        
        public $psr4 = [
                ...
                'LcShop\Cms'   => ROOTPATH . 'LcShop/Cms',
                'LcShop\Data'   => ROOTPATH . 'LcShop/Data',
                'LcShop\Web'   => ROOTPATH . 'LcShop/Web',
        ];



Add LC5 services in App\Config\Services.php


        public static function shopcart($getShared = true)
        {
                if ($getShared)
                {
                return static::getSharedInstance('shopcart');
                }

                return new \LcShop\Web\Controllers\Shop\Cart();
        }

## Base Controller 

Add helpers requirements in App\Controllers\BaseController.php

        protected $helpers = [... 'lcshop_frontend'];

Add getShopSettings method in App\Controllers\BaseController.php

        //--------------------------------------------------------------------
        protected function getShopSettings()
        {
                // 
                $shop_settings_model = new \LcShop\Data\Models\ShopSettingsModel();
                if (!$shop_settings_entity = $shop_settings_model->asObject()->where('id_app', __web_app_id__ )->first()) {
                        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
                }
                // 
                return $shop_settings_entity;
        }
