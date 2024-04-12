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
		if ($data['singleton'] == true) {
			$data['data'] = $this->extendData($data['data']);
		} else {
			foreach ($data['data'] as $item) {
				$item = $this->extendData($item);
			}
		}
		return $data;
	}

	private function extendData($item)
	{
		if (isset($item->imponibile_total) && $item->imponibile_total) {
			$item->imponibile_total_formatted = number_format($item->imponibile_total, 2, ',', '.');
		}
		if (isset($item->iva_total) && $item->iva_total) {
			$item->iva_total_formatted = number_format($item->iva_total, 2, ',', '.');
		}
		if (isset($item->pay_total) && $item->pay_total) {
			$item->pay_total_formatted = number_format($item->pay_total, 2, ',', '.');
		}
		if (isset($item->promo_total) && $item->promo_total) {
			$item->promo_total_formatted = number_format($item->promo_total, 2, ',', '.');
		}
		if (isset($item->spese_spedizione) && $item->spese_spedizione) {
			$item->spese_spedizione_formatted = number_format($item->spese_spedizione, 2, ',', '.');
		}
		if (isset($item->spese_spedizione_imponibile) && $item->spese_spedizione_imponibile) {
			$item->spese_spedizione_imponibile_formatted = number_format($item->spese_spedizione_imponibile, 2, ',', '.');
		}
		if (isset($item->total) && $item->total) {
			$item->total_formatted = number_format($item->total, 2, ',', '.');
		}
		if (isset($item->peso_totale_grammi) && $item->peso_totale_grammi) {
			$item->peso_totale_grammi_formatted = number_format($item->peso_totale_grammi, 2, ',', '.');
		}
		if (isset($item->peso_totale_kg) && $item->peso_totale_kg) {
			$item->peso_totale_kg_formatted = number_format($item->peso_totale_kg, 2, ',', '.');
		}

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
