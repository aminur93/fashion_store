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
if(isset($_GET['cat'])){
    $cat_id = sanitize($_GET['cat']);
    
}  else {
     $cat_id = '';    
}

$sql = "select * from products where categories='$cat_id'";
$productQ = $db->query($sql);

$category = get_category($cat_id);

 ?>
<!--product content-->
<div class="col-md-8">
    <div class="row">
        <h2 class="text-center"><?=$category['parent'].' '.$category['child'];?></h2>
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