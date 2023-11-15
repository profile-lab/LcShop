<?php

$req = \Config\Services::request();
if (!$req->isCLI()) {
	$supportedLocalesWithoutDefault = array_diff($req->config->supportedLocales, array($req->config->defaultLocale));
	if (in_array($req->uri->getSegment(1), $supportedLocalesWithoutDefault)) {
		// 
		if (env('custom.has_shop') === TRUE) {
			$routes->add('{locale}/shop/empty-cart', '\Lc5\Web\Controllers\Shop\Shop::emptyCart', ['as' => $req->uri->getSegment(1) . 'web_shop_cart_empty']);
			$routes->add('{locale}/shop/remove-cart-row/(:any)', '\Lc5\Web\Controllers\Shop\Shop::cartRemoveRow/$1', ['as' => $req->uri->getSegment(1) . 'web_shop_cart_remove_row']);
			$routes->add('{locale}/shop/increment-qnt/(:any)', '\Lc5\Web\Controllers\Shop\Shop::cartIncrementQnt/$1', ['as' => $req->uri->getSegment(1) . 'web_shop_cart_increment_qnt']);
			$routes->add('{locale}/shop/decrement-qnt/(:any)', '\Lc5\Web\Controllers\Shop\Shop::cartDecrementQnt/$1', ['as' => $req->uri->getSegment(1) . 'web_shop_cart_decrement_qnt']);
			$routes->add('{locale}/shop/cart', '\Lc5\Web\Controllers\Shop\Shop::carrello', ['as' => $req->uri->getSegment(1) . 'web_shop_cart']);
			// 
			$routes->add('{locale}/shop/product/(:segment)/(:segment)', '\Lc5\Web\Controllers\Shop\Shop::detail/$1/$2', ['as' => $req->uri->getSegment(1) . 'web_shop_detail_model']);
			$routes->add('{locale}/shop/product/(:segment)', '\Lc5\Web\Controllers\Shop\Shop::detail/$1', ['as' => $req->uri->getSegment(1) . 'web_shop_detail']);
			$routes->add('{locale}/shop/(:segment)', '\Lc5\Web\Controllers\Shop\Shop::index/$1', ['as' => $req->uri->getSegment(1) . 'web_shop_category']);
			$routes->add('{locale}/shop', '\Lc5\Web\Controllers\Shop\Shop::index', ['as' => $req->uri->getSegment(1) . 'web_shop_home']);
		}
		
	}
}



$routes->match(['get', 'post'], 'login', '\Lc5\Web\Controllers\Users\UserDashboard::login', ['as' => 'web_login']);
$routes->match(['get', 'post'], 'registrati', '\Lc5\Web\Controllers\Users\UserDashboard::signUp', ['as' => 'web_signup']);
$routes->get( 'email-template/(:any)', '\Lc5\Web\Controllers\Users\UserDashboard::vediEmailTemplate/$1');
$routes->match(['get', 'post'], 'recupera-password/(:any)', '\Lc5\Web\Controllers\Users\UserDashboard::recuperaPasswordS1/$1', ['as' => 'web_recupera_password_s1_action']);
$routes->match(['get', 'post'], 'recupera-password', '\Lc5\Web\Controllers\Users\UserDashboard::recuperaPasswordS1', ['as' => 'web_recupera_password_s1']);
$routes->match(['get', 'post'], 'crea-nuova-password/(:any)', '\Lc5\Web\Controllers\Users\UserDashboard::recuperaPasswordS2/$1', ['as' => 'web_recupera_password_s2']);
$routes->match(['get'], 'attiva-account/(:any)', '\Lc5\Web\Controllers\Users\UserDashboard::attivaAccount/$1', ['as' => 'web_attiva_account']);
$routes->match(['get'], 'logout', '\Lc5\Web\Controllers\Users\UserDashboard::logout', ['as' => 'web_logout']);



$routes->group('user-zone', ['namespace' => '\Lc5\Web\Controllers\Users', 'filter' => 'web_users_auth'], function ($routes) {
   
    $routes->group('user-settings', function ($routes) {
        // $routes->add('membership', 'UserSettings::membershipList', ['as' => 'web_user_settings_membership']);
        // $routes->match(['get', 'post'], 'profiles/(:num)', 'UserSettings::profilesEdit/$1', ['as' => 'web_user_settings_profile_edit']);
        // $routes->add('profiles/delete/(:num)', 'UserSettings::profilesDelete/$1', ['as' => 'web_user_settings_profile_delete']);
        // $routes->add('profiles', 'UserSettings::profilesList', ['as' => 'web_user_settings_profiles']);
        $routes->match(['get', 'post'], '/', 'UserSettings::userAccount', ['as' => 'web_user_settings_account']);
    });
    // 
    $routes->add('', 'UserDashboard::personalDashboard', ['as' => 'web_dashboard']);
});



// $routes->match(['get', 'post'], '/payment-stripe-webhook', '\App\Controllers\App\Webhooks::paymentStripeWebhook', ['as' => 'payment_stripe_webhook']);





// 
if (env('custom.has_shop') === TRUE) {
	$routes->add('shop/empty-cart', '\Lc5\Web\Controllers\Shop\Shop::emptyCart', ['as' => 'web_shop_cart_empty']);
	$routes->add('shop/remove-cart-row/(:any)', '\Lc5\Web\Controllers\Shop\Shop::cartRemoveRow/$1', ['as' => 'web_shop_cart_remove_row']);
	$routes->add('shop/increment-qnt/(:any)', '\Lc5\Web\Controllers\Shop\Shop::cartIncrementQnt/$1', ['as' => 'web_shop_cart_increment_qnt']);
	$routes->add('shop/decrement-qnt/(:any)', '\Lc5\Web\Controllers\Shop\Shop::cartDecrementQnt/$1', ['as' => 'web_shop_cart_decrement_qnt']);
	$routes->add('shop/carrello', '\Lc5\Web\Controllers\Shop\Shop::carrello', ['as' => 'web_shop_cart']);
	// 
	$routes->add('shop/prodotto/(:segment)/(:segment)', '\Lc5\Web\Controllers\Shop\Shop::detail/$1/$2', ['as' => 'web_shop_detail_model']);
	$routes->add('shop/prodotto/(:segment)', '\Lc5\Web\Controllers\Shop\Shop::detail/$1', ['as' => 'web_shop_detail']);
	$routes->add('shop/(:segment)', '\Lc5\Web\Controllers\Shop\Shop::index/$1', ['as' => 'web_shop_category']);
	$routes->add('shop', '\Lc5\Web\Controllers\Shop\Shop::index', ['as' => 'web_shop_home']);
}
