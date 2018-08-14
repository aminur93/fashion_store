<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'core/init.php';
include 'inc/head.php';
include 'inc/navigation.php';
include 'inc/headerpartial.php';
include 'inc/leftbar.php';

//show category from database
$sql = "select * from products";
$cat_id = (($_POST['cat'] != '')?sanitize($_POST['cat']):'');
if ($cat_id == ''){
    $sql .= ' where deleted=0';
}  else {
    $sql .= " where deleted='{$cat_id}' AND deleted=0";
}
$price_sort = (($_POST['price_sort'] != '')?  sanitize($_POST['price_sort']):'');
$min_price = (($_POST['min_price'] != '')?  sanitize($_POST['min_price']):'');
$max_price = (($_POST['max_price'] != '')?  sanitize($_POST['max_price']):'');
$brand = (($_POST['brand'] != '')?  sanitize($_POST['brand']):'');

if($min_price != ''){
    $sql .= " AND price >= '{$min_price}'";
}

if($max_price != ''){
    $sql .= " AND price <= '{$max_price}'";
}

if($brand != ''){
    $sql .= " AND brand='{$brand}'";
}

if ($price_sort == 'low'){
    $sql .= " ORDER BY price";
}

if($price_sort == 'high'){
    $sql .= " ORDER BY price DESC";
}

$productQ = $db->query($sql);
$category = get_category($cat_id);

 ?>
<!--product content-->
<div class="col-md-8">
    <div class="row">
        <?php if($cat_id != ''):?>
        <h2 class="text-center"><?=$category['parent'].' '.$category['child'];?></h2>
        <?php else:?>
        <h2 class="text-center">Fashion Store</h2>
        <?php endif;?>
        
        <?php while ($product = mysqli_fetch_assoc($productQ)):?>
        <div class="col-md-3">
            <h4 class="text-center"><?= $product['title'];?></h4>
            <?php $imagess = explode(',', $product['image'])?>
            <img src="<?= $imagess[0];?>" class="img-responsive single"/>
            <p class="text-danger">List Price: $<s><?= $product['list_price'];?></s></p>
        <p class="text-success">Price: $<?= $product['price'];?></p>
        <button type="button" class="btn btn-success btn-sally" onclick="detailsmodal(<?= $product['id'];?>)">Details</button>
        </div><!--end col md 3-->
        <?php endwhile; ?>
    </div><!--end row-->
</div><!--end col md 7-->
            
<?php
include 'inc/rightbar.php';
include 'inc/footer.php';
?>

