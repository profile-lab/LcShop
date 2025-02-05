<?php

namespace LcShop\Web\Controllers;

use LcShop\Data\Models\ShopOrdersItemsModel;
use LcShop\Data\Models\ShopOrdersModel;
use LcShop\Data\Models\ShopProductsModel;
use LcShop\Data\Models\ShopSettingsModel;
use LcShop\Data\Models\ShopSpeseSpedizionesModel;

class ShopAction extends \App\Controllers\BaseController
{

    //--------------------------------------------------------------------
    public function getShopProductsArchive($category_id = null, $pagination_limit = null)
    {

        $select_limit = 24;
        $select_limit = (env('custom.shop_pagination_limit')) ? env('custom.shop_pagination_limit') : $select_limit;
        $select_limit = $pagination_limit ? $pagination_limit : $select_limit;

        $shop_products_model = new ShopProductsModel();
        $shop_products_model->setForFrontemd();
        $products_archive_qb = $shop_products_model->where('parent <', 1)->where('public',1)->asObject();

        if ($category_id) {
            $products_archive_qb->where('category', $category_id);
        }

		$req = \Config\Services::request();

        if($req->getGet('s')){
            $products_archive_qb->like('nome', $req->getGet('s'));
        }

        if ($products_archive = $products_archive_qb->paginate(($select_limit))) { //findAll()) {
            foreach ($products_archive as $product) {
                $product->abstract = word_limiter(strip_tags($product->testo), 20);
                $product->permalink = route_to(__locale_uri__ . 'web_shop_detail', $product->guid);
                // 
                $shop_products_model->extendProduct($product, 'min');
                // 
            }
            $return = (object) [
                'products_archive' => $products_archive,
                'pager' => $shop_products_model->pager,
            ];
            return $return;
        }
        return null;
    }
    //--------------------------------------------------------------------
    public function getUserOrders($user_id)
    {
        $shop_orders_model = new ShopOrdersModel();
        $shop_orders_model->setForFrontemd();
        $shop_orders_model->where('user_id', $user_id);
        $shop_orders_model->orderBy('created_at', 'DESC');
        if ($user_orders_list = $shop_orders_model->asObject()->findAll()) {
            $this->getAllOrderStatusData();
            foreach ($user_orders_list as $order) {

                $order->order_status_label = (isset($this->all_order_status_labels[$order->order_status]) ? $this->all_order_status_labels[$order->order_status] : $order->order_status);
                $order->payment_type_label = (isset($this->all_payment_type_labels[$order->payment_type]) ? $this->all_payment_type_labels[$order->payment_type] : $order->payment_type);
                $order->payment_status_label = (isset($this->all_payment_status_labels[$order->payment_status]) ? $this->all_payment_status_labels[$order->payment_status] : $order->payment_status);

                $this->all_spedizioni_type;
                $this->all_spedizioni_type_labels;
                // 

            }
            return $user_orders_list;
        }
        return null;
    }
    //--------------------------------------------------------------------
    public function getUserOrderDett($user_id, $order_id)
    {
        $shop_orders_model = new ShopOrdersModel();
        $shop_orders_model->setForFrontemd();
        $shop_orders_model->where('user_id', $user_id);
        $shop_orders_model->where('id', $order_id);
        $shop_orders_model->orderBy('created_at', 'DESC');
        if ($order = $shop_orders_model->asObject()->first()) {
            $this->getAllOrderStatusData();

            $order->order_status_label = (isset($this->all_order_status_labels[$order->order_status]) ? $this->all_order_status_labels[$order->order_status] : $order->order_status);
            $order->payment_type_label = (isset($this->all_payment_type_labels[$order->payment_type]) ? $this->all_payment_type_labels[$order->payment_type] : $order->payment_type);
            $order->payment_status_label = (isset($this->all_payment_status_labels[$order->payment_status]) ? $this->all_payment_status_labels[$order->payment_status] : $order->payment_status);

            $order->shop_order_items = $this->getShopOrderItems($order->id);

            // $this->all_spedizioni_type;
            // $this->all_spedizioni_type_labels;
            // // 

            return $order;
        }
        return null;
    }
    //--------------------------------------------------------------------
    protected function getShopOrderItems($id_order)
    {
        $shop_orders_items_model = new ShopOrdersItemsModel();
        $shop_orders_items_model->where('order_id', $id_order);
        $shop_orders_items_model->orderBy('created_at', 'DESC');
        if ($shop_order_items = $shop_orders_items_model->asObject()->findAll()) {
            foreach ($shop_order_items as $item) {
                if ($item->id_modello) {

                    $item->product = $this->getProduct($item->id_modello);
                } else {
                    $item->product = $this->getProduct($item->id_prodotto);
                }
            }
            return $shop_order_items;
        }
        return null;
    }
    //--------------------------------------------------------------------
    protected function getProduct($product_id)
    {
        $shop_products_model = new ShopProductsModel();
        $shop_products_model->where('id', $product_id);
        if ($product = $shop_products_model->asObject()->first()) {
            $shop_products_model->extendProduct($product, 'min');
            return $product;
        }
        return null;
    }
}
