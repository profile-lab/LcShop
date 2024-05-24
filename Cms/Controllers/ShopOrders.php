<?php

namespace LcShop\Cms\Controllers;

use LcShop\Data\Models\ShopOrdersModel as CurrentModel;
use LcShop\Data\Entities\ShopOrder  as CurrentEntity;

use LcUsers\Data\Models\AppUsersDatasModel;
use LcShop\Data\Models\ShopOrdersItemsModel;

use CodeIgniter\API\ResponseTrait;
use Lc5\Cms\Controllers\MasterLc;

class ShopOrders extends MasterLc
{
    // protected $all_order_status = ['CART', 'ORDER', 'IN_PROGRESS', 'SHIPPED', 'IN_DELIVERY', 'DELIVERED', 'DELETED', 'DELETED_BY_USER', 'DELETED_BY_ADMIN'];
    // protected $all_payment_status = ['PENDING','COMPLETED','ERROR','FREE', 'REFUNDED', 'CANCELED'];
    // protected $all_payment_type = ['STRIPE','CASH','CC','BANK','PAYPAL','AT_DELIVERY','FREE'];
    // protected $all_spedizioni_type = ['COURIER','PICKUP','AT_DELIVERY','FREE'];
    protected $all_users;
    protected $current_shop_setting;
    // private $post_attributes;

    public function __construct()
    {
        parent::__construct();
        // 
        $this->module_name = 'Ordini';
        $this->route_prefix = 'lc_shop_orders';
        // 
        $this->lc_ui_date->__set('request', $this->req);
        $this->lc_ui_date->__set('route_prefix', $this->route_prefix);
        $this->lc_ui_date->__set('module_name', $this->module_name);
        $this->lc_ui_date->__set('currernt_module', 'lcshop');
        $this->lc_ui_date->__set('currernt_module_action', 'shoporders');

        // 
        $this->current_shop_setting  = $this->getShopSettings($this->getCurrApp());
        $this->lc_ui_date->__set('current_shop_setting', $this->current_shop_setting);
        //
        $this->lc_ui_date->__set('all_order_status_list', $this->all_order_status);
        $this->lc_ui_date->__set('all_order_status_labels', $this->all_order_status_labels);
        // 
        $this->lc_ui_date->__set('all_payment_status_list', $this->all_payment_status);
        $this->lc_ui_date->__set('all_payment_status_labels', $this->all_payment_status_labels);
        // 
        $this->lc_ui_date->__set('all_payment_type_list', $this->all_payment_type);
        $this->lc_ui_date->__set('all_payment_type_labels', $this->all_payment_type_labels);
        // 
        $this->lc_ui_date->__set('all_spedizioni_type_list', $this->all_spedizioni_type);
        $this->lc_ui_date->__set('all_spedizioni_type_labels', $this->all_spedizioni_type_labels);
        // 

        // 
        $this->all_users = $this->getUsers();
        $all_users_list = [];
        if ($this->all_users) {
            foreach ($this->all_users as $value) {
                $all_users_list[] = (object) ['val' => $value->id, 'nome' => $value->name . ' ' . $value->surname . ' (' . $value->email . ')'];
            }
        }
        $this->lc_ui_date->__set('all_users_list',  $all_users_list);
    }


    //--------------------------------------------------------------------
    private function getUser($user_id)
    {
        if ($this->all_users) {
            foreach ($this->all_users as $value) {
                if ($value->id == $user_id) {
                    return $value;
                }
            }
        }
    }
    //--------------------------------------------------------------------
    private function getUsers()
    {
        $user_model = new AppUsersDatasModel();
        return $user_model->where('status', 1)->select('id, name, surname, email, city, cf')->findAll();
    }
    //--------------------------------------------------------------------
    private function getShopOrderItems($order_id)
    {
        $shop_orders_items_model = new ShopOrdersItemsModel();
        return $shop_orders_items_model->where('order_id', $order_id)->findAll();

        // $user_model = new ShopOrdersItemsModel();
        // return $user_model->where('status', 1)->select('id, name, surname, email, city, cf')->findAll();
    }


    //--------------------------------------------------------------------
    public function index()
    {
        // 
        $current_model = new CurrentModel();
        // 
        $list = $current_model->orderBy('created_at', 'DESC')->findAll();
        if ($list) {
            foreach ($list as $value) {
                $value->user = $this->getUser($value->user_id);
            }
        }
        $this->lc_ui_date->list = $list;
        // 
        return view('LcShop\Cms\Views/orders/index', $this->lc_ui_date->toArray());
    }



    //--------------------------------------------------------------------
    public function edit($id)
    {
        $this->lc_ui_date->__set('back_to_list_btn_url', route_to($this->route_prefix));
        // 
        $current_model = new CurrentModel();
        if (!$curr_entity = $current_model->find($id)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        // 
        // 
        // dd($this->post_attributes); 
        // 
        if ($this->req->getPost()) {

            $is_falied = TRUE;
            $curr_entity->fill($this->req->getPost());

            $current_model->save($curr_entity);
            // 
            return redirect()->route($this->route_prefix . '_edit', [$curr_entity->id]);

            if ($is_falied) {
                $this->lc_ui_date->ui_mess =  ((isset($errMess)) ? $errMess : 'Utente non trovato! Controlla i dati inseriti!');
                $this->lc_ui_date->ui_mess_type = 'alert alert-danger';
            }
        }
        $order_items = $this->getShopOrderItems($curr_entity->id);
        $this->lc_ui_date->__set('order_items', $order_items);

        // 
        $this->lc_ui_date->entity = $curr_entity;
        return view('LcShop\Cms\Views/orders/scheda', $this->lc_ui_date->toArray());
    }




    // //--------------------------------------------------------------------
    // public function newpost()
    // {
    //     $this->lc_ui_date->__set('back_to_list_btn_url', route_to($this->route_prefix));
    //     // 
    //     $current_model = new CurrentModel();
    //     $curr_entity = new CurrentEntity();
    //     // 
    //     if ($this->req->getPost()) {
    //         $validate_rules = [
    //             'nome' => ['label' => 'Nome', 'rules' => 'required'],
    //         ];
    //         $is_falied = TRUE;
    //         $curr_entity->fill($this->req->getPost());
    //         if ($this->validate($validate_rules)) {
    //             $curr_entity->status = 1;
    //             if (!$this->req->getPost('val')) {
    //                 $curr_entity->val = url_title(trim($this->req->getPost('nome')), '-', TRUE);
    //             }
    //             $current_model->save($curr_entity);
    //             // 
    //             $new_id = $current_model->getInsertID();
    //             // 
    //             // $this->editEntityRows($new_id, 'pages');
    //             // 
    //             return redirect()->route($this->route_prefix . '_edit', [$new_id]);
    //         } else {
    //             $errMess = $this->lc_parseValidator($this->validator->getErrors());
    //         }
    //         if ($is_falied) {
    //             $this->lc_ui_date->ui_mess =  ((isset($errMess)) ? $errMess : 'Utente non trovato! Controlla i dati inseriti!');
    //             $this->lc_ui_date->ui_mess_type = 'alert alert-danger';
    //         }
    //     }
    //     // 
    //     $this->lc_ui_date->entity = $curr_entity;
    //     return view('LcShop\Cms\Views/orders/scheda', $this->lc_ui_date->toArray());
    // }

    //--------------------------------------------------------------------
    public function delete($id)
    {
        $current_model = new CurrentModel();
        if (!$curr_entity = $current_model->find($id)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $current_model->delete($curr_entity->id);
        return redirect()->route($this->route_prefix);
    }
}
