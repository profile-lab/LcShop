# Levelcomplete 5 Corebase Module

### (Is required Lc5 submodule LcUsers)

## Install git submodule

        git submodule add https://github.com/profile-lab/LcShop
        OR 
        git submodule add https://github.com/profile-lab/LcShop <destination folder>

## Update/Download submodules
        
        git submodule update --init --recursive

## Base Configuration and Namespaces


Add LC5 psr4 namespace in App\Config\Autoload.php
        
        public $psr4 = [
                ...
                'LcShop\Cms'   => ROOTPATH . 'LcShop/Cms',
                'LcShop\Data'   => ROOTPATH . 'LcShop/Data',
                'LcShop\Web'   => ROOTPATH . 'LcShop/Web',
                // 
                'Stripe'    => ROOTPATH . 'LcShop/Web/ThirdParty/stripe-php-13.18.0/lib',
                //
        ];


## App Services

Add LcShop and Siteuser services in App\Config\Services.php


        //--------------------------------------------------------------------
        public static function shopcart($getShared = true)
        {
                if ($getShared) {
                        return static::getSharedInstance('shopcart');
                }
                return new \LcShop\Web\Controllers\Cart();
        }

## Base Controller 

Add helpers requirements in App\Controllers\BaseController.php

        protected $helpers = [... 'lcshop_frontend'];

Add getShopSettings method in App\Controllers\BaseController.php

       //--------------------------------------------------------------------
        protected function getShopSettings($current_app_id)
        {
                if(!$current_app_id){
                        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
                }
                if (class_exists('\LcShop\Data\Models\ShopSettingsModel')) {
                        // 
                        $shop_settings_model = new \LcShop\Data\Models\ShopSettingsModel();
                        if (!$shop_settings_entity = $shop_settings_model->asObject()->where('id_app', $current_app_id)->first()) {
                                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
                        }
                        // 
                        return $shop_settings_entity;
                }
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        }
