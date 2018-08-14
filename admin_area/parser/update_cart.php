<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/fashion_store/core/init.php';
$mode = sanitize($_POST['mode']);
$edit_id = sanitize($_POST['edit_id']);
$edit_size = sanitize($_POST['edit_size']);

$cartQ = $db->query("select * from cart where id='{$cart_id}'");
$result = mysqli_fetch_assoc($cartQ);
$items = json_decode($result['items'],true);
$update_items = array();
$domain = (($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false);

if($mode == 'removeone'){
    foreach ($items as $item){
        if ($item['id'] == $edit_id && $item['size'] == $edit_size){
            $item['quantity'] = $item['quantity']-1;
            
        }
        if ($item['quantity'] > 0){
            $update_items[] = $item;
        }
    }
}

if($mode == 'addone'){
    foreach ($items as $item){
        if ($item['id'] == $edit_id && $item['size'] == $edit_size){
            $item['quantity'] = $item['quantity']+1;
            
        }
        $update_items[] = $item;
    }
}

if (!empty($update_items)){
    $json_updated = json_encode($update_items);
    $db->query("update cart set items='{$json_updated}' where id='{$cart_id}'");
    $_SESSION['success_flash'] = 'Your Shopping cart has been updated';
}

if (empty($update_items)){
    $db->query("delete from cart where id='{$cart_id}'");
    setcookie(CART_COOKIE,'',1,"/",$domain,false);
}