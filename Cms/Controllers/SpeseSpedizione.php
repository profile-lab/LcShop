<?php

namespace LcShop\Cms\Controllers;

use Lc5\Cms\Controllers\MasterLc;
use LcShop\Data\Models\ShopSpeseSpedizionesModel as CurrentModel;
use LcShop\Data\Entities\ShopSpeseSpedizione  as CurrentEntity;



class SpeseSpedizione extends MasterLc
{
    // private $post_attributes;
    protected $current_shop_setting;


    public function __construct()
    {
        helper('lcshop');

        parent::__construct();
        // 
        $this->module_name = 'Spese Spedizione';
        $this->route_prefix = 'lc_shop_spese_spedizione';
        // 
        $this->lc_ui_date->__set('request', $this->req);
        $this->lc_ui_date->__set('route_prefix', $this->route_prefix);
        $this->lc_ui_date->__set('module_name', $this->module_name);
        $this->lc_ui_date->__set('currernt_module', 'lcshop');
        $this->lc_ui_date->__set('currernt_module_action', 'shopsettings');
        $this->lc_ui_date->__set('currernt_module_tab', 'speesespedizione');
        $this->lc_ui_date->__set('shop_tools_tabs', LcShopConfigs::getShopToolsTabs());

        $spedizioni_per_list_data = get_spedizioni_per();
        $spedizioni_per_list = [];
        foreach ($spedizioni_per_list_data as $key => $value) {
            $spedizioni_per_list[$key] = (object) ['val' => $key, 'nome' => $value['nome']];
        }
        $this->lc_ui_date->__set('spedizioni_per_list', $spedizioni_per_list);
        // 
        $this->current_shop_setting  = $this->getShopSettings($this->getCurrApp());
        $this->lc_ui_date->__set('current_shop_setting', $this->current_shop_setting);
    }





    //--------------------------------------------------------------------
    public function index()
    {
        // 
        $current_model = new CurrentModel();
        // 
        $list = $current_model->findAll();
        $this->lc_ui_date->list = $list;
        // 
        return view('LcShop\Cms\Views/spese-spedizione/index', $this->lc_ui_date->toArray());
    }





    //--------------------------------------------------------------------
    public function newpost()
    {
        $this->lc_ui_date->__set('back_to_list_btn_url', route_to($this->route_prefix));
        // 
        $current_model = new CurrentModel();
        $curr_entity = new CurrentEntity();
        // 
        if ($this->req->getMethod() == 'post') {
            $validate_rules = [
                'nome' => ['label' => 'Nome', 'rules' => 'required'],
                // 'val' => ['label' => 'Valore', 'rules' => 'required'],
            ];
            $curr_entity->fill($this->req->getPost());
            if ($this->validate($validate_rules)) {
                $current_model->save($curr_entity);
                // 
                $new_id = $current_model->getInsertID();
                return redirect()->route($this->route_prefix . '_edit', [$new_id]);
            } else {
                $this->lc_ui_date->ui_mess =  $this->lc_parseValidator($this->validator->getErrors());
                $this->lc_ui_date->ui_mess_type = 'alert alert-danger';
            }
        }
        // 
        $this->lc_ui_date->entity = $curr_entity;
        return view('LcShop\Cms\Views/spese-spedizione/scheda', $this->lc_ui_date->toArray());
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
        if ($this->req->getMethod() == 'post') {
            $validate_rules = [
                'nome' => ['label' => 'Nome', 'rules' => 'required'],
                // 'val' => ['label' => 'Valore', 'rules' => 'required'],
            ];
            $curr_entity->fill($this->req->getPost());
            if ($this->validate($validate_rules)) {
                $current_model->save($curr_entity);
                // 
                return redirect()->route($this->route_prefix . '_edit', [$curr_entity->id]);
            } else {
                $this->lc_ui_date->ui_mess =  $this->lc_parseValidator($this->validator->getErrors());
                $this->lc_ui_date->ui_mess_type = 'alert alert-danger';
            }
        }
        // 
        $this->lc_ui_date->entity = $curr_entity;
        return view('LcShop\Cms\Views/spese-spedizione/scheda', $this->lc_ui_date->toArray());
    }

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
