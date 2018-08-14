<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once $_SERVER['DOCUMENT_ROOT'].'/fashion_store/core/init.php';
if (!is_logged_in()) {
    login_error_redirect();
}
include 'inc/head.php';
include 'inc/navigation.php';

$sql = "select * from products where deleted=1";
$result = $db->query($sql);

//archive product process
if(isset($_GET['archive'])){
    $id = sanitize($_GET['archive']);
    $reset = "update products set deleted=0 AND featured=1 where id='$id'";
    $result = $db->query($reset);
    header('Location: archive.php');
}
?>
<h2 class="text-center">Product</h2><hr>

<table class="table table-bordered table-striped table-responsive">
    <thead>
    <th></th>
    <th>Product</th>
    <th>Price</th>
    <th>Category</th>
    <th>Price</th>
    <th>Sold</th>
    </thead>
    <tbody>
        <?php while ($product = mysqli_fetch_assoc($result)):
            $childId = $product['categories'];
            $catsql = "select * from category where category_id ='$childId'";
            $cresult = $db->query($catsql);
            $child = mysqli_fetch_assoc($cresult);
            $parentId = $child['parent'];
            $psql = "select * from category where category_id ='$parentId'";
            $presult = $db->query($psql);
            $parent = mysqli_fetch_assoc($presult);
            $category = $parent['category'].'-'.$child['category'];
            ?>
        <tr>
            <td><a href="archive.php?archive=<?=$product['id'];?>"><span class="glyphicon glyphicon-refresh"></span></a></td>
            <td><?=$product['title'];?></td>
            <td><?=  money($product['price']);?></td>
            <td><?=$category;?></td>
            <td>
                <?=$product['price'];?>
            </td>
            <td>0</td>
        </tr>
        <?php endwhile;?>
    </tbody>
</table>
<?php include 'inc/footer.php';?>