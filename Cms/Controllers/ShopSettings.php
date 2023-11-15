<?php

namespace LcShop\Cms\Controllers;

use Lc5\Cms\Controllers\MasterLc;
use LcShop\Data\Models\ShopSettingsModel;


class ShopSettings extends MasterLc
{
    //--------------------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
		// 
		$this->module_name = 'Config';
        $this->route_prefix = 'lc_shop_settings';
		// 
		$this->lc_ui_date->__set('request', $this->req);
		$this->lc_ui_date->__set('route_prefix', $this->route_prefix);
		$this->lc_ui_date->__set('module_name', $this->module_name);
        $this->lc_ui_date->__set('currernt_module', 'lcshop');
		$this->lc_ui_date->__set('currernt_module_action', 'shopsettings');
		$this->lc_ui_date->__set('currernt_module_tab', 'shopsettings');
		$this->lc_ui_date->__set('shop_tools_tabs', LcShopConfigs::getShopToolsTabs() );
        // 
		$this->lc_ui_date->__set('discount_type_list', [(object) ['val' => 'PRICE', 'nome' => 'Imponibile scontato'], (object) ['val' => 'PERCENTAGE', 'nome' => 'Percentuale di sconto'] ] );
    }

    //--------------------------------------------------------------------
    public function edit()
    {
        // // 
        $shop_settings_model = new ShopSettingsModel();
        // 
        // $curr_entity = $this->getShopSettings($this->getCurrApp());
        if (!$curr_entity = $shop_settings_model->where('id_app', $this->getCurrApp())->first()) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}
        // 
        if ($this->req->getMethod() == 'post') {
            $validate_rules = [
                'save' => ['label' => 'Save', 'rules' => 'required'],
                // 'nome' => ['label' => 'Nome', 'rules' => 'required'],
            ];
            $is_falied = TRUE;
            $curr_entity->fill($this->req->getPost());
            // 
            if ($this->validate($validate_rules)) {
                $shop_settings_model->save($curr_entity);
                // 
                return redirect()->route($this->route_prefix, [$curr_entity->id]);
            } else {
                $this->lc_ui_date->ui_mess =  $this->lc_parseValidator($this->validator->getErrors());
                $this->lc_ui_date->ui_mess_type = 'alert alert-danger';
            }
        }
        // 
        $this->lc_ui_date->entity = $curr_entity;
        return view('LcShop\Cms\Views/settings/scheda', $this->lc_ui_date->toArray());
    }




}
