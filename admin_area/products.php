<link rel="stylesheet" href="style.css">
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * project Author is Aminur Rashid.
 */
ob_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/fashion_store/core/init.php';
if (!is_logged_in()) {
    login_error_redirect();
}
include 'inc/head.php';
include 'inc/navigation.php';

//delete products
if (isset($_GET['delete'])){
    $id = sanitize($_GET['delete']);
    $db->query("update products set deleted=1,featured=0 where id='$id'");
    header('location: products.php');
}

$dbPath='';

//add product form
if(isset($_GET['add'])|| isset($_GET['edit'])){
    $brandQuery = $db->query("select * from brand ORDER BY brand");
    $parentQuery = $db->query("select * from category where parent=0 ORDER BY category");
    //edit products
    $title = ((isset($_POST['title']) && $_POST['title'] !='')?sanitize($_POST['title']):'');
    $brand = ((isset($_POST['brand']) && !empty($_POST['brand']) !='')?sanitize($_POST['brand']):'');
    $parent = ((isset($_POST['parent']) && !empty($_POST['parent']) !='')?sanitize($_POST['parent']):'');
    $category = ((isset($_POST['child']) && !empty($_POST['child']) !='')?sanitize($_POST['child']):'');
    $price = ((isset($_POST['price']) && $_POST['price'] !='')?sanitize($_POST['price']):'');
    $list_price = ((isset($_POST['list_price']) && $_POST['list_price'] !='')?sanitize($_POST['list_price']):'');
    $sizes = ((isset($_POST['sizes']) && $_POST['sizes'] !='')?sanitize($_POST['sizes']):'');
    $sizes = rtrim($sizes,',');
    $product_keyword = ((isset($_POST['product_keyword']) && $_POST['product_keyword'] !='')?sanitize($_POST['product_keyword']):'');
    $description = ((isset($_POST['description']) && $_POST['description'] !='')?sanitize($_POST['description']):'');
    $saved_image = '';
    if(isset($_GET['edit'])){
        $edit_id = (int)$_GET['edit'];
        $productResult = $db->query("select * from products where id='$edit_id'");
        $product = mysqli_fetch_assoc($productResult);
        if (isset($_GET['delete_image'])){
            $imgi = (int)$_GET['imgi']-1;
            $imagess = explode(',', $product['image']);
            $image_url = $_SERVER['DOCUMENT_ROOT'].$imagess[$imgi];
            unlink($image_url);
            unset($imagess[$imgi]);
            $imageString = implode(',', $imagess);
            $db->query("update products set image='{$imageString}' where id='$edit_id'");
            header('Location: products.php?edit='.$edit_id);
        }
        $category = ((isset($_POST['child']) && !empty($_POST['child']))?sanitize($_POST['child']):$product['categories']);
        $title = ((isset($_POST['title']) && !empty($_POST['title']))?sanitize($_POST['title']):$product['title']);
        $brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):$product['brand']);
        $price = ((isset($_POST['price']) && !empty($_POST['price']))?sanitize($_POST['price']):$product['price']);
        $list_price = ((isset($_POST['list_price']) && !empty($_POST['list_price']))?sanitize($_POST['list_price']):$product['list_price']);
        $sizes = ((isset($_POST['sizes']) && !empty($_POST['sizes']))?sanitize($_POST['sizes']):$product['sizes']);
         $sizes = rtrim($sizes,',');
        $product_keyword = ((isset($_POST['product_keyword']) && !empty($_POST['product_keyword']))?sanitize($_POST['product_keyword']):$product['product_keyword']);
        $description = ((isset($_POST['description']))?sanitize($_POST['description']):$product['description']);
        $parentQ= $db->query("select * from category where category_id ='$category'");
        $parentResult = mysqli_fetch_assoc($parentQ);
        $parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):$parentResult['parent']);
        $saved_image = (($product['image'] !='')?$product['image']:'');
        $dbPath = $saved_image;
    }
    //sizes for edit
     if(!empty($sizes)){
            $sizeString = sanitize($sizes);
            $sizeString = rtrim($sizeString,',');
            $sizesArray = explode(',', $sizeString);
            $sArray = array();
            $qArray = array();
            $tArray = array();
            foreach ($sizesArray as $ss){
                $s = explode(':', $ss);
                $sArray[] = $s[0];
                $qArray[] = $s[1];
                $tArray[] = $s[2];
            }
        }else{$sizeString = array();}
        
    if ($_POST){
        $errors = array();
        $uploadName='';
        $required = array('title','brand','price','parent','child','sizes');
        $allowed = array('png','jpg','jpeg','gif');
        $tmpLoc = array();
        $uploadPath = array();
        foreach ($required as $filed) {
            if ($_POST[$filed] == '') {
                $errors[] = 'All fields with and Astrisk are required'; 
                break;
            }
        }
        $imageCount = count($_FILES['images']['name']);
        if ($imageCount>0) {
            for ($i=0;$i<$imageCount;$i++){
              $name = $_FILES['images']['name'][$i];
              $nameArray = explode('.', $name);
              $fileName = $nameArray[0];
              $fileEx = $nameArray[1];
              $mime = explode('/', $_FILES['images']['type'][$i]);
              $mimeType = $mime[0];
              $mimeEx = $mime[1];
              $tmpLoc[] = $_FILES['images']['tmp_name'][$i];
              $filesize = $_FILES['images']['size'][$i];              
              $uploadName = md5(microtime().$i).'.'.$fileEx;
              $uploadPath[] = BASEURL.'/images/'.$uploadName; 
              if ($i != 0) {
                  $dbPath .= ',';
              }
              $dbPath .= '/fashion_store/images/'.$uploadName;
              if($mimeType != 'image'){
                 $errors[] = 'The file must be and Image';
               }
              if (!in_array($fileEx,$allowed)){
                 $errors[] = 'The file extenson must be an png, jpg, jpeg or gif';
               }
              if ($filesize>15000000){
                 $errors[] = 'The file size must be under 15mb';
               }
              if ($fileEx != $mimeEx && ($mimeEx == 'jpeg' && $fileEx != 'jpg')){
                 $errors[] = 'The file Extenson does not match!';
               }
                
            }
        }
        if (!empty($errors)){
            echo display_error($errors);
        }  else {
             //first upload file then insert database
              if($imageCount>0){
                  for ($i=0;$i<$imageCount;$i++){
                     move_uploaded_file($tmpLoc[$i],$uploadPath[$i]);
                  }
              }
             $insertsql = "insert into products(`title`,`price`,`list_price`,`brand`,`categories`,`sizes`,`image`,`description`,`product_keyword`) 
               values('$title','$price','$list_price','$brand','$category','$sizes','$dbPath','$description','$product_keyword')";
             if(isset($_GET['edit'])){
                 $insertsql = "update products set 
                 title='$title',price='$price',list_price='$list_price',
                 brand='$brand',categories='$category',sizes='$sizes',
                 image='$dbPath',description='$description',product_keyword='$product_keyword' where id='$edit_id'";
             }
                $db->query($insertsql);
                header("Location: products.php");
        }
    }
    
?>
<h2 class="text-center"><?=((isset($_GET['edit']))?'Edit':'Add A New');?> Product</h2>
<form action="products.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1');?>" method="post" enctype="multipart/form-data">
    <div class="form-group col-md-3">
        <label for="title">Title: </label>
        <input type="text" name="title" class="form-control" id="title" value="<?=$title;?>">
    </div><!--end title input-->
    
    <div class="form-group col-md-3">
        <label for="brand">Brand: </label>
        <select class="form-control" id="brand" name="brand">
            <option value=""<?=(($brand == '')?' selected':'');?>></option>
            <?php while ($b = mysqli_fetch_assoc($brandQuery)):?>
            <option value="<?=$b['id'];?>"<?=(($brand == $b['id'])?' selected':'');?>><?=$b['brand'];?></option>
            <?php endwhile;?>
        </select>
    </div><!--end brand input-->
    
    <div class="form-group col-md-3">
        <label for="parent">Parent Category: </label>
        <select class="form-control" id="parent" name="parent">
            <option value=""<?=(($parent == '')?' selected':'');?>></option>
            <?php while ($p = mysqli_fetch_array($parentQuery)):?>
            <option value="<?=$p['category_id'];?>"<?=(($parent == $p['category_id'])?' selected':'');?>><?=$p['category'];?></option>
            <?php endwhile;?>
        </select>
    </div><!--end parent input-->
    
    <div class="form-group col-md-3">
        <label for="child">Child: </label>
        <select class="form-control" id="child" name="child">
        </select>
    </div><!--end child input-->
    
    <div class="form-group col-md-3">
        <label for="price">Price: </label>
        <input type="text" name="price" class="form-control" id="price" value="<?=$price;?>">
    </div>
    
    <div class="form-group col-md-3">
        <label for="list_price">List Price: </label>
        <input type="text" name="list_price" class="form-control" id="list_price" value="<?=$list_price;?>">
    </div>
    
    <div class="from-group col-md-3">
        <label>Quantity & Sizes: </label>
        <button class="btn btn-default form-control" onclick="jQuery('#sizesModal').modal('toggle');return false;">Quantity & Sizes</button>
    </div>
    
    <div class="form-group col-md-3">
        <label for="sizes">Sizes & Qty Preview</label>
        <input type="text" name="sizes" id="sizes" class="form-control" value="<?=$sizes;?>">
    </div>
    
    <div class="form-group col-md-6">
        <?php if ($saved_image !=''):?>
        <?php
         $imgi = 1;
         $imagess = explode(',',$saved_image);
        ?>
        <?php foreach ($imagess as $images):?>
        <div class="saved-image col-md-4">
            <img src="<?=$images;?>">
            <a href="products.php?delete_image=1&edit=<?=$edit_id;?>&imgi=<?=$imgi;?>" class="text-danger">Delete Image</a>
        </div>
        <?php 
         $imgi++;
        endforeach;?>
        <?php else:?>
        <label for="image">Image: </label>
        <input type="file" class="form-control" name="images[]" id="image" multiple="">
        <?php endif;?>
    </div>
    
    <div class="form-group col-md-6">
        <label for="product_keyword">Product Keyword: </label>
        <input type="text" class="form-control" name="product_keyword" id="product_keyword" value="<?=$product_keyword;?>">
    </div>
    
    <div class="form-group col-md-6">
        <label for="description">Description: </label>
        <textarea name="description" id="description" class="form-control" rows="6"><?=$description;?></textarea>
    </div>
    
    <div class="form-group pull-right">
       <a href="products.php" class="btn btn-default">Cancel</a>
       <input type="submit" name="add_submit" value="<?=((isset($_GET['edit']))?'Edit':'Add');?> Product" class=" btn btn-success">
    </div>
    
</form>

<!-- Modal -->
<div class="modal fade" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="sizesModalLabel">Size & Quantity</h4>
        </div>
        <div class="modal-body">
            <div class="container-fluid">
          <?php for ($i=1;$i <= 12;$i++):?>
            <div class="form-group col-sm-2">
                <label for="size<?=$i;?>">Size: </label>
                <input type="text" name="size<?=$i;?>" id="size<?=$i;?>" value="<?=((!empty($sArray[$i-1]))?$sArray[$i-1]:'');?>" class="form-control">
            </div>
            
            <div class="form-group col-sm-2">
                <label for="qty<?=$i;?>">Quantity: </label>
                <input type="number" name="qty<?=$i;?>" id="qty<?=$i;?>" value="<?=((!empty($qArray[$i-1]))?$qArray[$i-1]:'');?>" min="0" class="form-control">
            </div>
                
            <div class="form-group col-sm-2">
                <label for="threshold<?=$i;?>">Threshold: </label>
                <input type="number" name="threshold<?=$i;?>" id="threshold<?=$i;?>" value="<?=((!empty($tArray[$i-1]))?$tArray[$i-1]:'');?>" min="0" class="form-control">
            </div>
                
            <?php endfor;?>
            </div>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="updateSizes();jQuery('#sizesModal').modal('toggle');return false;">Save changes</button>
      </div>
    </div>
  </div>
</div>

<?php 
}
else {
    
//show products from database
$sql ="select * from products where deleted =0";
$result = $db->query($sql);

//featured product process
if (isset($_GET['featured'])) {
    $id = (int)$_GET['id'];
    $featured = (int)$_GET['featured'];
    $featuredsql = "update products set featured='$featured' where id='$id'";
    $db->query($featuredsql);
    header('location: products.php');
}
?>
<h2 class="text-center">Products</h2>
<a href="products.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Add Products</a><div class="clearfix"></div>
<hr>
<table class="table table-bordered table-striped table-responsive">
    <thead>
    <th></th>
    <th>Product</th>
    <th>Price</th>
    <th>Category</th>
    <th>Featured</th>
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
            <td>
                <a href="products.php?edit=<?= $product['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-edit"></span></a>
                <a href="products.php?delete=<?= $product['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-circle"></span></a>
            </td>
            <td><?=$product['title'];?></td>
            <td><?=  money($product['price']);?></td>
            <td><?= $category;?></td>
            <td>
                <a href="products.php?featured=<?=(($product['featured'] == 0)?'1':'0');?>&id=<?=$product['id'];?>" class="btn btn-xs btn-default">
                <span class="glyphicon glyphicon-<?=(($product['featured']==1)?'minus':'plus');?>"></span>
                </a>&nbsp<?=(($product['featured']==1)?'featured product':'');?></td>
            <td>0</td>
        </tr>
        <?php endwhile;?>
    </tbody>
</table>
<?php } include 'inc/footer.php';?>

<script>
    jQuery('document').ready(function(){
       get_child_options('<?=$category;?>'); 
    });
    </script>