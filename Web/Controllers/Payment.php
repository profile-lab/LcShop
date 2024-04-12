<?php

namespace LcShop\Web\Controllers;

use Lc5\Data\Models\PagesModel;
use LcShop\Data\Models\ShopProductsModel;
use LcShop\Data\Models\ShopSettingsModel;

// 
use LcShop\Data\Models\ShopProductsCategoriesModel;
use LcShop\Data\Models\ShopProductsTagsModel;
use LcShop\Data\Models\ShopProductsVariationsModel;
use LcShop\Data\Models\ShopProductsSizesModel;
// 
use LcShop\Data\Models\ShopOrdersModel;
use LcShop\Data\Entities\ShopOrder;
use LcShop\Data\Models\ShopOrdersItemsModel;
use LcShop\Data\Entities\ShopOrdersItem;

// use LcShop\Data\Models\ShopOrdersItemsModel;

use Config\Services;

use stdClass;

class Payment extends \Lc5\Web\Controllers\MasterWeb
{
  private $shop_products_model;
  private $shop_orders_model;
  private $shop_orders_items_model;
  private $shop_settings;
  private $appuser;


  //--------------------------------------------------------------------
  public function __construct()
  {
    parent::__construct();
    // 
    $this->shop_settings = $this->getShopSettings(__web_app_id__);
    // 
    $this->shop_products_model = new ShopProductsModel();
    $this->shop_products_model->setForFrontemd();
    $this->shop_products_model->shop_settings = $this->shop_settings;
    $this->shop_orders_model = new ShopOrdersModel();
    $this->shop_orders_model->setForFrontemd();
    $this->shop_orders_items_model = new ShopOrdersItemsModel();
    $this->shop_orders_items_model->setForFrontemd();
    // 
    $this->appuser = Services::appuser();
    // 
    $this->web_ui_date->__set('request', $this->req);
    // 
  }
  //--------------------------------------------------------------------
  public function payOrderNow($order_id)
  {
    

    // $order_data = $this->getOrderData();
    // $all_user_data = $this->appuser->getAllUserData();
    $riepilogo_order_data =  $this->shop_orders_model->where('id', $order_id)->where('user_id', $this->appuser->getUserId())->first();
    if(!$riepilogo_order_data){
      throw new \CodeIgniter\Exceptions\PageNotFoundException('Ordine non trovato');

    }
    $orders_items = $this->shop_orders_items_model->where('order_id', $riepilogo_order_data->id)->findAll();

    //
    $pages_entity_rows = null;
    $products_archive_qb = $this->shop_products_model->asObject();
    $products_archive_qb->where('parent', 0);
    // $products_archive_qb->where('(parent IS NULL OR parent <  1 )');

    $pages_model = new PagesModel();
    $pages_model->setForFrontemd();
    if ($curr_entity = $pages_model->asObject()->orderBy('id', 'DESC')->where('guid', 'cart')->first()) {
      $pages_entity_rows = $this->getEntityRows($curr_entity->id, 'pages');
    } else {
      $curr_entity = new stdClass();
      $curr_entity->titolo = 'Ordina';
      $curr_entity->guid = 'ordina';
      $curr_entity->testo = '';
      $curr_entity->seo_title = 'Concludi il tuo ordine';
      $curr_entity->seo_description = 'Concludi il tuo ordine';
    }



    // $riepilogo_order_data->imponibile_total;
		// $riepilogo_order_data->iva_total;
		// $riepilogo_order_data->pay_total;
		// $riepilogo_order_data->promo_total;
		// $riepilogo_order_data->spese_spedizione;
		// $riepilogo_order_data->spese_spedizione_imponibile;
		// $riepilogo_order_data->total;
		// $riepilogo_order_data->peso_totale_grammi;
		// $riepilogo_order_data->peso_totale_kg;








    $stripeOB = new \stdClass();
		\Stripe\Stripe::setApiKey(env('custom.stripe_secret_key'));
		$intent = \Stripe\PaymentIntent::create([
			'amount' => $riepilogo_order_data->pay_total * 100,
			'currency' => 'eur',
			// Verify your integration in this guide by including this parameter
			'metadata' => [
				'order_id' => $riepilogo_order_data->id,
				'order_type' => 'ORDER',
			],
			// 'payment_method_types' => ['card', 'klarna'],

			'payment_method_types' => [
				'card',
				// 'klarna'
			],
		]);

    $stripeOB->intent = $intent;
		$stripeOB->current_pay_page = route_to('web_shop_pay_now', $riepilogo_order_data->id);
		$stripeOB->ok_pay_page = route_to('web_shop_pay_completed', $riepilogo_order_data->id);

    $order_stripe_pi_data = [
			'stripe_pi' => $intent->id,
		];
		$this->shop_orders_model->update($riepilogo_order_data->id, $order_stripe_pi_data);



   
    $curr_entity->stripeOB = $stripeOB;
    $curr_entity->riepilogo_order_data = $riepilogo_order_data;
    $curr_entity->orders_items = $orders_items;

    $this->web_ui_date->fill((array)$curr_entity);
    $this->web_ui_date->entity_rows = $pages_entity_rows;
    //
    if (appIsFile($this->base_view_filesystem . 'shop/pay-order.php')) {
      return view($this->base_view_namespace . 'shop/pay-order', $this->web_ui_date->toArray());
    }
    throw \CodeIgniter\Exceptions\FrameworkException::forInvalidFile('View file not found - shop/pay-order.php - ');
  }
/*
<?php
// webhook.php
//
// Use this sample code to handle webhook events in your integration.
//
// 1) Paste this code into a new file (webhook.php)
//
// 2) Install dependencies
//   composer require stripe/stripe-php
//
// 3) Run the server on http://localhost:4242
//   php -S localhost:4242

require 'vendor/autoload.php';

// The library needs to be configured with your account's secret key.
// Ensure the key is kept out of any version control system you might be using.
$stripe = new \Stripe\StripeClient('sk_test_...');

// This is your Stripe CLI webhook secret for testing your endpoint locally.
$endpoint_secret = 'whsec_19f5f6b3bcee7ac3b2fa968931e5a37a86e15c1257227e5a2fa24925819c20d2';

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
  $event = \Stripe\Webhook::constructEvent(
    $payload, $sig_header, $endpoint_secret
  );
} catch(\UnexpectedValueException $e) {
  // Invalid payload
  http_response_code(400);
  exit();
} catch(\Stripe\Exception\SignatureVerificationException $e) {
  // Invalid signature
  http_response_code(400);
  exit();
}

// Handle the event
switch ($event->type) {
  case 'checkout.session.async_payment_failed':
    $session = $event->data->object;
  case 'checkout.session.async_payment_succeeded':
    $session = $event->data->object;
  case 'checkout.session.completed':
    $session = $event->data->object;
  case 'checkout.session.expired':
    $session = $event->data->object;
  case 'payment_intent.amount_capturable_updated':
    $paymentIntent = $event->data->object;
  case 'payment_intent.canceled':
    $paymentIntent = $event->data->object;
  case 'payment_intent.created':
    $paymentIntent = $event->data->object;
  case 'payment_intent.partially_funded':
    $paymentIntent = $event->data->object;
  case 'payment_intent.payment_failed':
    $paymentIntent = $event->data->object;
  case 'payment_intent.processing':
    $paymentIntent = $event->data->object;
  case 'payment_intent.requires_action':
    $paymentIntent = $event->data->object;
  case 'payment_intent.succeeded':
    $paymentIntent = $event->data->object;
  case 'payout.canceled':
    $payout = $event->data->object;
  case 'payout.created':
    $payout = $event->data->object;
  case 'payout.failed':
    $payout = $event->data->object;
  case 'payout.paid':
    $payout = $event->data->object;
  case 'payout.reconciliation_completed':
    $payout = $event->data->object;
  case 'payout.updated':
    $payout = $event->data->object;
  // ... handle other event types
  default:
    echo 'Received unknown event type ' . $event->type;
}

http_response_code(200);
*/
}
