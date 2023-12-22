<?php

namespace LcShop\Web\Controllers;

use Lc5\Data\Models\PagesModel;
use LcShop\Data\Models\ShopProductsModel;
use LcShop\Data\Models\ShopSettingsModel;

// 
use LcShop\Data\Models\ShopProductsCategoriesModel;
use LcShop\Data\Models\ShopProductsTagsModel;
use LcShop\Data\Models\ShopProductsVariationsModel;
use LcShop\Data\Models\ShopProductsSizesModel;

use Config\Services;

use stdClass;

class Shop extends \Lc5\Web\Controllers\MasterWeb
{
    protected $lcshop_views_namespace = '\LcShop\Web\Views/';

    private $shop_products_cat_model;
    private $shop_products_model;
    private $shop_products_tags_model;
    private $shop_products_variations_model;
    private $shop_products_sizes_model;
    // 
    private $cart;
    private $appuser;
    private $categories;
    private $tags;
    private $variations;
    private $sizes;

    private $shop_settings;

    //--------------------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
        // 
        $this->shop_settings = $this->getShopSettings(__web_app_id__);
        // 
        $this->shop_products_cat_model = new ShopProductsCategoriesModel();
        $this->shop_products_cat_model->setForFrontemd();
        $this->shop_products_model = new ShopProductsModel();
        $this->shop_products_model->setForFrontemd();
        $this->shop_products_model->shop_settings = $this->shop_settings;

        $this->shop_products_tags_model = new ShopProductsTagsModel();
        $this->shop_products_tags_model->setForFrontemd();
        $this->shop_products_variations_model = new ShopProductsVariationsModel();
        $this->shop_products_variations_model->setForFrontemd();
        $this->shop_products_sizes_model = new ShopProductsSizesModel();
        $this->shop_products_sizes_model->setForFrontemd();
        // 
        $this->cart = Services::shopcart(); // new Cart();
        $this->appuser = Services::appuser();

        // 

        // 
        $this->categories = $this->shop_products_cat_model->asObject()->findAll();
        foreach ($this->categories as $category) {
            $category->permalink = route_to(__locale_uri__ . 'web_shop_category', $category->guid);
        }
        $this->web_ui_date->__set('categories', $this->categories);
        // 
        $this->tags = $this->shop_products_tags_model->asObject()->findAll();
        // foreach ($this->tags as $tag) {
        //     $tag->permalink = route_to(__locale_uri__ . 'web_shop_category', $category->guid);
        // }
        $this->web_ui_date->__set('tags', $this->tags);
        // 
        $this->variations = $this->shop_products_variations_model->asObject()->findAll();
        $this->web_ui_date->__set('variations', $this->variations);
        // 
        $this->sizes = $this->shop_products_sizes_model->asObject()->findAll();
        $this->web_ui_date->__set('sizes', $this->sizes);
        // 

        // 
        $this->web_ui_date->__set('request', $this->req);
        // 


    }


    //--------------------------------------------------------------------
    public function index($category_guid = null)
    {

        if ($this->cart->checkCartAction()) {
            return redirect()->to(site_url(uri_string()));
        }
        $pages_entity_rows = null;
        $products_archive_qb = $this->shop_products_model->asObject();
        $products_archive_qb->where('parent', 0);
        // $products_archive_qb->where('(parent IS NULL OR parent <  1 )');
        if ($category_guid != null) {
            if ($curr_entity =  $this->shop_products_cat_model->where('guid', $category_guid)->asObject()->first()) {
                $products_archive_qb->where('category', $curr_entity->id);
            } else {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
        } else {
            $pages_model = new PagesModel();
            $pages_model->setForFrontemd();
            if ($curr_entity = $pages_model->asObject()->orderBy('id', 'DESC')->where('guid', 'shop')->first()) {
                $pages_entity_rows = $this->getEntityRows($curr_entity->id, 'pages');
            } else {
                $curr_entity = new stdClass();
                $curr_entity->titolo = 'Shop';
                $curr_entity->guid = 'shop';
                $curr_entity->testo = '';
                $curr_entity->seo_title = 'Il nostro e-commerce';
                $curr_entity->seo_description = 'Naviga il nostro e-commerce e acquista i nostri prodotti';
            }
        }
        if ($products_archive = $this->shop_products_model->asObject()->findAll()) {
            foreach ($products_archive as $product) {
                $product->abstract = word_limiter(strip_tags($product->testo), 20);
                // $post->abstract = character_limiter(strip_tags( $post->testo ), 100);
                $product->permalink = route_to(__locale_uri__ . 'web_shop_detail', $product->guid);
                // 
                $this->shop_products_model->extendProduct($product, 'min');
                // $this->extendProduct($product, 'min');
                // 
            }
            $curr_entity->products_archive  = $products_archive;
        }
        // dd($products_archive);
        $this->web_ui_date->fill((array)$curr_entity);
        $this->web_ui_date->entity_rows = $pages_entity_rows;

        //
        if (appIsFile($this->base_view_filesystem . 'shop/archive.php')) {
            return view($this->base_view_namespace . 'shop/archive', $this->web_ui_date->toArray());
        }
        throw \CodeIgniter\Exceptions\FrameworkException::forInvalidFile('View file not found - shop/archive.php - ');



        // return view($this->base_view_namespace . 'shop/archive', $this->web_ui_date->toArray());
    }

    //--------------------------------------------------------------------
    public function detail($product_guid, $model_id = null)
    {
        if ($this->cart->checkCartAction()) {
            return redirect()->to(site_url(uri_string()));
        }
        $pages_entity_rows = null;
        if (!$model_id) {

            $products_archive_qb = $this->shop_products_model->asObject();
            $products_archive_qb->where('parent', 0);
            if (!$curr_entity = $this->shop_products_model->where('guid', $product_guid)->asObject()->first()) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
            $this->shop_products_model->extendProduct($curr_entity, 'min');
        } else {
            // $products_archive_qb = $this->shop_products_model->asObject();
            // $products_archive_qb->where('parent', 0);
            // if (!$curr_parent_entity = $this->shop_products_model->where('guid', $product_guid)->asObject()->first()) {
            //     throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            // }
            $products_archive_qb = $this->shop_products_model->asObject();
            // $products_archive_qb->where('parent', $curr_parent_entity->id);
            if (!$curr_entity = $this->shop_products_model->where('id', $model_id)->asObject()->first()) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
            $this->shop_products_model->extendModelByParent($curr_entity, 'min');
            // d($curr_entity);
        }


        // $this->extendProduct($curr_entity, 'min');
        // dd($curr_entity);
        $this->web_ui_date->fill((array)$curr_entity);
        $this->web_ui_date->entity_rows = $pages_entity_rows;

        //
        if (appIsFile($this->base_view_filesystem . 'shop/detail.php')) {
            return view($this->base_view_namespace . 'shop/detail', $this->web_ui_date->toArray());
        }
        throw \CodeIgniter\Exceptions\FrameworkException::forInvalidFile('View file not found - shop/detail.php - ');

        // return view($this->base_view_namespace . 'shop/detail', $this->web_ui_date->toArray());
    }

    //--------------------------------------------------------------------
    public function makeOrder()
    {
        if ($this->cart->checkCartAction()) {
            return redirect()->to(site_url(uri_string()));
        }

        $ship_data = $this->appuser->getAllUserData();

        //
        if (!$sess_order_data = session()->get('order_data')) {
            $sess_order_data = [];
        } else {
            foreach ($sess_order_data as $key => $data) {
                $ship_data->{$key} = $data;
            }
        }
        // dd($ship_data);

        if ($this->request->getPost()) {
            if ($this->request->getPost('action') == 'login') {
                if ($this->appuser->loginPostAction($this->request->getPost())) {
                    // if ($get_redirect = $this->request->getGet('returnTo', false)) {
                    //     return redirect()->to(urldecode($get_redirect));
                    // } else {
                    //     return redirect()->route('web_dashboard');
                    // }
                    return redirect()->route('web_shop_make_order');
                } else {
                    session()->setFlashdata('ui_mess', 'Nome utente o password errati');
                    session()->setFlashdata('ui_mess_type', 'alert alert-danger');
                }
            }else{
                if ($this->request->getPost('ship_send') == 'next') {
                    $ship_data->ship_name = $this->request->getPost('ship_name');
                    $ship_data->ship_surname = $this->request->getPost('ship_surname');
                    $ship_data->ship_address = $this->request->getPost('ship_address');
                    $ship_data->ship_city = $this->request->getPost('ship_city');
                    $ship_data->ship_zip = $this->request->getPost('ship_zip');
                    $ship_data->ship_phone = $this->request->getPost('ship_phone');
                    $ship_data->ship_email = $this->request->getPost('ship_email');
                    $ship_data->ship_infos = $this->request->getPost('ship_infos');
                    $ship_data->save_in_user = $this->request->getPost('save_in_user');
                    session()->set('order_data', $ship_data);
                    return redirect()->route('web_shop_make_order');
                }
            }
            
        }

        //
        $pages_entity_rows = null;
        $products_archive_qb = $this->shop_products_model->asObject();
        $products_archive_qb->where('parent', 0);
        // $products_archive_qb->where('(parent IS NULL OR parent <  1 )');

        $pages_model = new PagesModel();
        $pages_model->setForFrontemd();
        if ($curr_entity = $pages_model->asObject()->orderBy('id', 'DESC')->where('guid', 'cart')->first()) {
            $pages_entity_rows = $this->getEntityRows($curr_entity->id, 'pages');
        } else {
            $curr_entity = new stdClass();
            $curr_entity->titolo = 'Ordina';
            $curr_entity->guid = 'ordina';
            $curr_entity->testo = '';
            $curr_entity->seo_title = 'Concludi il tuo ordine';
            $curr_entity->seo_description = 'Concludi il tuo ordine';
        }
        $curr_entity->ship_data = $ship_data;


        $this->web_ui_date->fill((array)$curr_entity);
        $this->web_ui_date->entity_rows = $pages_entity_rows;
        //
        if (appIsFile($this->base_view_filesystem . 'shop/make-order.php')) {
            return view($this->base_view_namespace . 'shop/make-order', $this->web_ui_date->toArray());
        }
        throw \CodeIgniter\Exceptions\FrameworkException::forInvalidFile('View file not found - shop/make-order.php - ');
    }

    //--------------------------------------------------------------------
    public function emptyCart()
    {
        $this->cart->svuotaCarrello();
        return redirect()->to(previous_url());
    }
    //--------------------------------------------------------------------
    public function cartIncrementQnt($row_key)
    {
        $this->cart->incrementRow($row_key);
        return redirect()->to(previous_url());
    }
    //--------------------------------------------------------------------
    public function cartDecrementQnt($row_key)
    {
        $this->cart->decrementRow($row_key);
        return redirect()->to(previous_url());
    }
    //--------------------------------------------------------------------
    public function cartRemoveRow($row_key)
    {
        $this->cart->removeRow($row_key);
        return redirect()->to(previous_url());
    }


    //--------------------------------------------------------------------
    public function carrello()
    {

        if ($this->cart->checkCartAction()) {
            return redirect()->to(site_url(uri_string()));
        }
        $pages_entity_rows = null;
        $products_archive_qb = $this->shop_products_model->asObject();
        $products_archive_qb->where('parent', 0);
        // $products_archive_qb->where('(parent IS NULL OR parent <  1 )');

        $pages_model = new PagesModel();
        $pages_model->setForFrontemd();
        if ($curr_entity = $pages_model->asObject()->orderBy('id', 'DESC')->where('guid', 'cart')->first()) {
            $pages_entity_rows = $this->getEntityRows($curr_entity->id, 'pages');
        } else {
            $curr_entity = new stdClass();
            $curr_entity->titolo = 'Carrello';
            $curr_entity->guid = 'carrello';
            $curr_entity->testo = '';
            $curr_entity->seo_title = 'Il tuo carrello';
            $curr_entity->seo_description = 'Il tuo carrello';
        }

        $this->web_ui_date->fill((array)$curr_entity);
        $this->web_ui_date->entity_rows = $pages_entity_rows;
        if (appIsFile($this->base_view_filesystem . 'shop/site-cart.php')) {
            return view($this->base_view_namespace . 'shop/site-cart', $this->web_ui_date->toArray());
        }
        throw \CodeIgniter\Exceptions\FrameworkException::forInvalidFile('View file not found - shop/site-cart.php - ');
    }
}
