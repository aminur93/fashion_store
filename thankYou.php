<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'core/init.php';

// Set your secret key: remember to change this to your live secret key in production
// See your keys here: https://dashboard.stripe.com/account/apikeys
\Stripe\Stripe::setApiKey(STRIPE_PRIVATE);

// Token is created using Stripe.js or Checkout!
// Get the payment token ID submitted by the form:
$token = $_POST['stripeToken'];
//Get the rest of post data
$ful_name = sanitize($_POST['full_name']);
$email = sanitize($_POST['email']);
$street = sanitize($_POST['street']);
$street2 = sanitize($_POST['street2']);
$city = sanitize($_POST['city']);
$state = sanitize($_POST['state']);
$zip_code = sanitize($_POST['zip_code']);
$country = sanitize($_POST['country']);
$tax = sanitize($_POST['tax']);
$sub_total = sanitize($_POST['sub_total']);
$grand_total = sanitize($_POST['grand_total']);
$cart_id = sanitize($_POST['cart_id']);
$description = sanitize($_POST['description']);
$charge_amount = number_format($grand_total,2)*100;
$metadata = array(
    "cart_id" => $cart_id,
    "tax" =>$tax,
    "sub_total" => $sub_total,
);


// Charge the user's card:
try{
$charge = \Stripe\Charge::create(array(
  "amount" => $charge_amount,
  "currency" => CURRENCY,
  "description" => $description,
  "source" => $token,
  "receipt_email" => $email,
    "metadata" =>$metadata
));

//adjust inventriy
$itemQ = $db->query("select * from cart where id = '{$cart_id}'");
$iresults = mysqli_fetch_assoc($itemQ);
$items = json_decode($iresults['items'],true);
foreach ($items as $item){
    $newSizes = array();
    $item_id = $item['id'];
    $productQ = $db->query("select sizes from products where id='{$item_id}'");
    $product = mysqli_fetch_assoc($productQ);
    $sizes = sizesToArray($product['sizes']);
    foreach ($sizes as $size){
        if ($size['size'] == $item['size']){
            $q = $size['quantity']-$item['quantity'];
            $newSizes[] = array('size' => $size['size'], 'quantity' => $q, 'threshold' => $q);
        }  else {
            $newSizes[] = array('size' => $size['size'], 'quantity' => $size['quantity'], 'threshold' => $size['threshold']);
        }
    }
    $sizeString = sizesToString($newSizes);
    $db->query("update products set sizes = '{$sizeString}' where id = '{$item_id}'");
}

//update cart
$db->query("update cart set paid=1 where id='{$cart_id}'");
$db->query("insert into transactions
    (charge_id,cart_id,full_name,email,street,street2,city,state,zip_code,country,sub_total,tax,grand_total,description,tax_type)
    values('$charge->id','$cart_id','$ful_name','$email','$street','$street2','$city','$state','$zip_code','$country','$sub_total','$tax','$grand_total','$description','$charge->object')");
     
$domain = ($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false;
setcookie(CART_COOKIE,'',1,"/",$domain,false);
include 'inc/head.php';
include 'inc/navigation.php';
include 'inc/headerpartial.php';
?>
<h1 class="text-center text-success">Thank You!</h1>
<p class="text-center text-primary">Your card has been successfully charged <?=  money($grand_total);?>.
you have been email reciept. please check your spam folder if it is not your inbox.Additionally you can print 
 this page as a receipt. 
</p>

<p class="text-center text-primary">Your Receipt Number is: <strong><?=$cart_id;?></strong></p>
<p class="text-center text-primary">Your Order Shipped in This Address!</p>

<address class="text-center text-primary">
    <?=$ful_name;?><br>
    <?=$street;?>
    <?=(($street2 != '')?$street2.'<br>':'');?>
    <?=$city.','.$state.','.$zip_code;?>
    <?=$country;?>
</address>
<?php
include 'inc/footer.php';
}  catch (\Stripe\Error\Card $e){
    echo $e;
}
?>