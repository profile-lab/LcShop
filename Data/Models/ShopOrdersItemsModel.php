<?php

namespace LcShop\Data\Models;

use Lc5\Data\Models\MasterModel;

class ShopOrdersItemsModel extends MasterModel
{
	protected $table                = 'shop_orders_items';
	protected $primaryKey           = 'id';
	protected $useSoftDeletes 		= true;
	protected $createdField  		= 'created_at';
	protected $updatedField  		= 'updated_at';
	protected $deletedField  		= 'deleted_at';

	protected $returnType           = 'LcShop\Data\Entities\ShopOrdersItem';
	protected $allowedFields        =  [
		'id',
		'id_app',
		'order_id',
		'user_id',
		'row_key',
		'reference_type',
		'id_prodotto',
		'id_modello',
		'nome',
		'modello',
		'full_nome_prodotto',
		'permalink',
		'qnt',
		'qnt_scaricate',
		'prezzo_uni',
		'prezzo',

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
		if (isset($item->prezzo_uni) && $item->prezzo_uni) {
			$item->prezzo_uni_formatted = number_format($item->prezzo_uni, 2, ',', '.');
		}
		if (isset($item->prezzo) && $item->prezzo) {
			$item->prezzo_formatted = number_format($item->prezzo, 2, ',', '.');
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
