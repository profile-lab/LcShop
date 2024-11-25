<?php

$req = \Config\Services::request();
if (!$req->isCLI()) {
	$uri = $req->getUri();
    $supportedLocales = config("APP")->{'supportedLocales'};
	$supportedLocalesWithoutDefault = array_diff($supportedLocales, array($req->getDefaultLocale()));
	if (in_array($uri->getSegment(1), $supportedLocalesWithoutDefault)) {
		// 
		if (env('custom.has_shop') === TRUE) {
			$routes->match(['get', 'post'], '{locale}/shop/empty-cart', '\LcShop\Web\Controllers\Shop::emptyCart', ['as' => $uri->getSegment(1) . 'web_shop_cart_empty']);
			$routes->match(['get', 'post'], '{locale}/shop/remove-cart-row/(:any)', '\LcShop\Web\Controllers\Shop::cartRemoveRow/$1', ['as' => $uri->getSegment(1) . 'web_shop_cart_remove_row']);
			$routes->match(['get', 'post'], '{locale}/shop/increment-qnt/(:any)', '\LcShop\Web\Controllers\Shop::cartIncrementQnt/$1', ['as' => $uri->getSegment(1) . 'web_shop_cart_increment_qnt']);
			$routes->match(['get', 'post'], '{locale}/shop/decrement-qnt/(:any)', '\LcShop\Web\Controllers\Shop::cartDecrementQnt/$1', ['as' => $uri->getSegment(1) . 'web_shop_cart_decrement_qnt']);
			$routes->match(['get', 'post'], '{locale}/shop/make-order', '\LcShop\Web\Controllers\Shop::makeOrder', ['as' => $uri->getSegment(1) . 'web_shop_make_order']);
			$routes->match(['get', 'post'], '{locale}/shop/cart', '\LcShop\Web\Controllers\Shop::carrello', ['as' => $uri->getSegment(1) . 'web_shop_cart']);
			// 
			$routes->match(['get', 'post'], '{locale}/shop/product/(:segment)/(:segment)', '\LcShop\Web\Controllers\Shop::detail/$1/$2', ['as' => $uri->getSegment(1) . 'web_shop_detail_model']);
			$routes->match(['get', 'post'], '{locale}/shop/product/(:segment)', '\LcShop\Web\Controllers\Shop::detail/$1', ['as' => $uri->getSegment(1) . 'web_shop_detail']);
			$routes->match(['get', 'post'], '{locale}/shop/(:segment)', '\LcShop\Web\Controllers\Shop::index/$1', ['as' => $uri->getSegment(1) . 'web_shop_category']);
			$routes->match(['get', 'post'], '{locale}/shop', '\LcShop\Web\Controllers\Shop::index', ['as' => $uri->getSegment(1) . 'web_shop_home']);
		}
	}
}

// $routes->match(['get', 'post'], '/payment-stripe-webhook', '\App\Controllers\App\Webhooks::paymentStripeWebhook', ['as' => 'payment_stripe_webhook']);
$routes->match(['get', 'post'], '/payment-stripe-webhook', '\LcShop\Web\Controllers\Payment::orderPaymentStripeWebhook', ['as' => 'order_payment_stripe_webhook']);

$routes->match(['get', 'post'], 'shop/google-merchants-feed', '\LcShop\Web\Controllers\Shop::googleMerchantsFeed', ['as' => 'web_shop_google_merchants_feed']);
// 
$routes->match(['get', 'post'], 'shop/empty-cart', '\LcShop\Web\Controllers\Shop::emptyCart', ['as' => 'web_shop_cart_empty']);
$routes->match(['get', 'post'], 'shop/remove-cart-row/(:any)', '\LcShop\Web\Controllers\Shop::cartRemoveRow/$1', ['as' => 'web_shop_cart_remove_row']);
$routes->match(['get', 'post'], 'shop/increment-qnt/(:any)', '\LcShop\Web\Controllers\Shop::cartIncrementQnt/$1', ['as' => 'web_shop_cart_increment_qnt']);
$routes->match(['get', 'post'], 'shop/decrement-qnt/(:any)', '\LcShop\Web\Controllers\Shop::cartDecrementQnt/$1', ['as' => 'web_shop_cart_decrement_qnt']);
$routes->match(['get', 'post'], 'shop/pay-order-on-stripe/(:any)', '\LcShop\Web\Controllers\Payment::payOrderOnStripeApp/$1', ['as' => 'web_shop_pay_on_stripe_app']);
$routes->match(['get', 'post'], 'shop/pay-order-now/(:any)', '\LcShop\Web\Controllers\Payment::payOrderNow/$1', ['as' => 'web_shop_pay_now']);
$routes->match(['get', 'post'], 'shop/pay-completed/(:any)', '\LcShop\Web\Controllers\Payment::payOrderCompleted/$1', ['as' => 'web_shop_pay_completed']);
$routes->match(['get', 'post'], 'shop/pay-canceled/(:any)', '\LcShop\Web\Controllers\Payment::payOrderCanceled/$1', ['as' => 'web_shop_pay_canceled']);
$routes->match(['get', 'post'], 'shop/payment-info', '\LcShop\Web\Controllers\Shop::payment', ['as' => 'web_shop_payment']);
$routes->match(['get', 'post'], 'shop/shipping-info', '\LcShop\Web\Controllers\Shop::makeOrder', ['as' => 'web_shop_make_order']);
$routes->match(['get', 'post'], 'shop/carrello', '\LcShop\Web\Controllers\Shop::carrello', ['as' => 'web_shop_cart']);
// 
$routes->match(['get', 'post'], 'shop/prodotto/(:segment)/(:segment)', '\LcShop\Web\Controllers\Shop::detail/$1/$2', ['as' => 'web_shop_detail_model']);
$routes->match(['get', 'post'], 'shop/prodotto/(:segment)', '\LcShop\Web\Controllers\Shop::detail/$1', ['as' => 'web_shop_detail']);
$routes->match(['get', 'post'], 'shop/(:segment)', '\LcShop\Web\Controllers\Shop::index/$1', ['as' => 'web_shop_category']);
$routes->match(['get', 'post'], 'shop', '\LcShop\Web\Controllers\Shop::index', ['as' => 'web_shop_home']);

$routes->group('user', ['namespace' => '\LcUsers\Web\Controllers', 'filter' => 'appUserFilter'], function ($routes) {
    $routes->match(['get','post'], 'orders/(:num)', 'User::shopOrderDett/$1', ['as' => 'web_user_order_dett']);
    $routes->match(['get','post'], 'orders', 'User::shopOrders', ['as' => 'web_user_orders']);
});
