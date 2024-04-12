<?php

namespace LcShop\Web\Controllers;

use LcShop\Data\Models\ShopProductsModel;
use LcShop\Data\Models\ShopSettingsModel;
use LcShop\Data\Models\ShopSpeseSpedizionesModel;

class Cart extends \App\Controllers\BaseController
{
    protected $payTotal = 0;
    protected $payTotalFormatted = 0;
    protected $cartTotal = 0;
    protected $cartTotalFormatted = 0;
    protected $referenze = 0;
    protected $referenze_totali  = 0;

    protected $pesoTotaleGrammi = 0;
    protected $pesoTotaleKg = 0;

    protected $ivaTotal = 0;
    protected $ivaTotalFormatted = 0;
    protected $imponibileTotal = 0;
    protected $imponibileTotalFormatted = 0;
    protected $promoPriceTotal = 0;
    protected $promoPriceTotalFormatted = 0;
    protected $discountPercTotal = 0;
    protected $discountPercTotalFormatted = 0;
    protected $spedizioneCorrente = null;
    protected $spedizioneName = 'Spese di spedizione';
    protected $speseSpedizioneImponibile = 0;
    protected $speseSpedizioneImponibileFormatted = 0;
    protected $speseSpedizioneTotal = 0;
    protected $speseSpedizioneTotalFormatted = 0;

    protected $req;
    protected $appuser;


    //--------------------------------------------------------------------
    public function __construct()
    {
        $this->req = \Config\Services::request();
        $this->appuser = \Config\Services::appuser();


        helper('lcshop');

        // parent::__construct();
    }
    //--------------------------------------------------------------------
    public function checkCartAction() //($category_guid = null)
    {
        if ($this->req->getPost()) {
            if ($this->req->getPost('cart_action') == 'ADD') {
                if ($this->addToCart($this->req->getPost('prod_id'), 'p_')) {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    //-------------------------------------------------
    public function addToCart($product_id,  $prod_prefix = 'p_')
    {

        // dd($this->req->getPost());

        $referenze_totali = 0;
        $site_cart_object = new \stdClass();


        if (!$cart = session()->get('site_cart')) {
            $cart = [];
        }
        $cart_index = $prod_prefix . $product_id . '_' . $this->req->getPost('prod_model_id');
        if (isset($cart[$cart_index])) {
            $cart[$cart_index] += ($this->req->getPost('prod_qty')) ? $this->req->getPost('prod_qty') : 1;
        } else {
            $cart[$cart_index] = ($this->req->getPost('prod_qty')) ? $this->req->getPost('prod_qty') : 1;
        }
        foreach ($cart as $cart_item) {
            $referenze_totali += $cart_item;
        }

        session()->set(['site_cart' => $cart]);


        $site_cart_object->status = '201';
        $site_cart_object->mess = 'Prodotto aggiunto al carrello<br /><a class="go_to_cart_in_mess" href="' . site_url(route_to('site_cart')) . '" title="Vai al carrello"><span class="menu-item-label">Vai al carrello</span><i class="fas fa-shopping-cart nav-link-icon"></i></a>';
        $site_cart_object->cart = $cart;
        $site_cart_object->referenze_totali = $referenze_totali;
        $site_cart_object->referenze = count($cart);

        //  dd($site_cart_object);


        return $site_cart_object;
    }

    //-------------------------------------------------
    public function svuotaCarrello()
    {
        session()->set(['shipping_zip' => null]);
        session()->set(['order_data' => null]);
        session()->set(['site_cart' => null]);
    }
    //-------------------------------------------------
    public function removeRow($row_key)
    {
        if (!$cart = session()->get('site_cart')) {
            $cart = [];
        }
        if (isset($cart[$row_key])) {
            unset($cart[$row_key]);
        }
        session()->set(['site_cart' => $cart]);
        return $cart;
    }
    //-------------------------------------------------
    public function incrementRow($row_key)
    {
        if (!$cart = session()->get('site_cart')) {
            $cart = [];
        }
        if (isset($cart[$row_key])) {
            $cart[$row_key] += 1;
        } else {
            $cart[$row_key] = 1;
        }
        session()->set(['site_cart' => $cart]);
        return $cart;
    }
    //-------------------------------------------------
    public function decrementRow($row_key)
    {
        if (!$cart = session()->get('site_cart')) {
            $cart = [];
        }
        if (isset($cart[$row_key])) {
            $cart[$row_key] -= 1;
            if ($cart[$row_key] < 1) {
                unset($cart[$row_key]);
            }
        }
        session()->set(['site_cart' => $cart]);
        return $cart;
    }

    //-------------------------------------------------
    public function getSiteCart()
    {

        $processed_cart = [];
        $this->payTotal = 0;
        $this->payTotalFormatted = 0;
        $this->cartTotal = 0;
        $this->cartTotalFormatted = 0;
        $this->referenze = 0;
        $this->referenze_totali  = 0;

        $this->pesoTotaleGrammi = 0;
        $this->pesoTotaleKg = 0;
        $this->ivaTotal = 0;
        $this->ivaTotalFormatted = 0;
        $this->imponibileTotal = 0;
        $this->imponibileTotalFormatted = 0;
        $this->promoPriceTotal = 0;
        $this->promoPriceTotalFormatted = 0;

        $this->discountPercTotal = 0;
        $this->discountPercTotalFormatted = 0;
        $this->spedizioneCorrente = null;
        $this->spedizioneName = 'Spese di spedizione';
        $this->speseSpedizioneImponibile = 0;
        $this->speseSpedizioneImponibileFormatted = 0;
        $this->speseSpedizioneTotal = 0;
        $this->speseSpedizioneTotalFormatted = 0;




        if ($cart = session()->get('site_cart')) {
            $shop_settings = $this->getShopSettings(__web_app_id__);
            // 
            $shop_products_model = new ShopProductsModel();
            $shop_products_model->setForFrontemd();
            $shop_products_model->shop_settings = $shop_settings;
            if (is_iterable($cart)) {
                foreach ($cart as $key => $qnt) {
                    $reference_type = '';
                    $key_parameters = explode('_', $key);
                    if(isset($key_parameters[0]) && $key_parameters[0] == 'p'){
                        $reference_type = 'product';
                    }
                    if (isset($key_parameters[1])) {
                        if ($prod = $shop_products_model->select(['id', 'nome', 'titolo', 'modello', 'giacenza', 'peso_prodotto', 'guid', 'price', 'promo_price', 'ali'])->asObject()->find($key_parameters[1])) {
                            $permalink = site_url(route_to(__locale_uri__ . 'web_shop_detail', $prod->guid));
                            if (
                                isset($key_parameters[2]) && $key_parameters[2] != $key_parameters[1] &&
                                $modello = $shop_products_model->select(['id', 'nome', 'titolo', 'modello', 'giacenza', 'peso_prodotto', 'guid', 'price', 'promo_price', 'ali'])->asObject()->find($key_parameters[2])
                            ) {
                                if ($modello->price < 0.01) {
                                    $modello->prezzo = $prod->prezzo;
                                }
                                $permalink = site_url(route_to(__locale_uri__ . 'web_shop_detail_model', $prod->guid, $modello->id));
                            } else {
                                $modello = $prod;
                            }
                            // d($prod);
                            // dd($modello);
                            $this->cartTotal += ($modello->prezzo * $qnt);
                            $this->ivaTotal += ($modello->iva * $qnt);
                            $this->pesoTotaleGrammi += ($modello->peso_prodotto * $qnt);
                            $this->imponibileTotal += ($modello->imponibile * $qnt);
                            $this->referenze_totali += $qnt;


                            $prezzo_uni = $modello->prezzo;
                            $totale_row = $prezzo_uni * $qnt;
                            $processed_cart[$key] = (object) [
                                'id_prodotto' => $prod->id,
                                'id_modello' => $modello->id,
                                'row_key' => $key,
                                'permalink' => $permalink,
                                'full_nome_prodotto' => $modello->full_nome_prodotto,
                                'nome' => $prod->nome,
                                'modello' => $modello->modello,
                                'qnt' => $qnt,
                                'reference_type' => $reference_type,
                                'prezzo_uni' => $prezzo_uni,
                                'prezzo_uni_formatted' => number_format($prezzo_uni, 2, ',', '.'),
                                'prezzo' => $totale_row,
                                'prezzo_formatted' => number_format($totale_row, 2, ',', '.'),
                            ];
                        }
                    }
                }
            }
        }

        $currentCap = $this->getCurrentCap();
        $regione = get_regione_by_cap($currentCap);
        // $currentCap = $this->req->getPost('cap') ?: $this->req->getGet('cap');

        $this->pesoTotaleKg = doubleval($this->pesoTotaleGrammi / 1000);
        $this->spedizioneCorrente = $this->getSpedizione($this->pesoTotaleGrammi, $regione);
        if ($this->spedizioneCorrente) {
            $this->speseSpedizioneImponibile = $this->spedizioneCorrente->prezzo_imponibile;
            $this->speseSpedizioneTotal = $this->spedizioneCorrente->prezzo;
            $this->spedizioneName = ($this->spedizioneCorrente->titolo) ? $this->spedizioneCorrente->titolo : $this->spedizioneCorrente->nome;
        } else {
            $this->speseSpedizioneImponibile = 0;
            $this->speseSpedizioneTotal = 0;
        }

        $this->payTotal = $this->cartTotal + $this->speseSpedizioneTotal;
        $this->payTotalFormatted = number_format($this->payTotal, 2, ',', '.');

        $this->cartTotalFormatted = number_format($this->cartTotal, 2, ',', '.');
        $this->ivaTotalFormatted = number_format($this->ivaTotal, 2, ',', '.');
        $this->imponibileTotalFormatted = number_format($this->imponibileTotal, 2, ',', '.');
        $this->promoPriceTotalFormatted = number_format($this->promoPriceTotal, 2, ',', '.');
        $this->discountPercTotalFormatted = number_format($this->discountPercTotal, 2, ',', '.');
        $this->speseSpedizioneImponibileFormatted = number_format($this->speseSpedizioneImponibile, 2, ',', '.');
        $this->speseSpedizioneTotalFormatted = number_format($this->speseSpedizioneTotal, 2, ',', '.');

        $this->referenze = count($processed_cart);


        $returnObject = (object) [
            'products' => $processed_cart,
            'pay_total' =>  $this->payTotal,
            'pay_total_formatted' =>  $this->payTotalFormatted,
            'total' => $this->cartTotal,
            'total_formatted' => $this->cartTotalFormatted,
            'peso_totale_grammi' => $this->pesoTotaleGrammi,
            'peso_totale_kg' => $this->pesoTotaleKg,
            'iva_total' => $this->ivaTotal,
            'iva_total_formatted' => $this->ivaTotalFormatted,
            'imponibile_total' => $this->imponibileTotal,
            'imponibile_total_formatted' => $this->imponibileTotalFormatted,
            'promo_total' => $this->promoPriceTotal,
            'promo_total_formatted' => $this->promoPriceTotalFormatted,

            'regione' => $regione,

            'spedizione_corrente' => $this->spedizioneCorrente,
            'spedizione_name' => $this->spedizioneName,
            'spese_spedizione_imponibile' => $this->speseSpedizioneImponibile,
            'spese_spedizione_imponibile_formatted' => ($this->speseSpedizioneImponibile > 0) ? $this->speseSpedizioneImponibileFormatted : 'Gratis',
            'spese_spedizione' => $this->speseSpedizioneTotal,
            'spese_spedizione_formatted' => ($this->speseSpedizioneTotal > 0) ? $this->speseSpedizioneTotalFormatted : 'Gratis',


            'referenze' => $this->referenze,
            'referenze_totali' => $this->referenze_totali,
        ];
        return $returnObject;
    }

    //-------------------------------------------------
    public function getCurrentCap()
    {
        if (!$shipping_zip = session()->get('shipping_zip')) {
            if ($all_user_data = $this->appuser->getAllUserData()) {
                if (isset($all_user_data->cap) && trim($all_user_data->cap)) {
                    session()->set(['shipping_zip' => $all_user_data->cap]);
                    $shipping_zip = $all_user_data->cap;
                }
            }
        } else {
            $shipping_zip = session()->get('shipping_zip');
        }
        if ($this->req->getPost('ship_zip')) {
            session()->set(['shipping_zip' => $this->req->getPost('ship_zip')]);
            $shipping_zip = $this->req->getPost('ship_zip');
        }
        if ($this->req->getGet('ship_zip')) {
            session()->set(['shipping_zip' => $this->req->getGet('ship_zip')]);
            $shipping_zip = $this->req->getGet('ship_zip');
        }
        return $shipping_zip;
        // return null;
    }

    //-------------------------------------------------
    public function getSpedizione($pesoTotaleGrammi = null, $regione  = null)
    {
        $spese_spedizione_model = new ShopSpeseSpedizionesModel();
        $pesoTotaleKg = doubleval($pesoTotaleGrammi / 1000);
        $spedizioneQb = $spese_spedizione_model->where('status', 1)->where('public', 1)->where('peso_max >=', $pesoTotaleKg);
        if ($regione) {
            // $spedizioneQb->where('consegna', 'estero');
            if ($regione->is_italy == false) {
                $spedizioneQb->where('consegna', 'estero');
            } else if ($regione->is_isole) {
                $spedizioneQb->where('consegna', 'isole');
            } else if ($regione->is_italy) {
                $spedizioneQb->where('consegna', 'italia');
            } else {
                $spedizioneQb->where('is_default', 1);
            }
        } else {
            $spedizioneQb->where('is_default', 1);
        }

        $spedizioneCorrente =  $spedizioneQb->orderBy('peso_max', 'ASC')->first();
        if ($spedizioneCorrente) {
            return $spedizioneCorrente;
        }
        return false;
    }





    // //-------------------------------------------------
    // public function addQntFromCart($product_id, $model_id = 0, $quantity = 1)
    // {
    //     $referenze_totali = 0;
    //     $site_cart_object = new \stdClass();
    //     $prod_prefix = 'p_';

    //     if (!$cart = session()->get('site_cart')) {
    //         $cart = [];
    //     }
    //     $new_cart = [];

    //     foreach ($cart as $key => $cart_item) {
    //         if ($key == $prod_prefix . $product_id . '_' . $model_id) {
    //             $cart_item += $quantity;
    //         }
    //         $referenze_totali += $cart_item;
    //         $new_cart[$key] = $cart_item;
    //     }
    //     $cart = $new_cart;

    //     session()->set(['site_cart' => $cart]);


    //     $site_cart_object->status = '201';
    //     $site_cart_object->mess = 'Prodotto aggiunto al carrello';
    //     $site_cart_object->cart = $cart;
    //     $site_cart_object->referenze_totali = $referenze_totali;
    //     $site_cart_object->referenze = count($cart);

    //     if ($returnTo = $this->req->getGet('returnTo')) {
    //         return $this->response->redirect($returnTo);
    //     }


    //     return $this->setResponseFormat('json')->respond($site_cart_object);
    // }









}
