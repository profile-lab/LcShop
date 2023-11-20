<?php

namespace LcShop\Cms\Controllers;


class LcShopConfigs
{

    //--------------------------------------------------------------------
    public static function getLcModulesMenu(): array
    {
        $mudules = [];
        $mudules['lcshop'] = (object) [
            'label' => 'Shop',
            'route' => 'lc_shop',
            'module' => 'lcshop',
            'ico' => 'basket',
            'items' => [
                (object) [
                    'label' => 'Lista Prodotti',
                    'route' => 'lc_shop_prod',
                    // 'route' => site_url(route_to('lc_shop_prod')),
                    'module_action' => 'index',
                ],
                (object) [
                    'label' => 'Nuovo Prodotto',
                    'route' => 'lc_shop_prod_new',
                    // 'route' => site_url(route_to('lc_shop_prod_new')),
                    'module_action' => 'newpost',
                ],
                (object) [
                    'label' => 'Categorie Prodotti',
                    'route' => 'lc_shop_prod_cat',
                    // 'route' => site_url(route_to('lc_shop_prod_cat')),
                    'module_action' => 'shopproductscat',
                ],
                (object) [
                    'label' => 'Settings',
                    'route' => 'lc_shop_settings',
                    // 'route' => site_url(route_to('lc_shop_settings')),
                    'module_action' => 'shopsettings',
                ],

            ]
        ];

        return $mudules;




        // return [
        //     'shop' =>  [ 'nome' => 'Shop', 'controller' => 'LcShopSettings', 'lc_base_route' => 'lc_shop_settings' ],
        //     'shop-products' =>  [ 'nome' => 'Prodotti', 'controller' => 'LcShopProducts', 'lc_base_route' => 'lc_shop_products' ],
        //     'shop-categories' =>  [ 'nome' => 'Categorie', 'controller' => 'LcShopCategories', 'lc_base_route' => 'lc_shop_categories' ],
        //     'shop-orders' =>  [ 'nome' => 'Ordini', 'controller' => 'LcShopOrders', 'lc_base_route' => 'lc_shop_orders' ],
        //     'shop-customers' =>  [ 'nome' => 'Clienti', 'controller' => 'LcShopCustomers', 'lc_base_route' => 'lc_shop_customers' ],
        //     'shop-settings' =>  [ 'nome' => 'Impostazioni', 'controller' => 'LcShopSettings', 'lc_base_route' => 'lc_shop_settings' ],
        //     'shop-shipping' =>  [ 'nome' => 'Spedizioni', 'controller' => 'LcShopShipping', 'lc_base_route' => 'lc_shop_shipping' ],
        //     'shop-payments' =>  [ 'nome' => 'Pagamenti', 'controller' => 'LcShopPayments', 'lc_base_route' => 'lc_shop_payments' ],
        //     'shop-coupons' =>  [ 'nome' => 'Coupons', 'controller' => 'LcShopCoupons', 'lc_base_route' => 'lc_shop_coupons' ],
        // ]; 


    }


    //--------------------------------------------------------------------
    public static function getShopToolsTabs()
    {
        $tabs_data_arr = [
            (object) [
                'label' => 'Config',
                'route' => site_url(route_to('lc_shop_settings')),
                'module_tab' => 'shopsettings',
            ],
            (object) [
                'label' => 'Taglie Prodotti',
                'route' => site_url(route_to('lc_shop_prod_sizes')),
                'module_tab' => 'shopproductssizes',
            ],
            (object) [
                'label' => 'Varianti Prodotti',
                'route' => site_url(route_to('lc_shop_prod_colors')),
                'module_tab' => 'shopproductscolors',
            ],
            (object) [
                'label' => 'Tags Prodotti',
                'route' => site_url(route_to('lc_shop_prod_tags')),
                'module_tab' => 'shopproductstags',
            ],
            (object) [
                'label' => 'Aliquote Iva',
                'route' => site_url(route_to('lc_shop_aliquote')),
                'module_tab' => 'shopaliquote',
            ]
        ];

        return $tabs_data_arr;
    }
}
