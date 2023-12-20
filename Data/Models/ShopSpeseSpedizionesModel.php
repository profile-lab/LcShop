<?php
namespace LcShop\Data\Models;
use Lc5\Data\Models\MasterModel;

class ShopSpeseSpedizionesModel extends MasterModel
{
	protected $table				= 'shop_spese_spedizione';
	protected $primaryKey			= 'id';
	protected $useSoftDeletes		= true;
	protected $createdField			= 'created_at';
	protected $updatedField			= 'updated_at';
	protected $deletedField			= 'deleted_at';

	protected $returnType           = 'LcShop\Data\Entities\ShopSpeseSpedizione';
	protected $allowedFields = [
		'id', 
		'status', 
		'id_app', 
		'lang', 
		'public', 
		'is_default', 
		'peso_max', 
		'prezzo_imponibile', 
		'prezzo_aliquota', 
		'post_type', 
		'nazione', 
		'is_free', 
		'default_nazione', 
		'nome', 
		'consegna', 
		'guid', 
		'titolo', 
		'testo_breve', 
		'testo', 
		'main_img_id', 
		'extra_field', 

	];

	protected $beforeInsert         = ['beforeInsert'];
	protected $afterInsert          = [];
	protected $beforeUpdate         = ['beforeUpdate'];
	protected $afterUpdate          = [];
	protected $beforeFind           = ['beforeFind'];
	protected $afterFind            = ['afterFind'];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];

	//------------------------------------------------------------
	protected function beforeFind(array $data)
	{
		$this->checkAppAndLang();
		//
		// if($this->is_for_frontend == true){
		// 	$this->where('status !=', 0);
		// 	$this->where('public', 1);
		// }
		// 
	}

	//------------------------------------------------------------
	protected function afterFind(array $data)
	{
		// $data = $this->beforeSave($data);
		if ($data['singleton'] == true) {
			$data['data'] = $this->extendData($data['data'], true);
		} else {
			foreach ($data['data'] as $item) {
				$item = $this->extendData($item);
			}
		}
		return $data;
	}

	//------------------------------------------------------------
	private function extendData($item, $is_singleton = false)
	{
		if ($item) {
		}
		return $item;
	}

	//------------------------------------------------------------
	protected function beforeUpdate(array $data)
	{
		$data = $this->beforeSave($data);
		return $data;
	}

	//------------------------------------------------------------
	protected function beforeInsert(array $data)
	{
		$data = $this->setDataAppAndLang($data);
		$data = $this->beforeSave($data);
		return $data;
	}

	//------------------------------------------------------------
	private function beforeSave(array $data)
	{
		$curr_item_lang = null;
		if (in_array('lang', $this->allowedFields)) {
			if ($curr_lc_lang = session()->get('curr_lc_lang')) {
				$curr_item_lang = $curr_lc_lang;
			}
		}
		if (isset($data['data']['guid'])) {
			$data['data']['guid'] = url_title($data['data']['guid'], '-', TRUE);
		} else if (isset($data['data']['nome'])) {

			$data['data']['guid'] = url_title($data['data']['nome'], '-', TRUE);
		}


		return $data;
	}

}