<?php

namespace Config;

// if (file_exists(ROOTPATH.'lc5/cms/Routes/api-custom.php')) {
// 	require ROOTPATH.'lc5/cms/Routes/api-custom.php';
// }else if (file_exists(ROOTPATH.'lc5/cms/Routes/api.php')) {
// 	require ROOTPATH.'lc5/cms/Routes/api.php';
// }

if (file_exists(APPPATH . 'Routes/shop-web.php')) {
	require APPPATH . 'Routes/shop-web.php';
}else if (file_exists(ROOTPATH.'LcShop/Web/Routes/shop-web.php')) {
	require ROOTPATH.'LcShop/Web/Routes/shop-web.php';
}
