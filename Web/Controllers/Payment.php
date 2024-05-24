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
use CodeIgniter\Email\Email;

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
	public function payOrderCompleted($order_id)
	{
		$riepilogo_order_data =  $this->shop_orders_model->where('id', $order_id)->where('user_id', $this->appuser->getUserId())->first();
		if (!$riepilogo_order_data) {
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
		if ($curr_entity = $pages_model->asObject()->orderBy('id', 'DESC')->where('guid', 'pay-order-completed')->first()) {
			$pages_entity_rows = $this->getEntityRows($curr_entity->id, 'pages');
		} else {
			$curr_entity = new stdClass();
			$curr_entity->titolo = 'Ordine completato';
			$curr_entity->guid = 'pay-order-completed';
			$curr_entity->testo = '';
			$curr_entity->seo_title = 'Acquisto completato';
			$curr_entity->seo_description = 'Acquisto completato';
		}
		$curr_entity->riepilogo_order_data = $riepilogo_order_data;
		$curr_entity->orders_items = $orders_items;
		$this->web_ui_date->fill((array)$curr_entity);
		$this->web_ui_date->entity_rows = $pages_entity_rows;


		return view(customOrDefaultViewFragment('shop/pay-order-completed', 'LcShop'), $this->web_ui_date->toArray());

	}
	//--------------------------------------------------------------------
	public function payOrderNow($order_id)
	{


		// $order_data = $this->getOrderData();
		// $all_user_data = $this->appuser->getAllUserData();
		$riepilogo_order_data =  $this->shop_orders_model->where('id', $order_id)->where('user_id', $this->appuser->getUserId())->first();
		if (!$riepilogo_order_data) {
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
		if ($curr_entity = $pages_model->asObject()->orderBy('id', 'DESC')->where('guid', 'pay-order-now')->first()) {
			$pages_entity_rows = $this->getEntityRows($curr_entity->id, 'pages');
		} else {
			$curr_entity = new stdClass();
			$curr_entity->titolo = 'Completa l\'acquisto';
			$curr_entity->guid = 'pay-order-now';
			$curr_entity->testo = '';
			$curr_entity->seo_title = 'Concludi il tuo ordine';
			$curr_entity->seo_description = 'Concludi il tuo ordine';
		}




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
		return view(customOrDefaultViewFragment('shop/pay-order', 'LcShop'), $this->web_ui_date->toArray());
		//
		// if (appIsFile($this->base_view_filesystem . 'shop/pay-order.php')) {
		//   return view($this->base_view_namespace . 'shop/pay-order', $this->web_ui_date->toArray());
		// }
		// throw \CodeIgniter\Exceptions\FrameworkException::forInvalidFile('View file not found - shop/pay-order.php - ');
	}

	//--------------------------------------------------------------------
	public function orderPaymentStripeWebhook()
	{
		$fp = fopen(__DIR__ . '/stripe_log.txt', 'a+'); //opens file in append mode  
		$payload = @file_get_contents('php://input');
		$event = null;

		echo $payload;
		fwrite($fp, $payload);
		fwrite($fp, '
---------
');
		fclose($fp);


		try {
			$event = \Stripe\Event::constructFrom(
				json_decode($payload, true)
			);
		} catch (\UnexpectedValueException $e) {
			// Invalid payload
			http_response_code(400);
			exit();
		}

		$paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent
		$pi_ID = $paymentIntent->id;
		$pi_metadata = $paymentIntent->metadata;
		$order_id = $pi_metadata->order_id;
		$order_type = $pi_metadata->order_type;
		// Handle the event
		switch ($event->type) {
				// case 'payment_intent.created':
				//   break;
			case 'payment_intent.succeeded':
				$this->updateObjectOnDB_succeeded($order_type, $order_id, $pi_ID);
				break;
			case 'payment_intent.payment_failed':
				$this->updateObjectOnDB_failed($order_type, $order_id, $pi_ID);
				break;
			case 'payment_intent.canceled':
				$this->updateObjectOnDB_canceled($order_type, $order_id, $pi_ID);
				break;
				// case 'payment_method.attached':
				//   break;
			default:
				http_response_code(400);
				exit();
		}

		http_response_code(200);
	}

	//--------------------------------------------------------------------
	private function updateObjectOnDB_succeeded($order_type, $order_id, $stripe_pi)
	{


		switch ($order_type) {
			case 'ORDER':

				$riepilogo_order_data =  $this->shop_orders_model->where('id', $order_id)->where('stripe_pi', $stripe_pi)->first();
				if (!$riepilogo_order_data) {
					throw new \CodeIgniter\Exceptions\PageNotFoundException('Ordine non trovato');
				}


				$data = [
					'payment_type' => 'STRIPE',
					'order_status' => 'ORDER',
					'payment_status' => 'COMPLETED',
					'last_status_change' => date('Y-m-d H:i:s'),
					'payed_at' => date('Y-m-d H:i:s'),
				];
				$this->shop_orders_model->update($riepilogo_order_data->id, $data);


				$ripilogo_products = '<table width="100%">';
				$orders_items = $this->shop_orders_items_model->where('order_id', $riepilogo_order_data->id)->findAll();
				foreach ($orders_items as $orders_item) {
					$ripilogo_products .= '<tr>';
					$ripilogo_products .= '<td width="15%">' . $orders_item->qnt . '</td>';
					$ripilogo_products .= '<td width="50%">' . $orders_item->full_nome_prodotto . '</td>';
					$ripilogo_products .= '<td width="30%">€ ' . $orders_item->prezzo_formatted . '</td>';
					$ripilogo_products .= '</tr>';
					if ($orders_item->reference_type == 'product') {
						if ($orders_item->id_modello > 0) {
							$current_product = $this->shop_products_model->select('id, giacenza, um')->where('id', $orders_item->id_modello)->first();
						} else {
							$current_product = $this->shop_products_model->select('id, giacenza, um')->where('id', $orders_item->id_prodotto)->first();
						}
						if ($current_product && $orders_item->qnt_scaricate != 1) {
							$data = [
								'giacenza' => $current_product->giacenza - $orders_item->qnt
							];
							$this->shop_products_model->update($current_product->id, $data);

							$dataUpdateOrderItem = [
								'qnt_scaricate' => 1
							];
							$this->shop_orders_items_model->update($orders_item->id, $dataUpdateOrderItem);

						}
					}
				}
				$ripilogo_products .= '</table>';




				// //
				$users_model = new \LcUsers\Data\Models\AppUsersDatasModel();

				$datiUser = $users_model
					->where('id', $riepilogo_order_data->user_id)
					->first();
				$dati_email = array(
					'name' => $datiUser->name, 
					'surname' => $datiUser->surname, 
					'email' => $datiUser->email, 
					'order_id' => $riepilogo_order_data->id, 
					'order_total' => $riepilogo_order_data->pay_total, 
					'ripilogo_products' => $ripilogo_products
				);
				$email_subject = 'Il tuo ordine ' . '=?UTF-8?B?' . base64_encode(env('custom.app_name')) . '?=';
				$this->send_email_pagamento_ok($datiUser->email, $dati_email, $email_subject, 'pay_cart_ok');
				$toAddress = env('custom.default_to_address');
				if(trim($toAddress) != ''){
					$email_subject = 'Nuovo ordine ricevuto su ' . '=?UTF-8?B?' . base64_encode(env('custom.app_name')) . '?=';
					$this->send_email_pagamento_ok($toAddress, $dati_email, $email_subject, 'nuovo_ordine_ricevuto');
				}

				break;
				// 
				// 

		}
	}

	//--------------------------------------------------------------------
	private function updateObjectOnDB_failed($order_type, $order_id)
	{
	}
	//--------------------------------------------------------------------
	private function updateObjectOnDB_canceled($order_type, $order_id)
	{
	}
	//--------------------------------------------------------------------
	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	//--------------------------------------------------------------------
	protected function inviaEmail($toAddress, $mailSubject,  $htmlbody)
	{

		if (env('custom.email.protocol') == 'mailgun_api') {
			return $this->inviaEmailMailGunApi($toAddress, $mailSubject,  $htmlbody);
		} elseif (env('custom.email.protocol') == 'smtp') {
			return $this->inviaEmailSMTP($toAddress, $mailSubject,  $htmlbody);
		}
	}


	//--------------------------------------------------------------------
	protected function inviaEmailMailGunApi($toAddress, $mailSubject,  $htmlbody)
	{
		if (!env('custom.email.MailGunSigningKey') || !env('custom.email.MailGunDomain')) {
			return FALSE;
		}
		$refMailGunClass = '\Mailgun\Mailgun';
		if (class_exists($refMailGunClass)) {
			$mg = $refMailGunClass::create(env('custom.email.MailGunSigningKey'), 'https://api.eu.mailgun.net'); // For EU servers
			$mailGun_message = $mg->messages()->send(env('custom.email.MailGunDomain'), [
				'from'    => env('custom.from_address'), //env('custom.from_name')
				'to'      => $toAddress,
				'subject' => $mailSubject,
				'html'    =>  $htmlbody,
				'text'    => 'Questa email è stata inviata in formato HTML. Visualizzi questo messaggio perché il tuo client di posta non supporta queste funzionalità.',

			]);
			return $mailGun_message;
		}
		return FALSE;

		// return $this->inviaEmailSMTP($toAddress, $mailSubject,  $htmlbody);
	}


	//--------------------------------------------------------------------
	protected function inviaEmailSMTP($toAddress, $mailSubject,  $htmlbody)
	{
		$filePath = ROOTPATH . 'Lc5/Web/ThirdParty/PHPMailer/language/';
		$mail = new \PHPMailer\PHPMailer\PHPMailer(true);
		$mail->setLanguage('it', $filePath);
		try {
			//Server settings
			// $mail->SMTPDebug = \PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;
			$mail->isSMTP();
			$mail->Host       = $this->send_mail_config['SMTPHost'];
			$mail->SMTPAuth   = true;
			$mail->Username   = $this->send_mail_config['SMTPUser'];
			$mail->Password   = $this->send_mail_config['SMTPPass'];
			$mail->SMTPSecure = 'tls'; // \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
			$mail->Port       = $this->send_mail_config['SMTPPort'];

			//Recipients
			$mail->setFrom(env('custom.from_address'), env('custom.from_name'));
			$mail->addAddress($toAddress);

			//Content
			$mail->isHTML(true);
			$mail->Subject = $mailSubject;
			$mail->Body    = $htmlbody;
			$mail->AltBody = 'Questa email è stata inviata in formato HTML. Visualizzi questo messaggio perché il tuo client di posta non supporta queste funzionalità.';

			$mail->send();
			return true;
		} catch (\PHPMailer\PHPMailer\Exception $e) {
			// echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
			return false;
		}
		return FALSE;
	}

	//--------------------------------------------------------------------
	private function getEnvEmailConfig()
	{
		if (env('custom.email.protocol') == 'mailgun_api') {
			return [];
		} elseif (env('custom.email.protocol') == 'smtp') {
			return [
				'mailType' => 'html',
				'SMTPHost' => env('custom.email.SMTPHost'),
				'SMTPPort' => env('custom.email.SMTPPort'), //  465,//
				'protocol' => env('custom.email.protocol'),
				'SMTPUser' => env('custom.email.SMTPUser'),
				'SMTPPass' => env('custom.email.SMTPPass'),
				// 'SMTPCrypto' => 'tls',
				// 'SMTPAuth' => true,
				// 'SMTPKeepAlive' => true,
				// 'mailPath' => '/var/qmail/mailnames',
			];
		}


		return FALSE;
	}




	private function send_email_pagamento_ok($toAddress, $dati_mess, $email_subject, $modello_html = 'pay_cart_ok')
	{


		// 
		$return_obj = new stdClass();
		$return_obj->is_send = FALSE;
		$user_mess = new stdClass();
		$user_mess->type = 'ko';
		$user_mess->title = 'Si è verificato un errore';
		$user_mess->content = '';
		//
		$htmlbody = file_get_contents(APPPATH . 'Views/email/' . $modello_html . '.html');
		$htmlbody = str_replace('{{logo_path}}', env('custom.logo_path'), $htmlbody);
		$htmlbody = str_replace('{{app_name}}', env('custom.app_name'), $htmlbody);
		$htmlbody = str_replace('{{name}}', $dati_mess['name'], $htmlbody);
		$htmlbody = str_replace('{{surname}}', $dati_mess['surname'], $htmlbody);
		$htmlbody = str_replace('{{email}}', $dati_mess['email'], $htmlbody);
		$htmlbody = str_replace('{{order_id}}', $dati_mess['order_id'], $htmlbody);
		$htmlbody = str_replace('{{order_total}}', $dati_mess['order_total'], $htmlbody);
		$htmlbody = str_replace('{{ripilogo_products}}', $dati_mess['ripilogo_products'], $htmlbody);

		// $email_subject = 'Il tuo ordine ' . '=?UTF-8?B?' . base64_encode(env('custom.app_name')) . '?=';
		if ($this->inviaEmail($toAddress, $email_subject, $htmlbody)) {
			$user_mess->type = 'ok';
			$return_obj->is_send = TRUE;
		}
		return $return_obj;
	}

	//--------------------------------------------------------------------
}
