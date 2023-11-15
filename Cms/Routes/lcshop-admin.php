<?php
//----------------------------------------------------------------------------
//------------------- LC Shop
//----------------------------------------------------------------------------

if (env('custom.hide_lc_cms') === TRUE) {
} else {

	$routes->group('lc-admin', ['namespace' => 'LcShop\Cms\Controllers', 'filter' => 'admin_auth'], function ($routes) {
		$routes->group('shop', function ($routes) {
			$routes->group('products', function ($routes) {
				$routes->get('delete/(:num)', 'ShopProducts::delete/$1', ['as' => 'lc_shop_prod_delete']);
				$routes->match(['get', 'post'], 'edit/(:num)', 'ShopProducts::edit/$1', ['as' => 'lc_shop_prod_edit']);
				$routes->match(['get', 'post'], 'newpost/(:num)', 'ShopProducts::newpost/$1', ['as' => 'lc_shop_prod_new_sub']);
				$routes->match(['get', 'post'], 'newpost', 'ShopProducts::newpost', ['as' => 'lc_shop_prod_new']);
				$routes->get('', 'ShopProducts::index', ['as' => 'lc_shop_prod']);
			});
			$routes->group('categories', function ($routes) {
				$routes->get('delete/(:num)', 'ShopProductsCategories::delete/$1', ['as' => 'lc_shop_prod_cat_delete']);
				$routes->match(['get', 'post'], 'edit/(:num)', 'ShopProductsCategories::edit/$1', ['as' => 'lc_shop_prod_cat_edit']);
				$routes->match(['get', 'post'], 'newpost', 'ShopProductsCategories::newpost', ['as' => 'lc_shop_prod_cat_new']);
				$routes->get('', 'ShopProductsCategories::index', ['as' => 'lc_shop_prod_cat']);
			});
			$routes->group('tags', function ($routes) {
				$routes->get('delete/(:num)', 'ShopProductsTags::delete/$1', ['as' => 'lc_shop_prod_tags_delete']);
				$routes->match(['get', 'post'], 'combo-newpost', 'ShopProductsTags::ajaxCreate', ['as' => 'lc_shop_prod_tags_data_new']);
				$routes->match(['get', 'post'], 'edit/(:num)', 'ShopProductsTags::edit/$1', ['as' => 'lc_shop_prod_tags_edit']);
				$routes->match(['get', 'post'], 'newpost', 'ShopProductsTags::newpost', ['as' => 'lc_shop_prod_tags_new']);
				$routes->get('', 'ShopProductsTags::index', ['as' => 'lc_shop_prod_tags']);
			});
			$routes->group('taglie', function ($routes) {
				$routes->get('delete/(:num)', 'ShopProductsSizes::delete/$1', ['as' => 'lc_shop_prod_sizes_delete']);
				$routes->match(['get', 'post'], 'combo-newpost', 'ShopProductsSizes::ajaxCreate', ['as' => 'lc_shop_prod_sizes_data_new']);
				$routes->match(['get', 'post'], 'edit/(:num)', 'ShopProductsSizes::edit/$1', ['as' => 'lc_shop_prod_sizes_edit']);
				$routes->match(['get', 'post'], 'newpost', 'ShopProductsSizes::newpost', ['as' => 'lc_shop_prod_sizes_new']);
				$routes->get('', 'ShopProductsSizes::index', ['as' => 'lc_shop_prod_sizes']);
			});
			$routes->group('colori', function ($routes) {
				$routes->get('delete/(:num)', 'ShopProductsColors::delete/$1', ['as' => 'lc_shop_prod_colors_delete']);
				$routes->match(['get', 'post'], 'combo-newpost', 'ShopProductsColors::ajaxCreate', ['as' => 'lc_shop_prod_colors_data_new']);
				$routes->match(['get', 'post'], 'edit/(:num)', 'ShopProductsColors::edit/$1', ['as' => 'lc_shop_prod_colors_edit']);
				$routes->match(['get', 'post'], 'newpost', 'ShopProductsColors::newpost', ['as' => 'lc_shop_prod_colors_new']);
				$routes->get('', 'ShopProductsColors::index', ['as' => 'lc_shop_prod_colors']);
			});
			$routes->group('aliquote-iva', function ($routes) {
				$routes->get('delete/(:num)', 'ShopAliquote::delete/$1', ['as' => 'lc_shop_aliquote_delete']);
				$routes->match(['get', 'post'], 'edit/(:num)', 'ShopAliquote::edit/$1', ['as' => 'lc_shop_aliquote_edit']);
				$routes->match(['get', 'post'], 'newpost', 'ShopAliquote::newpost', ['as' => 'lc_shop_aliquote_new']);
				$routes->get('', 'ShopAliquote::index', ['as' => 'lc_shop_aliquote']);
			});
			$routes->match(['get', 'post'], 'settings', 'ShopSettings::edit', ['as' => 'lc_shop_settings']); //, ['filter' => 'noauth']

		});
	});
}



//----------------------------------------------------------------------------
//------------------- FINE LC Shop
//----------------------------------------------------------------------------
