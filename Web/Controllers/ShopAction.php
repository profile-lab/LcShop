<?php

namespace LcShop\Web\Controllers;

use LcShop\Data\Models\ShopProductsModel;
use LcShop\Data\Models\ShopSettingsModel;
use LcShop\Data\Models\ShopSpeseSpedizionesModel;

class ShopAction extends \App\Controllers\BaseController
{
   
    //--------------------------------------------------------------------
    public function getShopProductsArchive()
    {
        $shop_products_model = new ShopProductsModel();
        $shop_products_model->setForFrontemd();

        if ($products_archive = $shop_products_model->asObject()->findAll()) {
            foreach ($products_archive as $product) {
                $product->abstract = word_limiter(strip_tags($product->testo), 20);
                $product->permalink = route_to(__locale_uri__ . 'web_shop_detail', $product->guid);
                // 
                $shop_products_model->extendProduct($product, 'min');
                // 
            }
            return $products_archive;
        }
        return null;
    }
}
