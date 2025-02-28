<?php

namespace LcShop\Cms\Controllers;

use Lc5\Cms\Controllers\MasterLc;
// 
use LcShop\Data\Models\ShopProductsCategoriesModel;
use LcShop\Data\Models\ShopProductsModel;
use LcShop\Data\Entities\ShopProductItem;

use LcShop\Data\Models\ShopProductsTagsModel;
use LcShop\Data\Models\ShopProductsVariationsModel;
use LcShop\Data\Models\ShopProductsSizesModel;
use LcShop\Data\Models\ShopAliquoteModel;


class ShopProducts extends MasterLc
{
	protected $current_shop_setting;
	private $shop_products_model;
	//--------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct();
		// 
		$this->shop_products_model = new ShopProductsModel();

		$this->module_name = 'Prodotti';
		$this->route_prefix = 'lc_shop_prod';
		// 
		$this->lc_ui_date->__set('request', $this->req);
		$this->lc_ui_date->__set('route_prefix', $this->route_prefix);
		$this->lc_ui_date->__set('module_name', $this->module_name);
		// 
		$this->lc_ui_date->__set('currernt_module', 'lcshop');
		// 
		$this->current_shop_setting  = $this->getShopSettings($this->getCurrApp());
		$this->lc_ui_date->__set('current_shop_setting', $this->current_shop_setting);
	}

	//--------------------------------------------------------------------
	public function index()
	{
		$updateOrdineValue = $this->shop_products_model->where('ordine is null')->set(['ordine' => 1500])->update();
		// 
		$list = $this->shop_products_model->where('parent', 0)->orderBy('id', 'DESC')->findAll();
		$this->lc_ui_date->list = $list;
		// 
		return view('LcShop\Cms\Views/products/index', $this->lc_ui_date->toArray());
	}

	//--------------------------------------------------------------------
	public function newpost($parent_id = 0)
	{
		// 
		$curr_entity = new ShopProductItem();
		// 
		$curr_entity->is_modello = FALSE;
		$curr_entity->parent_entity = NULL;
		// 
		if ($parent_id > 0) {
			$curr_entity->parent = $parent_id;
			if (!$curr_entity->parent_entity = $this->shop_products_model->find($parent_id)) {
				throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
			}
			// 
			$curr_entity->is_modello = TRUE;
			// 
		}
		// 
		$curr_entity->aliquote_list = $this->getListAliquote();
		$curr_entity->categories_list = $this->getListCategories();
		$curr_entity->parents_list = $this->getListParents();
		// 
		$shop_products_tags_model = new ShopProductsTagsModel();
		$curr_entity->tags_list = $this->getListLikeTags($shop_products_tags_model);
		$shop_products_sizes_model = new ShopProductsSizesModel();
		$curr_entity->sizes_list = $this->getListLikeTags($shop_products_sizes_model);
		$shop_products_variations_model = new ShopProductsVariationsModel();
		$curr_entity->variations_list = $this->getListLikeTags($shop_products_variations_model);
		$curr_entity->um_list = $this->getListUM();
		$curr_entity->public = 1;
		$curr_entity->status = 1;
		// 
		if ($this->req->getPost()) {
			// 
			if ($curr_entity->is_modello == TRUE) {
				$validate_rules = [
					'modello' => ['label' => 'Modello', 'rules' => 'required'],
				];
			} else {
				$validate_rules = [
					'titolo' => ['label' => 'Prodotto', 'rules' => 'required'],
				];
			}
			// 
			$is_falied = TRUE;
			$curr_entity->fill($this->req->getPost());
			if ($curr_entity->is_modello != TRUE) {
				$curr_entity->nome = $curr_entity->titolo;
				// $curr_entity->status = 0;
				// $curr_entity->public = 0;
			}

			$curr_entity->tags = ($this->req->getPost('tags')) ? json_encode($this->req->getPost('tags')) : NULL;

			// 
			// compila dati da prodotto padre
			if ($curr_entity->parent_entity) {
				$this->fillEntityByParent($curr_entity);
			}
			// 


			if ($this->validate($validate_rules)) {

				// dd($curr_entity);
				$this->shop_products_model->save($curr_entity);
				// 
				$new_id = $this->shop_products_model->getInsertID();
				// 
				// $this->editEntityRows($new_id, 'pages');
				// 
				return redirect()->route($this->route_prefix . '_edit', [$new_id]);
			} else {
				$errMess = $this->lc_parseValidator($this->validator->getErrors());
			}
			if ($is_falied) {
				$this->lc_ui_date->ui_mess =  ((isset($errMess)) ? $errMess : 'Utente non trovato! Controlla i dati inseriti!');
				$this->lc_ui_date->ui_mess_type = 'alert alert-danger';
			}
		}
		// 
		$this->lc_ui_date->entity = $curr_entity;
		return view('LcShop\Cms\Views/products/scheda', $this->lc_ui_date->toArray());
	}

	//--------------------------------------------------------------------
	public function edit($id)
	{
		if (!$curr_entity = $this->shop_products_model->find($id)) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}
		// 
		$curr_entity->aliquote_list = $this->getListAliquote();
		$curr_entity->categories_list = $this->getListCategories();
		$curr_entity->parents_list = $this->getListParents();
		// 
		$shop_products_tags_model = new ShopProductsTagsModel();
		$curr_entity->tags_list = $this->getListLikeTags($shop_products_tags_model);
		$shop_products_sizes_model = new ShopProductsSizesModel();
		$curr_entity->sizes_list = $this->getListLikeTags($shop_products_sizes_model);
		$shop_products_variations_model = new ShopProductsVariationsModel();
		$curr_entity->variations_list = $this->getListLikeTags($shop_products_variations_model);
		$curr_entity->um_list = $this->getListUM();
		// 
		// 
		$curr_entity->is_modello = FALSE;
		$curr_entity->parent_entity = NULL;
		// 
		if ($curr_entity->parent > 0) {
			if (!$curr_entity->parent_entity = $this->shop_products_model->find($curr_entity->parent)) {
				throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
			}
			// 
			$curr_entity->is_modello = TRUE;
			// 
		} else {
			$curr_entity->childs_entities = $this->shop_products_model->where('parent', $curr_entity->id)->findAll();
		}
		// 
		if ($this->req->getPost()) {
			if ($curr_entity->is_modello == TRUE) {
				$validate_rules = [
					'modello' => ['label' => 'Modello', 'rules' => 'required'],
				];
			} else {
				$validate_rules = [
					'nome' => ['label' => 'Nome', 'rules' => 'required'],
				];
			}
			$is_falied = TRUE;
			$curr_entity->fill($this->req->getPost());
			if ($curr_entity->created_at == $curr_entity->updated_at) {
				$curr_entity->public = 1;
			}
			// dd($curr_entity);
			if ($this->validate($validate_rules)) {
				// $curr_entity->status = 1;
				if ($curr_entity->created_at == $curr_entity->updated_at) {
					$curr_entity->status = 1;
				}
				// $curr_entity->public = $this->req->getPost('public') ? 1 : 0 ;
				if ($this->req->getPost('public')) {
					$curr_entity->status = 1;
					$curr_entity->public = 1;
				} else {
					$curr_entity->public = 0;
				}
				$curr_entity->tags = ($this->req->getPost('tags')) ? json_encode($this->req->getPost('tags')) : NULL;

				// 
				// compila dati da prodotto padre
				if ($curr_entity->parent_entity) {
					$this->fillEntityByParent($curr_entity);
				} else if ($curr_entity->parent == 0) {
					if (is_array($curr_entity->childs_entities)) {
						$this->updateChildsEntity($curr_entity);
					}
				}

				// 

				$this->shop_products_model->save($curr_entity);



				// 
				return redirect()->route($this->route_prefix . '_edit', [$curr_entity->id]);
			} else {
				$errMess = $this->lc_parseValidator($this->validator->getErrors());
			}
			if ($is_falied) {
				$this->lc_ui_date->ui_mess =  ((isset($errMess)) ? $errMess : 'Utente non trovato! Controlla i dati inseriti!');
				$this->lc_ui_date->ui_mess_type = 'alert alert-danger';
			}
		}

		// 
		$this->lc_ui_date->entity = $curr_entity;
		return view('LcShop\Cms\Views/products/scheda', $this->lc_ui_date->toArray());
	}

	//--------------------------------------------------------------------
	public function delete($id)
	{

		if (!$curr_entity = $this->shop_products_model->find($id)) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}



		$this->shop_products_model->delete($curr_entity->id);
		$this->lc_setErrorMess('Contenuto eliminato con successo', 'alert-warning');

		return redirect()->route($this->route_prefix);
	}

	//--------------------------------------------------------------------
	private function getListParents($exclude_id = null)
	{
		// 
		$this->shop_products_model = new ShopProductsModel();
		// 
		$list_qb = $this->shop_products_model->select('id as val, nome')->asObject();
		if ($exclude_id) {
			$list_qb->where('id !=', $exclude_id);
		}
		return $list_qb->findAll();
	}
	//--------------------------------------------------------------------
	private function getListAliquote()
	{
		// 
		$shop_aliquote_model = new ShopAliquoteModel();
		// 
		$list_qb = $shop_aliquote_model->select('id as val, nome')->asObject();
		return $list_qb->findAll();
	}
	//--------------------------------------------------------------------
	private function getListUM()
	{
		// 
		$um_list = [
			(object)['val' => 'PZ', 'nome' => '€/Pezzo'],
			(object)['val' => 'KG', 'nome' => '€/Chilo'],
			(object)['val' => 'MT', 'nome' => '€/Metro'],
			(object)['val' => 'LT', 'nome' => '€/Litro'],
		];
		// 
		return $um_list;
	}
	//--------------------------------------------------------------------
	private function getListCategories()
	{
		// 
		$shop_product_cat_model = new ShopProductsCategoriesModel();
		// 
		$list_qb = $shop_product_cat_model->select('id as val, nome')->asObject();
		return $list_qb->findAll();
	}
	//--------------------------------------------------------------------
	private function getListLikeTags($tags_type_model)
	{
		// 
		$list_qb = $tags_type_model->select('val, nome')->asObject();
		// $list_qb = $tags_type_model->select('id as val, nome')->asObject();
		return $list_qb->findAll();
	}
	//--------------------------------------------------------------------
	//--------------------------------------------------------------------
	private function fillEntityByParent(&$curr_entity)
	{
		$curr_entity->status = $curr_entity->parent_entity->status;
		$curr_entity->ordine = $curr_entity->parent_entity->ordine;
		// $curr_entity->public = $curr_entity->parent_entity->public;
		$curr_entity->product_type = $curr_entity->parent_entity->product_type;
		$curr_entity->category = $curr_entity->parent_entity->category;
		$curr_entity->multi_categories = $curr_entity->parent_entity->multi_categories;
		$curr_entity->nome = $curr_entity->parent_entity->nome;
		$curr_entity->guid = $curr_entity->parent_entity->guid . '-' . url_title($curr_entity->modello, '-', TRUE);
		$curr_entity->titolo = $curr_entity->parent_entity->titolo;
		$curr_entity->sottotitolo = $curr_entity->parent_entity->sottotitolo;
		$curr_entity->um = $curr_entity->parent_entity->um;
		$curr_entity->ali = $curr_entity->parent_entity->ali;
		$curr_entity->fornitore = $curr_entity->parent_entity->fornitore;
		$curr_entity->gruppo_merceologico = $curr_entity->parent_entity->gruppo_merceologico;
		if (isset($curr_entity->id) && $curr_entity->id > 0) {
			// solo se già esite
		} else {
			if (trim($curr_entity->scheda_tecnica) != '') {
				$curr_entity->scheda_tecnica = $curr_entity->parent_entity->scheda_tecnica;
			}
		}
	}
	//--------------------------------------------------------------------
	private function updateChildsEntity($curr_entity)
	{
		if (is_iterable($curr_entity->childs_entities)) {
			foreach ($curr_entity->childs_entities as $current_child) {
				$new_data = array();
				$new_data['guid'] = $curr_entity->guid . '-' . url_title($current_child->modello, '-', TRUE);
				$new_data['status'] = $curr_entity->status;
				$new_data['ordine'] = $curr_entity->ordine;
				// $new_data['public'] = $curr_entity->public;
				$new_data['product_type'] = $curr_entity->product_type;
				$new_data['category'] = $curr_entity->category;
				$new_data['multi_categories'] = $curr_entity->multi_categories;
				$new_data['nome'] = $curr_entity->nome;
				$new_data['titolo'] = $curr_entity->titolo;
				$new_data['sottotitolo'] = $curr_entity->sottotitolo;
				$new_data['ali'] = $curr_entity->ali;
				$new_data['um'] = $curr_entity->um;
				// $new_data['scheda_tecnica'] = $curr_entity->scheda_tecnica;
				$new_data['fornitore'] = $curr_entity->fornitore;
				$new_data['gruppo_merceologico'] = $curr_entity->gruppo_merceologico;
				// 
				$this->shop_products_model->update($current_child->id, $new_data);
			}
		}
	}
}
