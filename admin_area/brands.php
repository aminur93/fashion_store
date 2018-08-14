<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require '../core/init.php';
if (!is_logged_in()) {
    login_error_redirect();
}
include 'inc/head.php';
include 'inc/navigation.php';

//getting brand from databas
$sql = "select * from brand ORDER BY brand ASC";
$result = $db->query($sql);
$errors = array();

//edit brands
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_id = sanitize($edit_id);
    $sql2 = "select * from brand where id='$edit_id'";
    $edit_result = $db->query($sql2);
    $ebrand = mysqli_fetch_assoc($edit_result);
}

//delete brands
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_id = sanitize($delete_id);
    $sql = "delete from brand where id='$delete_id'";
    $db->query($sql);
    header('location: brands.php');
}

//if and form submitted
if(isset($_POST['add_submit'])){
    $brand = sanitize($_POST['brand']);
    //check if is empty
    if($_POST['brand'] == ''){
        $errors[] .= 'You must enter a brand';
    }
    //check if brand exisr in databse
    $sql = "select * from brand where brand='$brand'";
    if (isset($_GET['edit'])) {
        $sql = "select * from brand where brand='$brand' AND id !='$edit_id'";
    }
    $pbrand = $db->query($sql);
    $count = mysqli_num_rows($pbrand);
    if ($count>0) {
        $errors[] .= $brand.' already exist! please chose another brand!';
    }
    //display error
    if (!empty($errors)){
        echo display_error($errors);
    }  else {
        //add brand to the batabase
        $sql = "insert into brand(brand)values('$brand')";
        //update brands
        if (isset($_GET['edit'])) {
            $sql = "update brand set brand='$brand' where id='$edit_id'";
        }
        $db->query($sql);
        header('location: brands.php');
    }
}
?>
<h3 class="text-center">Brands</h3><hr>
<!--brand form-->
<div class="text-center">
    <form class="form-inline" action="brands.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" method="post">
        <div class="form-group">
            <?php
            $brand_value = '';
            if (isset($_GET['edit'])){
                $brand_value = $ebrand['brand'];
            }  else {
                 if(isset($_POST['brand'])){
                     $brand_value = sanitize($_POST['brand']);
                     
                 }    
            }
            ?>
            <label for="brand"><?=((isset($_GET['edit']))?'Edit':'Add A');?> Brands: </label>
            <input type="text" name="brand" class="form-control" id="brand" value="<?=$brand_value;?>">
            <?php if (isset($_GET['edit'])):?>
            <a href="brands.php" class="btn btn-default">Cancel</a>
            <?php endif;?>
            <input type="submit" name="add_submit" value="<?=((isset($_GET['edit']))?'Edit':'Add');?> Brand" class="btn btn-success">
        </div>
    </form>
</div><hr>

<table class="table table-bordered table-striped table-responsive">
    <thead>
    <th class="text-center">Edith</th><th class="text-center">Brands</th><th class="text-center">Delete</th>
    </thead>
    <tbody>
        <?php while ($brand = mysqli_fetch_assoc($result)):?>
        <tr class="text-center">
            <td><a href="brands.php?edit=<?= $brand['id'];?>" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span></a></td>
            <td><?= $brand['brand'];?></td>
            <td><a href="brands.php?delete=<?= $brand['id'];?>" class="btn btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a></td>
        </tr>
        <?php endwhile;?>
    </tbody>
</table>
<?php
include 'inc/footer.php';
?>
