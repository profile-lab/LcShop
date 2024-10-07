<?php

//--------------------------------------------------
if (!function_exists('get_spedizioni_per')) {
    /**
     * Ritorna un array di oggetti con le spedizioni per
     * [
     * {
     *  'nome' => 'Italia',
     *  'key' => 'italia',
     *  'is_italy' => true,
     *  'is_isole' => false,
     *  'is_default' => true,
     * }
     * ]
     */
    function get_spedizioni_per()
    {
        $spedizioni_per = [
            'italia' => ['nome' => 'Italia', 'key' => 'italia', 'is_italy' => true,  'is_isole' => false, 'is_default' => true],
            'isole' => ['nome' => 'Isole', 'key' => 'isole', 'is_italy' => true, 'is_isole' => true],
            'estero' => ['nome' => 'Estero', 'key' => 'estero', 'is_italy' => false, 'is_isole' => false],
        ];

        return $spedizioni_per;
    }
}

//--------------------------------------------------
if (!function_exists('get_regioni')) {
    /**
     * Ritorna tutte le regioni Italiane get_regioni($solo_isole = null) 
     * o tutte le regioni continentali ($solo_isole = false)
     * o solo le isole ($solo_isole = true)
     */
    function get_regioni($solo_isole = null)
    {
        $regioniItaliane = [
            'abruzzo' => ['is_italy' => true, 'min' => 64010, 'max' => 67100, 'is_isole' => false],
            'basilicata' => ['is_italy' => true, 'min' => 75010, 'max' => 85100, 'is_isole' => false],
            'calabria' => ['is_italy' => true, 'min' => 87010, 'max' => 89900, 'is_isole' => false],
            'campania' => ['is_italy' => true, 'min' => 80010, 'max' => 84135, 'is_isole' => false],
            'emilia_romagna' => ['is_italy' => true, 'min' => 29010, 'max' => 48100, 'is_isole' => false],
            'friuli_venezia_giulia' => ['is_italy' => true, 'min' => 33010, 'max' => 34170, 'is_isole' => false],
            'lazio' => ['is_italy' => true, 'min' => 00010, 'max' => 2011, 'is_isole' => false],
            'liguria' => ['is_italy' => true, 'min' => 12071, 'max' => 19137, 'is_isole' => false],
            'lombardia' => ['is_italy' => true, 'min' => 16192, 'max' => 46100, 'is_isole' => false],
            'marche' => ['is_italy' => true, 'min' => 60010, 'max' => 63900, 'is_isole' => false],
            'molise' => ['is_italy' => true, 'min' => 86010, 'max' => 86170, 'is_isole' => false],
            'piemonte' => ['is_italy' => true, 'min' => 10010, 'max' => 28925, 'is_isole' => false],
            'puglia' => ['is_italy' => true, 'min' => 70010, 'max' => 76125, 'is_isole' => false],
            'sardegna' => ['is_italy' => true, 'min' => 07010, 'max' => 9170, 'is_isole' => true],
            'sicilia' => ['is_italy' => true, 'min' => 90010, 'max' => 98168, 'is_isole' => true],
            'toscana' => ['is_italy' => true, 'min' => 50010, 'max' => 59100, 'is_isole' => false],
            'trentino_alto_adige' => ['is_italy' => true, 'min' => 38010, 'max' => 39100, 'is_isole' => false],
            'umbria' => ['is_italy' => true, 'min' => 05010, 'max' => 06135, 'is_isole' => false],
            'valle_d_aosta' => ['is_italy' => true, 'min' => 11010, 'max' => 11100, 'is_isole' => false],
            'veneto' => ['is_italy' => true, 'min' => 30010, 'max' => 45100, 'is_isole' => false],
        ];
        if ($solo_isole == null) {
            return $regioniItaliane;
        }
        $regioni = [];
        if ($solo_isole == true) {
            foreach ($regioniItaliane as $key => $regione) {
                if ($regione['is_isole']) {
                    $regioni[$key] = $regione;
                }
            }
        } else {
            foreach ($regioniItaliane as $key => $regione) {
                if (!$regione['is_isole']) {
                    $regioni[$key] = $regione;
                }
            }
        }
        return $regioni;
    }
}

//--------------------------------------------------
if (!function_exists('get_regione_by_cap')) {
    /**
     * Ritorna un oggetto regione Italiana get_regione_by_cap($cap) se il cap è valido
     * {
     *  'nome' => 'Abruzzo',
     *  'key' => 'abruzzo',
     *  'cap_min' => 64010,
     *  'cap_max' => 67100,
     *  'is_isole' => false,
     *  'is_italy' => true,
     * }
     */
    function get_regione_by_cap($user_cap)
    {
        $caps_regioni = get_regioni();
        foreach ($caps_regioni as $key => $c_caps) {
            if ($user_cap >= $c_caps['min'] && $user_cap <= $c_caps['max']) {
                $regioneObj = new stdClass();
                $regioneObj->nome = ucfirst(str_replace('_', ' ', $key));
                $regioneObj->key = $key;
                $regioneObj->cap_min = $c_caps['min'];
                $regioneObj->cap_max = $c_caps['max'];
                $regioneObj->is_isole = $c_caps['is_isole'];
                $regioneObj->is_italy = $c_caps['is_italy'];
                return $regioneObj;
            }
        }
        return false;
    }
}
//--------------------------------------------------
if (!function_exists('getShopEviProducts')) {


    function getShopEviProducts($_ref_row, $limit = 4)
    {
        if ($_ref_row->component_params && trim($_ref_row->component_params) != '' && is_numeric(trim($_ref_row->component_params))) {
            $count_params = intval(trim($_ref_row->component_params));
            if ($count_params > 0) {
                $limit = $count_params;
            }
        }
        $shop_products_model = new \LcShop\Data\Models\ShopProductsModel();
        $shop_products_model->setForFrontemd();
        $qb_prodotti = $shop_products_model->asObject();
        if (
            $pages_archive = $qb_prodotti->orderBy('is_evi', 'ASC')
            ->where('parent', 0)
            ->orderBy('id', 'RANDOM')
            ->findAll($limit)
        ) {
            foreach ($pages_archive as $key => $single) {
                $single->permalink = route_to(__locale_uri__ . 'web_shop_detail', $single->guid);
            }
            return $pages_archive;
        }
        return FALSE;
    }
}
