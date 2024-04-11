<?php

namespace LcShop\Data\Models;

use Lc5\Data\Models\MasterModel;

class ShopOrdersModel extends MasterModel
{
	protected $table                = 'shop_orders';
	protected $primaryKey           = 'id';
	protected $useSoftDeletes 		= true;
    protected $createdField  		= 'created_at';
    protected $updatedField  		= 'updated_at';
    protected $deletedField  		= 'deleted_at';
	
	protected $returnType           = 'LcShop\Data\Entities\ShopOrder';
	protected $allowedFields        =  [
		'id', 
		'id_app', 
		'user_id', 
		'order_status', 
		'last_status_change', 
		'ship_name', 
		'ship_surname', 
		'ship_country', 
		'ship_district', 
		'ship_city', 
		'ship_zip', 
		'ship_address', 
		'ship_address_number', 
		'ship_phone', 
		'ship_email', 
		'ship_infos', 
		'pay_name', 
		'pay_surname', 
		'pay_country', 
		'pay_district', 
		'pay_city', 
		'pay_zip', 
		'pay_address', 
		'pay_address_number', 
		'pay_phone', 
		'pay_email', 
		'pay_infos', 
		'pay_fiscal', 
		'pay_vat', 
		'imponibile_total', 
		'iva_total', 
		'pay_total', 
		'promo_total', 
		'spese_spedizione', 
		'spese_spedizione_imponibile', 
		'total', 
		'peso_totale_grammi', 
		'peso_totale_kg', 
		'referenze', 
		'referenze_totali', 
		'spedizione_name', 
		'spedizione_id', 
		'spedizione_type', 
		'consegna', 
		'consegna_note', 
		'consegna_corriere', 
		'consegna_date', 
		'consegna_track_code', 
		'note', 
		'note_admin', 
		'qnt_scaricate', 
		'payment_type', 
		'payment_status', 
		'payment_code', 
		'payed_at', 
		'payment_action', 
		'stripe_pi', 
		'paypal_string', 
		'auth_1', 
		'auth_2', 
		'auth_3', 
		'auth_4', 
		'auth_5', 

	];

	protected $beforeInsert         = ['beforeInsert'];
	protected $afterInsert          = [];
	protected $beforeUpdate         = ['beforeUpdate'];
	protected $afterUpdate          = [];
	protected $beforeFind           = ['beforeFind'];
	protected $afterFind            = ['afterFind'];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];

	protected function beforeFind(array $data)
	{
		$this->checkAppAndLang();
	}
	protected function afterFind(array $data)
	{
		// $data = $this->beforeSave($data);
		if($data['singleton'] == true){
			$data['data'] = $this->extendData($data['data']);
		}else{
			foreach($data['data'] as $item){
				$item = $this->extendData($item);
			}
		}
		return $data;
		
	}
	
	private function extendData($item)
	{

		return $item;
	}

	protected function beforeUpdate(array $data)
	{
		$data = $this->beforeSave($data);
		return $data;
	}
	protected function beforeInsert(array $data)
	{
		$data = $this->setDataAppAndLang($data);
		$data = $this->beforeSave($data);
		return $data;
	}

	private function beforeSave(array $data)
    {
        if (!isset($data['data']['nome'])) {
			return $data;
        }
		// if (isset($data['data']['val']) && trim($data['data']['val']) ) {
		// 	$data['data']['val'] = url_title(trim($data['data']['val']), '-', TRUE);
		// }else{
		// 	$data['data']['val'] = url_title($data['data']['nome'], '-', TRUE);
		// }
        return $data;
    }
}
