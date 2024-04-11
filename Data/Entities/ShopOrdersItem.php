<?php

namespace LcShop\Data\Entities;

use CodeIgniter\Entity\Entity;

class ShopOrdersItem extends Entity
{
        protected $attributes = [
                'id' => null, 
		'id_app' => null, 
		'order_id' => null, 
		'user_id' => null, 
		'row_key' => null, 
		'reference_type' => null, 
		'id_prodotto' => null, 
		'id_modello' => null, 
		'nome' => null, 
		'modello' => null, 
		'full_nome_prodotto' => null, 
		'permalink' => null, 
		'qnt' => null, 
		'qnt_scaricate' => null, 
		'prezzo_uni' => null, 
		'prezzo' => null, 
        ];
}
