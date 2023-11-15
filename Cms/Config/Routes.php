<?php

namespace Config;

if (file_exists(APPPATH . 'Routes/lcshop-admin.php')) {
    require APPPATH . 'Routes/lcshop-admin.php';
} else if (file_exists(ROOTPATH . 'LcShop/Cms/Routes/lcshop-admin.php')) {
    require ROOTPATH . 'LcShop/Cms/Routes/lcshop-admin.php';
}
