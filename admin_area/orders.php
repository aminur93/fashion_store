<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../core/init.php';
if (!is_logged_in()){
    header('location: log.php');
}
include 'inc/head.php';
include 'inc/navigation.php';

//complete order
if(isset($_GET['complete']) && $_GET['complete'] == 1){
    
    $cart_id = sanitize((int)$_GET['cart_id']);
    $db->query("update cart set shipped=1 where id='{$cart_id}'");
    $_SESSION['success_flash'] = 'The Order has Been Completed';
    header('Location: index.php');
}

$txn_id = sanitize((int)$_GET['txn_id']);
$txnQuery = $db->query("select * from transactions where id='{$txn_id}'");
$txn = mysqli_fetch_assoc($txnQuery);
$cart_id = $txn['cart_id'];
$cartQ = $db->query("select * from cart where id='{$cart_id}'");
$cart = mysqli_fetch_assoc($cartQ);
$items = json_decode($cart['items'],true);
$idArray = array();
$products = array();
foreach ($items as $item){
    $idArray[] = $item['id'];
}
$ids = implode(',', $idArray);
$productQ = $db->query("select i.id as 'id', i.title as 'title', c.category_id as 'cid', c.category as 'child', p.category as 'parent'
             from products i 
             LEFT JOIN category c ON i.categories = c.category_id
             LEFT JOIN category p ON c.parent = p.category_id
             where i.id IN({$ids})");
             
while ($p = mysqli_fetch_assoc($productQ)){
    foreach ($items as $item){
        if ($item['id'] == $p['id']){
            $x = $item;
            continue;
        }
    }
    $products[] = array_merge($x,$p);
}
?>
<h3 class="text-center">Items Order</h3>
<table class="table table-bordered table-condensed table-striped table-responsive">
    <thead>
    <th>Quantity</th>
    <th>Title</th>
    <th>Category</th>
    <th>Size</th>
    </thead>
    <tbody>
        <?php foreach ($products as $product):?>
        <tr>
            <td><?=$product['quantity'];?></td>
            <td><?=$product['title'];?></td>
            <td><?=$product['parent'].'~'.$product['child'];?></td>
            <td><?=$product['size'];?></td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>

<div class="row">
    <div class="col-md-6">
        <h3 class="text-center">Order Details</h3>
        <table class="table table-bordered table-condensed table-striped table-responsive">
            <tr>
                <td>Sub Total</td>
                <td><?=  money($txn['sub_total']);?></td>
            </tr>
            
            <tr>
                <td>Tax</td>
                <td><?=  money($txn['tax']);?></td>
            </tr>
            
            <tr>
                <td>Grand Total</td>
                <td><?=  money($txn['grand_total']);?></td>
            </tr>
            
            <tr>
                <td>Order Date</td>
                <td><?=  pretty_date($txn['txn_date']);?></td>
            </tr>
           
        </table>
    </div>
    <div class="col-md-6">
        <h3 class="text-center">Shipping Address</h3>
        <address class="text-center">
            <?=$txn['full_name'];?><br>
            <?=$txn['street'];?><br>
            <?=($txn['street2'] != '')?$txn['street2'].'<br>':'';?>
            <?=$txn['city'].' '.$txn['state'].' '.$txn['zip_code'].'<br>';?>
            <?=$txn['country'];?>
        </address>
    </div>
</div>

<div class="pull-right">
    <a href="index.php" class="btn btn-danger"><span class="glyphicon glyphicon-backward"></span>&nbsp;Cancel</a>
    <a href="orders.php?complete=1&cart_id=<?=$cart_id;?>" class="btn btn-primary">Complete Order</a>
</div>
<?php include 'inc/footer.php';?>