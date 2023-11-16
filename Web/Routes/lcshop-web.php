<?php

$req = \Config\Services::request();
if (!$req->isCLI()) {
	$uri = $req->getUri();
	$supportedLocales = config(App::class)->{'supportedLocales'};
	$supportedLocalesWithoutDefault = array_diff($supportedLocales, array($req->getDefaultLocale()));
	if (in_array($uri->getSegment(1), $supportedLocalesWithoutDefault)) {
		// 
		if (env('custom.has_shop') === TRUE) {
			$routes->match(['get', 'post'], '{locale}/shop/empty-cart', '\LcShop\Web\Controllers\Shop::emptyCart', ['as' => $uri->getSegment(1) . 'web_shop_cart_empty']);
			$routes->match(['get', 'post'], '{locale}/shop/remove-cart-row/(:any)', '\LcShop\Web\Controllers\Shop::cartRemoveRow/$1', ['as' => $uri->getSegment(1) . 'web_shop_cart_remove_row']);
			$routes->match(['get', 'post'], '{locale}/shop/increment-qnt/(:any)', '\LcShop\Web\Controllers\Shop::cartIncrementQnt/$1', ['as' => $uri->getSegment(1) . 'web_shop_cart_increment_qnt']);
			$routes->match(['get', 'post'], '{locale}/shop/decrement-qnt/(:any)', '\LcShop\Web\Controllers\Shop::cartDecrementQnt/$1', ['as' => $uri->getSegment(1) . 'web_shop_cart_decrement_qnt']);
			$routes->match(['get', 'post'], '{locale}/shop/cart', '\LcShop\Web\Controllers\Shop::carrello', ['as' => $uri->getSegment(1) . 'web_shop_cart']);
			// 
			$routes->match(['get', 'post'], '{locale}/shop/product/(:segment)/(:segment)', '\LcShop\Web\Controllers\Shop::detail/$1/$2', ['as' => $uri->getSegment(1) . 'web_shop_detail_model']);
			$routes->match(['get', 'post'], '{locale}/shop/product/(:segment)', '\LcShop\Web\Controllers\Shop::detail/$1', ['as' => $uri->getSegment(1) . 'web_shop_detail']);
			$routes->match(['get', 'post'], '{locale}/shop/(:segment)', '\LcShop\Web\Controllers\Shop::index/$1', ['as' => $uri->getSegment(1) . 'web_shop_category']);
			$routes->match(['get', 'post'], '{locale}/shop', '\LcShop\Web\Controllers\Shop::index', ['as' => $uri->getSegment(1) . 'web_shop_home']);
		}
	}
}



// $routes->match(['get', 'post'], 'login', '\Lc5\Web\Controllers\Users\UserDashboard::login', ['as' => 'web_login']);
// $routes->match(['get', 'post'], 'registrati', '\Lc5\Web\Controllers\Users\UserDashboard::signUp', ['as' => 'web_signup']);
// $routes->get('email-template/(:any)', '\Lc5\Web\Controllers\Users\UserDashboard::vediEmailTemplate/$1');
// $routes->match(['get', 'post'], 'recupera-password/(:any)', '\Lc5\Web\Controllers\Users\UserDashboard::recuperaPasswordS1/$1', ['as' => 'web_recupera_password_s1_action']);
// $routes->match(['get', 'post'], 'recupera-password', '\Lc5\Web\Controllers\Users\UserDashboard::recuperaPasswordS1', ['as' => 'web_recupera_password_s1']);
// $routes->match(['get', 'post'], 'crea-nuova-password/(:any)', '\Lc5\Web\Controllers\Users\UserDashboard::recuperaPasswordS2/$1', ['as' => 'web_recupera_password_s2']);
// $routes->match(['get'], 'attiva-account/(:any)', '\Lc5\Web\Controllers\Users\UserDashboard::attivaAccount/$1', ['as' => 'web_attiva_account']);
// $routes->match(['get'], 'logout', '\Lc5\Web\Controllers\Users\UserDashboard::logout', ['as' => 'web_logout']);



// $routes->group('user-zone', ['namespace' => '\Lc5\Web\Controllers\Users', 'filter' => 'web_users_auth'], function ($routes) {

// 	$routes->group('user-settings', function ($routes) {
// 		// $routes->add('membership', 'UserSettings::membershipList', ['as' => 'web_user_settings_membership']);
// 		// $routes->match(['get', 'post'], 'profiles/(:num)', 'UserSettings::profilesEdit/$1', ['as' => 'web_user_settings_profile_edit']);
// 		// $routes->add('profiles/delete/(:num)', 'UserSettings::profilesDelete/$1', ['as' => 'web_user_settings_profile_delete']);
// 		// $routes->add('profiles', 'UserSettings::profilesList', ['as' => 'web_user_settings_profiles']);
// 		$routes->match(['get', 'post'], '/', 'UserSettings::userAccount', ['as' => 'web_user_settings_account']);
// 	});
// 	// 
// 	$routes->add('', 'UserDashboard::personalDashboard', ['as' => 'web_dashboard']);
// });







// $routes->match(['get', 'post'], '/payment-stripe-webhook', '\App\Controllers\App\Webhooks::paymentStripeWebhook', ['as' => 'payment_stripe_webhook']);

// 
$routes->match(['get', 'post'], 'shop/empty-cart', '\LcShop\Web\Controllers\Shop::emptyCart', ['as' => 'web_shop_cart_empty']);
$routes->match(['get', 'post'], 'shop/remove-cart-row/(:any)', '\LcShop\Web\Controllers\Shop::cartRemoveRow/$1', ['as' => 'web_shop_cart_remove_row']);
$routes->match(['get', 'post'], 'shop/increment-qnt/(:any)', '\LcShop\Web\Controllers\Shop::cartIncrementQnt/$1', ['as' => 'web_shop_cart_increment_qnt']);
$routes->match(['get', 'post'], 'shop/decrement-qnt/(:any)', '\LcShop\Web\Controllers\Shop::cartDecrementQnt/$1', ['as' => 'web_shop_cart_decrement_qnt']);
$routes->match(['get', 'post'], 'shop/carrello', '\LcShop\Web\Controllers\Shop::carrello', ['as' => 'web_shop_cart']);
// 
$routes->match(['get', 'post'], 'shop/prodotto/(:segment)/(:segment)', '\LcShop\Web\Controllers\Shop::detail/$1/$2', ['as' => 'web_shop_detail_model']);
$routes->match(['get', 'post'], 'shop/prodotto/(:segment)', '\LcShop\Web\Controllers\Shop::detail/$1', ['as' => 'web_shop_detail']);
$routes->match(['get', 'post'], 'shop/(:segment)', '\LcShop\Web\Controllers\Shop::index/$1', ['as' => 'web_shop_category']);
$routes->match(['get', 'post'], 'shop', '\LcShop\Web\Controllers\Shop::index', ['as' => 'web_shop_home']);
