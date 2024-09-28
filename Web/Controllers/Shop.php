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
// 
use LcShop\Data\Models\ShopOrdersModel;
use LcShop\Data\Entities\ShopOrder;
use LcShop\Data\Models\ShopOrdersItemsModel;
use LcShop\Data\Entities\ShopOrdersItem;

// use LcShop\Data\Models\ShopOrdersItemsModel;

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
    private $shop_orders_model;
    private $shop_orders_items_model;
    // private $shop_order;
    // private $shop_order_item;
    // 
    private $shop_action;
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
        $this->shop_orders_model = new ShopOrdersModel();
        $this->shop_orders_model->setForFrontemd();
        $this->shop_orders_items_model = new ShopOrdersItemsModel();
        $this->shop_orders_items_model->setForFrontemd();


        // 
        $this->shop_action = new ShopAction();
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
        $products_archive_qb_category = null;

        $shop_page_type = 'shop';
        $shop_category_guid = null;

        if ($category_guid != null) {
            if ($curr_entity =  $this->shop_products_cat_model->where('guid', $category_guid)->asObject()->first()) {
                $products_archive_qb_category =  $curr_entity->id;
                $shop_page_type = 'shop_category';
                $shop_category_guid = $category_guid;
            } else {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
        } else {
            $pages_model = new PagesModel();
            $pages_model->setForFrontemd();

            $shop_home_guid = 'shop';
            if ($setting_shophomepage_guid = trim($this->shop_settings->shop_home)) {
                $shop_home_guid = trim(str_replace(['/'], '', $setting_shophomepage_guid));
            }

            if ($curr_entity = $pages_model->asObject()->orderBy('id', 'DESC')->where('guid', $shop_home_guid)->first()) {
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
        // 
        $productsArchive = $this->shop_action->getShopProductsArchive($products_archive_qb_category);
        $curr_entity->products_archive  = $productsArchive->products_archive;
        $curr_entity->pager  = $productsArchive->pager;

        // 
        $this->web_ui_date->fill((array)$curr_entity);
        $this->web_ui_date->entity_rows = $pages_entity_rows;
        // 
        $this->web_ui_date->shop_page_type  = $shop_page_type;
        $this->web_ui_date->shop_category_guid = $shop_category_guid;
        // 
        return view(customOrDefaultViewFragment('shop/archive', 'LcShop'), $this->web_ui_date->toArray());
        //
        // if (appIsFile($this->base_view_filesystem . 'shop/archive.php')) {
        //     return view($this->base_view_namespace . 'shop/archive', $this->web_ui_date->toArray());
        // }
        // throw \CodeIgniter\Exceptions\FrameworkException::forInvalidFile('View file not found - shop/archive.php - ');
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
        return view(customOrDefaultViewFragment('shop/detail', 'LcShop'), $this->web_ui_date->toArray());
        //
        // if (appIsFile($this->base_view_filesystem . 'shop/detail.php')) {
        //     return view($this->base_view_namespace . 'shop/detail', $this->web_ui_date->toArray());
        // }
        // throw \CodeIgniter\Exceptions\FrameworkException::forInvalidFile('View file not found - shop/detail.php - ');
    }

    //--------------------------------------------------------------------
    public function makeOrder()
    {
        if ($this->cart->checkCartAction()) {
            return redirect()->to(site_url(uri_string()));
        }

        // $order_data = $this->getOrderData();
        $order_data = $this->getSessionOrderData();
        $all_user_data = $this->appuser->getAllUserData();



        if ($sess_order_data = session()->get('order_data')) {
            $order_data->ship_name = $this->request->getPost('ship_name') ? $this->request->getPost('ship_name') : (isset($sess_order_data->ship_name) ? $sess_order_data->ship_name : '');
            $order_data->ship_surname = $this->request->getPost('ship_surname') ? $this->request->getPost('ship_surname') : (isset($sess_order_data->ship_surname) ? $sess_order_data->ship_surname : '');
            $order_data->ship_country = $this->request->getPost('ship_country') ? $this->request->getPost('ship_country') : (isset($sess_order_data->ship_country) ? $sess_order_data->ship_country : '');
            $order_data->ship_district = $this->request->getPost('ship_district') ? $this->request->getPost('ship_district') : (isset($sess_order_data->ship_district) ? $sess_order_data->ship_district : '');
            $order_data->ship_city = $this->request->getPost('ship_city') ? $this->request->getPost('ship_city') : (isset($sess_order_data->ship_city) ? $sess_order_data->ship_city : '');
            $order_data->ship_zip = $this->request->getPost('ship_zip') ? $this->request->getPost('ship_zip') : (isset($sess_order_data->ship_zip) ? $sess_order_data->ship_zip : '');
            $order_data->ship_address = $this->request->getPost('ship_address') ? $this->request->getPost('ship_address') : (isset($sess_order_data->ship_address) ? $sess_order_data->ship_address : '');
            $order_data->ship_address_number = $this->request->getPost('ship_address_number') ? $this->request->getPost('ship_address_number') : (isset($sess_order_data->ship_address_number) ? $sess_order_data->ship_address_number : '');
            $order_data->ship_phone = $this->request->getPost('ship_phone') ? $this->request->getPost('ship_phone') : (isset($sess_order_data->ship_phone) ? $sess_order_data->ship_phone : '');
            $order_data->ship_email = $this->request->getPost('ship_email') ? $this->request->getPost('ship_email') : (isset($sess_order_data->ship_email) ? $sess_order_data->ship_email : '');
            $order_data->ship_infos = $this->request->getPost('ship_infos') ? $this->request->getPost('ship_infos') : (isset($sess_order_data->ship_infos) ? $sess_order_data->ship_infos : '');
        }

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
            } else {
                if ($this->request->getPost('ship_send') == 'next') {

                    $validate_rules = [
                        'ship_name' => ['label' => 'Nome', 'rules' => 'required'],
                        'ship_surname' => ['label' => 'Cognome', 'rules' => 'required'],
                        'ship_district' => ['label' => 'Provincia', 'rules' => 'required'],
                        'ship_city' => ['label' => 'Città', 'rules' => 'required'],
                        'ship_zip' => ['label' => 'Cap', 'rules' => 'required'],
                        'ship_address' => ['label' => 'Indirizzo', 'rules' => 'required'],
                        'ship_address_number' => ['label' => 'Civico', 'rules' => 'required'],
                        'ship_phone' => ['label' => 'Telefono', 'rules' => 'required'],
                        'ship_email' => ['label' => 'Email', 'rules' => 'required|valid_email'],

                    ];

                    if ($this->validate($validate_rules)) {

                        // 
                        $order_data->ship_name = $this->request->getPost('ship_name');
                        $order_data->ship_surname = $this->request->getPost('ship_surname');
                        $order_data->ship_country = $this->request->getPost('ship_country');
                        $order_data->ship_district = $this->request->getPost('ship_district');
                        $order_data->ship_city = $this->request->getPost('ship_city');
                        $order_data->ship_zip = $this->request->getPost('ship_zip');
                        $order_data->ship_address = $this->request->getPost('ship_address');
                        $order_data->ship_address_number = $this->request->getPost('ship_address_number');
                        $order_data->ship_phone = $this->request->getPost('ship_phone');
                        $order_data->ship_email = $this->request->getPost('ship_email');
                        $order_data->ship_infos = $this->request->getPost('ship_infos');
                        // 
                        session()->set('order_data', $order_data);
                        return redirect()->route('web_shop_payment');
                    } else {
                        session()->setFlashdata('ui_mess', $this->validator->getErrors());
                        session()->setFlashdata('ui_mess_type', 'alert alert-danger');
                    }
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
        $curr_entity->order_data = $order_data;


        $this->web_ui_date->fill((array)$curr_entity);
        $this->web_ui_date->entity_rows = $pages_entity_rows;
        // 
        return view(customOrDefaultViewFragment('shop/make-order', 'LcShop'), $this->web_ui_date->toArray());
        //
        // if (appIsFile($this->base_view_filesystem . 'shop/make-order.php')) {
        //     return view($this->base_view_namespace . 'shop/make-order', $this->web_ui_date->toArray());
        // }
        // throw \CodeIgniter\Exceptions\FrameworkException::forInvalidFile('View file not found - shop/make-order.php - ');
    }

    //--------------------------------------------------------------------
    public function payment()
    {
        if ($this->cart->checkCartAction()) {
            return redirect()->to(site_url(uri_string()));
        }

        // $order_data = $this->getOrderData();
        $order_data = $this->getSessionOrderData();
        $all_user_data = $this->appuser->getAllUserData();


        if ($sess_order_data = session()->get('order_data')) {
            // d($sess_order_data);

            $order_data->pay_name = $this->request->getPost('pay_name') ? $this->request->getPost('pay_name') : (isset($sess_order_data->pay_name) ? $sess_order_data->pay_name : $all_user_data->name);
            $order_data->pay_surname = $this->request->getPost('pay_surname') ? $this->request->getPost('pay_surname') : (isset($sess_order_data->pay_surname) ? $sess_order_data->pay_surname : $all_user_data->surname);
            $order_data->pay_country = $this->request->getPost('pay_country') ? $this->request->getPost('pay_country') : (isset($sess_order_data->pay_country) ? $sess_order_data->pay_country : $all_user_data->country);
            $order_data->pay_district = $this->request->getPost('pay_district') ? $this->request->getPost('pay_district') : (isset($sess_order_data->pay_district) ? $sess_order_data->pay_district : $all_user_data->district);
            $order_data->pay_city = $this->request->getPost('pay_city') ? $this->request->getPost('pay_city') : (isset($sess_order_data->pay_city) ? $sess_order_data->pay_city : $all_user_data->city);
            $order_data->pay_zip = $this->request->getPost('pay_zip') ? $this->request->getPost('pay_zip') : (isset($sess_order_data->pay_zip) ? $sess_order_data->pay_zip : $all_user_data->zip);
            $order_data->pay_address = $this->request->getPost('pay_address') ? $this->request->getPost('pay_address') : (isset($sess_order_data->pay_address) ? $sess_order_data->pay_address : $all_user_data->address);
            $order_data->pay_address_number = $this->request->getPost('pay_address_number') ? $this->request->getPost('pay_address_number') : (isset($sess_order_data->pay_address_number) ? $sess_order_data->pay_address_number : $all_user_data->address_number);
            $order_data->pay_phone = $this->request->getPost('pay_phone') ? $this->request->getPost('pay_phone') : (isset($sess_order_data->pay_phone) ? $sess_order_data->pay_phone : $all_user_data->tel_num);
            $order_data->pay_email = $this->request->getPost('pay_email') ? $this->request->getPost('pay_email') : (isset($sess_order_data->pay_email) ? $sess_order_data->pay_email : $all_user_data->email);
            $order_data->pay_fiscal = $this->request->getPost('pay_fiscal') ? $this->request->getPost('pay_fiscal') : (isset($sess_order_data->pay_fiscal) ? $sess_order_data->pay_fiscal : '');
            $order_data->pay_vat = $this->request->getPost('pay_vat') ? $this->request->getPost('pay_vat') : (isset($sess_order_data->pay_vat) ? $sess_order_data->pay_vat : '');
            $order_data->pay_infos = $this->request->getPost('pay_infos') ? $this->request->getPost('pay_infos') : (isset($sess_order_data->pay_infos) ? $sess_order_data->pay_infos : '');
        }

        if ($this->request->getPost()) {
            if ($this->request->getPost('action') == 'login') {
                if ($this->appuser->loginPostAction($this->request->getPost())) {
                    return redirect()->route('web_shop_payment');
                } else {
                    session()->setFlashdata('ui_mess', 'Nome utente o password errati');
                    session()->setFlashdata('ui_mess_type', 'alert alert-danger');
                }
            } else {
                if ($this->request->getPost('pay_send') == 'next') {
                    $validate_rules = [
                        'pay_name' => ['label' => 'Nome', 'rules' => 'required'],
                        'pay_surname' => ['label' => 'Cognome', 'rules' => 'required'],
                        'pay_district' => ['label' => 'Provincia', 'rules' => 'required'],
                        'pay_city' => ['label' => 'Città', 'rules' => 'required'],
                        'pay_zip' => ['label' => 'Cap', 'rules' => 'required'],
                        'pay_address' => ['label' => 'Indirizzo', 'rules' => 'required'],
                        'pay_address_number' => ['label' => 'Civico', 'rules' => 'required'],
                        'pay_phone' => ['label' => 'Telefono', 'rules' => 'required'],
                        'pay_email' => ['label' => 'Email', 'rules' => 'required|valid_email'],

                    ];

                    if ($this->validate($validate_rules)) {
                        // 
                        $order_data->pay_name = $this->request->getPost('pay_name');
                        $order_data->pay_surname = $this->request->getPost('pay_surname');
                        $order_data->pay_fiscal = $this->request->getPost('pay_fiscal');
                        $order_data->pay_vat = $this->request->getPost('pay_vat');
                        $order_data->pay_country = $this->request->getPost('pay_country');
                        $order_data->pay_district = $this->request->getPost('pay_district');
                        $order_data->pay_city = $this->request->getPost('pay_city');
                        $order_data->pay_zip = $this->request->getPost('pay_zip');
                        $order_data->pay_address = $this->request->getPost('pay_address');
                        $order_data->pay_address_number = $this->request->getPost('pay_address_number');
                        $order_data->pay_phone = $this->request->getPost('pay_phone');
                        $order_data->pay_email = $this->request->getPost('pay_email');
                        $order_data->pay_infos = $this->request->getPost('pay_infos');
                        // 
                        session()->set('order_data', $order_data);

                        $shop_order = new ShopOrder();
                        $shop_order->fill((array)$order_data);
                        $shop_order->user_id = $this->appuser->getUserId();
                        $this->shop_orders_model->save($shop_order);

                        // 
                        $new_id = $this->shop_orders_model->getInsertID();
                        // 
                        if (is_array($order_data->products)) {
                            foreach ($order_data->products as $product) {
                                $shop_order_item = new ShopOrdersItem();
                                $shop_order_item->fill((array)$product);
                                $shop_order_item->order_id = $new_id;
                                $shop_order_item->user_id = $this->appuser->getUserId();
                                $this->shop_orders_items_model->save($shop_order_item);
                            }
                        }
                        $this->cart->svuotaCarrello();
                        return redirect()->route('web_shop_pay_now', [$new_id]);
                    } else {
                        session()->setFlashdata('ui_mess', $this->validator->getErrors());
                        session()->setFlashdata('ui_mess_type', 'alert alert-danger');
                    }

                    // $shop_order_item = new ShopOrdersItem();
                    // $this->shop_orders_items_model->save($shop_order_item);
                    // // $this->shop_order->order_status = 'PENDING';
                    // dd($this->shop_order);

                    // return redirect()->route('web_shop_payment');
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
        $curr_entity->order_data = $order_data;


        $this->web_ui_date->fill((array)$curr_entity);
        $this->web_ui_date->entity_rows = $pages_entity_rows;
        // 
        return view(customOrDefaultViewFragment('shop/payment', 'LcShop'), $this->web_ui_date->toArray());
        //
        // if (appIsFile($this->base_view_filesystem . 'shop/payment.php')) {
        //     return view($this->base_view_namespace . 'shop/payment', $this->web_ui_date->toArray());
        // }
        // throw \CodeIgniter\Exceptions\FrameworkException::forInvalidFile('View file not found - shop/payment.php - ');
    }
    //--------------------------------------------------------------------
    private function getSessionOrderData()
    {

        $sess_order_data = session()->get('order_data');
        $all_user_data = $this->appuser->getAllUserData();
        // if(shopcart)
        $cart = \Config\Services::shopcart();
        if ($cart != null) {
            $site_cart = $cart->getSiteCart();
        }

        $orderData = (object)[
            'ship_name' => isset($sess_order_data) ? ((isset($sess_order_data->ship_name)) ? $sess_order_data->ship_name : '') : (isset($all_user_data->name) ? $all_user_data->name : ''),
            'ship_surname' => isset($sess_order_data) ? ((isset($sess_order_data->ship_surname)) ? $sess_order_data->ship_surname : '') : (isset($all_user_data->surname) ? $all_user_data->surname : ''),
            'ship_country' => isset($sess_order_data) ? ((isset($sess_order_data->ship_country)) ? $sess_order_data->ship_country : '') : (isset($all_user_data->country) ? $all_user_data->country : ''),
            'ship_district' => isset($sess_order_data) ? ((isset($sess_order_data->ship_district)) ? $sess_order_data->ship_district : '') : (isset($all_user_data->district) ? $all_user_data->district : ''),
            'ship_city' => isset($sess_order_data) ? ((isset($sess_order_data->ship_city)) ? $sess_order_data->ship_city : '') : (isset($all_user_data->city) ? $all_user_data->city : ''),
            'ship_zip' => isset($sess_order_data) ? ((isset($sess_order_data->ship_zip)) ? $sess_order_data->ship_zip : '') : (isset($all_user_data->cap) ? $all_user_data->cap : ''),
            'ship_address' => isset($sess_order_data) ? ((isset($sess_order_data->ship_address)) ? $sess_order_data->ship_address : '') : (isset($all_user_data->address) ? $all_user_data->address : ''),
            'ship_address_number' => isset($sess_order_data) ? ((isset($sess_order_data->ship_address_number)) ? $sess_order_data->ship_address_number : '') : (isset($all_user_data->street_number) ? $all_user_data->street_number : ''),
            'ship_phone' => isset($sess_order_data) ? ((isset($sess_order_data->ship_phone)) ? $sess_order_data->ship_phone : '') : (isset($all_user_data->tel_num) ? $all_user_data->tel_num : ''),
            'ship_email' => isset($sess_order_data) ? ((isset($sess_order_data->ship_email)) ? $sess_order_data->ship_email : '') : (isset($all_user_data->email) ? $all_user_data->email : ''),
            'ship_infos' => isset($sess_order_data) ? ((isset($sess_order_data->ship_infos)) ? $sess_order_data->ship_infos : '') : '',
            'save_in_user' => false,
            // 
            'pay_name' => isset($sess_order_data) ? ((isset($sess_order_data->pay_name)) ? $sess_order_data->pay_name : '') : (isset($all_user_data->name) ? $all_user_data->name : ''),
            'pay_surname' => isset($sess_order_data) ? ((isset($sess_order_data->pay_surname)) ? $sess_order_data->pay_surname : '') : (isset($all_user_data->surname) ? $all_user_data->surname : ''),
            'pay_country' => isset($sess_order_data) ? ((isset($sess_order_data->pay_country)) ? $sess_order_data->pay_country : '') : (isset($all_user_data->country) ? $all_user_data->country : ''),
            'pay_district' => isset($sess_order_data) ? ((isset($sess_order_data->pay_district)) ? $sess_order_data->pay_district : '') : (isset($all_user_data->district) ? $all_user_data->district : ''),
            'pay_city' => isset($sess_order_data) ? ((isset($sess_order_data->pay_city)) ? $sess_order_data->pay_city : '') : (isset($all_user_data->city) ? $all_user_data->city : ''),
            'pay_zip' => isset($sess_order_data) ? ((isset($sess_order_data->pay_zip)) ? $sess_order_data->pay_zip : '') : (isset($all_user_data->cap) ? $all_user_data->cap : ''),
            'pay_address' => isset($sess_order_data) ? ((isset($sess_order_data->pay_address)) ? $sess_order_data->pay_address : '') : (isset($all_user_data->address) ? $all_user_data->address : ''),
            'pay_address_number' => isset($sess_order_data) ? ((isset($sess_order_data->pay_address_number)) ? $sess_order_data->pay_address_number : '') : (isset($all_user_data->street_number) ? $all_user_data->street_number : ''),
            'pay_phone' => isset($sess_order_data) ? ((isset($sess_order_data->pay_phone)) ? $sess_order_data->pay_phone : '') : (isset($all_user_data->tel_num) ? $all_user_data->tel_num : ''),
            'pay_email' => isset($sess_order_data) ? ((isset($sess_order_data->pay_email)) ? $sess_order_data->pay_email : '') : (isset($all_user_data->email) ? $all_user_data->email : ''),
            'pay_fiscal' => isset($sess_order_data) ? ((isset($sess_order_data->pay_fiscal)) ? $sess_order_data->pay_fiscal : '') : '',
            'pay_vat' => isset($sess_order_data) ? ((isset($sess_order_data->pay_vat)) ? $sess_order_data->pay_vat : '') : '',
            'pay_infos' => isset($sess_order_data) ? ((isset($sess_order_data->pay_infos)) ? $sess_order_data->pay_infos : '') : '',
            // 
            'imponibile_total' => (isset($site_cart->imponibile_total)) ? $site_cart->imponibile_total :  '0.00',
            'iva_total' => (isset($site_cart->iva_total)) ? $site_cart->iva_total :  '0.00',
            'pay_total' => (isset($site_cart->pay_total)) ? $site_cart->pay_total :  '0.00',
            'promo_total' => (isset($site_cart->promo_total)) ? $site_cart->promo_total :  '0.00',
            'spese_spedizione' => (isset($site_cart->spese_spedizione)) ? $site_cart->spese_spedizione :  '0.00',
            'spese_spedizione_imponibile' => (isset($site_cart->spese_spedizione_imponibile)) ? $site_cart->spese_spedizione_imponibile :  '0.00',
            'total' => (isset($site_cart->total)) ? $site_cart->total :  '0.00',
            'peso_totale_grammi' => (isset($site_cart->peso_totale_grammi)) ? $site_cart->peso_totale_grammi :  '0.00',
            'peso_totale_kg' => (isset($site_cart->peso_totale_kg)) ? $site_cart->peso_totale_kg :  '0.00',
            'referenze' => (isset($site_cart->referenze)) ? $site_cart->referenze :  '0.00',
            'referenze_totali' => (isset($site_cart->referenze_totali)) ? $site_cart->referenze_totali :  '0.00',
            'spedizione_name' => (isset($site_cart->spedizione_name)) ? $site_cart->spedizione_name :  '0.00',
            // 
            'products' => (isset($site_cart->products)) ? $site_cart->products : [],
            // 
        ];

        return $orderData;
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
        // 
        return view(customOrDefaultViewFragment('shop/site-cart', 'LcShop'), $this->web_ui_date->toArray());
        //
        // if (appIsFile($this->base_view_filesystem . 'shop/site-cart.php')) {
        //     return view($this->base_view_namespace . 'shop/site-cart', $this->web_ui_date->toArray());
        // }
        // throw \CodeIgniter\Exceptions\FrameworkException::forInvalidFile('View file not found - shop/site-cart.php - ');
    }
}
