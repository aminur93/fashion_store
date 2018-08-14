<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

define('BASEURL', $_SERVER['DOCUMENT_ROOT'].'/fashion_store/');
define('CART_COOKIE', 'SBwiz223ertyGHwiz321');
define('CART_COOKIE_EXPIRE',  time() + (86400 *30));
define('TAXRATE',0.087);

define('CURRENCY','USD');
define('CHECKOUTMODE', 'TEST');

if (CHECKOUTMODE == 'TEST'){
    define('STRIPE_PRIVATE','sk_test_TaqN6g3XckcHLyDYvFR3ZSSR');
    define('STRIPE_PUBLIC','pk_test_x04rVJt4saDAcvbFTvk0Q2xU');
}

if (CHECKOUTMODE == 'LIVE'){
    define('STRIPE_PRIVATE','');
    define('STRIPE_PUBLIC','');
}