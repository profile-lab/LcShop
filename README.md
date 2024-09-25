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

### Add Class variables in App\Controllers\BaseController.php

        ...
        // 
        protected $all_order_status;
        protected $all_order_status_labels;
        protected $all_payment_status;
        protected $all_payment_status_labels;
        protected $all_payment_type;
        protected $all_payment_type_labels;
        protected $all_spedizioni_type;
        protected $all_spedizioni_type_labels;
        // 

#

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
                        $this->getAllOrderStatusData();
                        //  
                        return $shop_settings_entity;
                }
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        }

        //--------------------------------------------------------------------
        protected function getAllOrderStatusData()
        {
                // 
                $this->all_order_status = [
                (object) ['val' => 'CART', 'nome' => 'CART'],
                (object) ['val' => 'ORDER', 'nome' => 'ORDER'],
                (object) ['val' => 'IN_PROGRESS', 'nome' => 'IN_PROGRESS'],
                (object) ['val' => 'SHIPPED', 'nome' => 'SHIPPED'],
                (object) ['val' => 'IN_DELIVERY', 'nome' => 'IN_DELIVERY'],
                (object) ['val' => 'DELIVERED', 'nome' => 'DELIVERED'],
                (object) ['val' => 'DELETED', 'nome' => 'DELETED'],
                (object) ['val' => 'DELETED_BY_USER', 'nome' => 'DELETED_BY_USER'],
                (object) ['val' => 'DELETED_BY_ADMIN', 'nome' => 'DELETED_BY_ADMIN'],
                ];
                $this->all_payment_status = [
                (object) ['val' => 'PENDING', 'nome' => 'PENDING'],
                (object) ['val' => 'COMPLETED', 'nome' => 'COMPLETED'],
                (object) ['val' => 'ERROR', 'nome' => 'ERROR'],
                (object) ['val' => 'FREE', 'nome' => 'FREE'],
                (object) ['val' => 'REFUNDED', 'nome' => 'REFUNDED'],
                (object) ['val' => 'CANCELED', 'nome' => 'CANCELED'],
                ];
                $this->all_payment_type = [
                (object) ['val' => 'STRIPE', 'nome' => 'STRIPE'],
                (object) ['val' => 'CASH', 'nome' => 'CASH'],
                (object) ['val' => 'CC', 'nome' => 'CC'],
                (object) ['val' => 'BANK', 'nome' => 'BANK'],
                (object) ['val' => 'PAYPAL', 'nome' => 'PAYPAL'],
                (object) ['val' => 'AT_DELIVERY', 'nome' => 'AT_DELIVERY'],
                (object) ['val' => 'FREE', 'nome' => 'FREE'],
                ];
                $this->all_spedizioni_type = ['COURIER', 'PICKUP', 'AT_DELIVERY', 'FREE'];
                // 
                $this->all_order_status_labels = [
                'CART' => 'Carrello',
                'ORDER' => 'Ordine',
                'IN_PROGRESS' => 'In lavorazione',
                'SHIPPED' => 'Spedito',
                'IN_DELIVERY' => 'In consegna',
                'DELIVERED' => 'Consegnato',
                'DELETED' => 'Eliminato',
                'DELETED_BY_USER' => 'Eliminato dall\'utente',
                'DELETED_BY_ADMIN' => 'Eliminato dall\'admin',
                ];
                $this->all_payment_status_labels = [
                'PENDING' => 'In attesa',
                'COMPLETED' => 'Completato',
                'ERROR' => 'Errore',
                'FREE' => 'Gratis',
                'REFUNDED' => 'Rimborsato',
                'CANCELED' => 'Annullato',
                ];
                // 
                $this->all_payment_type_labels = [
                'STRIPE' => 'Stripe',
                'CASH' => 'Contanti',
                'CC' => 'Carta di credito',
                'BANK' => 'Bonifico',
                'PAYPAL' => 'Paypal',
                'AT_DELIVERY' => 'Contrassegno',
                'FREE' => 'Gratis',
                ];
                // 
                $this->all_spedizioni_type_labels = [
                'COURIER' => 'Corriere',
                'PICKUP' => 'Ritiro in sede',
                'AT_DELIVERY' => 'Contrassegno',
                'FREE' => 'Gratis',
                ];
                // 
        }

## Site Header 

### Add css Link Tag  App\Views\layout\components\header-tag.php

        <link rel="stylesheet" href="<?= __base_assets_folder__.'lc-admin-assets/frontend/shop-fe-base.css' ?>" />
