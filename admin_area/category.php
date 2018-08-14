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

//show category from databse
$sql = "select * from category where parent = 0";
$result = $db->query($sql);
$errors = array();
$category = '';
$post_parent = '';
//Edit category
if (isset($_GET['edit']) && !empty($_GET['edit'])){
    $edit_id = (int)$_GET['edit'];
    $edit_id = sanitize($edit_id);
    $edit_sql = "select * from category where category_id ='$edit_id'";
    $edit_result = $db->query($edit_sql);
    $edit_category = mysqli_fetch_assoc($edit_result);
}

//delete category
if(isset($_GET['delete']) && !empty($_GET['delete'])){
    $delete_id = (int)$_GET['delete'];
    $delete_id = sanitize($delete_id);
    $sql = "select * from category where category_id ='$delete_id'";
    $result = $db->query($sql);
    $category = mysqli_fetch_assoc($result);
    if ($category['parent'] == 0) {
        $sql = "delete from category where parent ='$delete_id'";
        $db->query($sql);
    }
    $dsql = "delete from category where category_id ='$delete_id'";
    $db->query($dsql);
    header('location: category.php');
}

//process form
if(isset($_POST) && !empty($_POST)){
    $post_parent = sanitize($_POST['parent']);
    $category = sanitize($_POST['category']);
    $sqlform = "select * from category where category='$category' AND parent='$post_parent'";
    if (isset($_GET['edit'])){
        $category_id = $edit_category['category_id'];
        $sqlform = "select * from category where category='$category' AND parent='$post_parent' AND category_id !='$category_id'";
        
        
    }
    $fresult = $db->query($sqlform);
    $count = mysqli_num_rows($fresult);
    
    //if category is blank
    if ($category == '') {
        $errors[] .='The category can not be Blank!';
    }
    
    //if exist in database
    if($count>0){
        $errors[] .=$category.'already exist in database! please chose new category';
    }
    
    //Display error or Update database
    if (!empty($errors)) {
        //display error
        $display = display_error($errors);?>
        <script>
            jQuery('document').ready(function(){
               jQuery('#errors').html('<?=$display;?>'); 
            });
        </script>
   <?php }else {
        //update databse
       $updatesql = "insert into category(category,parent)values('$category','$post_parent')";
       if(isset($_GET['edit'])){
           $updatesql = "update category set category='$category',parent='$post_parent' where category_id='$edit_id'";
           
       }
       $db->query($updatesql);
       header('location: category.php');
    }
}

//show catgeory for edit
$category_value = '';
$parent_value = 0;
if(isset($_GET['edit'])){
    $category_value = $edit_category['category'];
    $parent_value = $edit_category['parent'];
}  else {
    if (isset($_POST)){
        $category_value = $category;
        $parent_value = $post_parent;
    }
}
?>
<h2 class="text-center">Category</h2><hr>
<div class="row">
    <!--form-->
    <div class="col-md-6">
        <legend class="text-center"><?=((isset($_GET['edit']))?'Edit':'Add A')?> Category</legend>
        <div id="errors"></div>
        <form class="form" action="category.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'')?>" method="post">
            <div class="form-group">
                <label for="parent">Parent</label>
                <select class="form-control" name="parent" id="parent">
                    <option value="0"<?=(($parent_value == 0)?' selected="selected"':'');?>>parent</option>
                    <?php while ($parent = mysqli_fetch_assoc($result)):?>
                    <option value="<?=$parent['category_id']?>"<?=(($parent_value == $parent['category_id'])?' selected="selected"':'')?>><?=$parent['category'];?></option>
                    <?php endwhile;?>
                </select>
                
                <div class="from-group">
                    <label for="category">Category</label>
                    <input type="text" name="category" id="category" class="form-control" value="<?= $category_value;?>">
                </div>
                <br>
                <div class="form-group">
                    <?php if (isset($_GET['edit'])):?>
                    <a href="category.php" class="btn btn-default">Cancel</a>
                    <?php endif;?>
                    <input type="submit" name="add_submit" value="<?=((isset($_GET['edit']))?'Edit':'Add');?> category" class="btn btn-success">
                </div>
            </div>
        </form>
    </div>
    
    <!--category table-->
    <div class="col-md-6">
        <table class="table table-bordered table-responsive">
            <thead>
            <th>category</th>
            <th>Parent</th>
            <th></th>
            </thead>
            <tbody>
                <?php 
                    $sql = "select * from category where parent = 0";
                    $result = $db->query($sql);
                    while ($parent = mysqli_fetch_assoc($result)):
                    $parent_id = (int)$parent['category_id'];
                     $sql2 = "select * from category where parent ='$parent_id'";
                     $cresult = $db->query($sql2);
                    ?>
                <tr class="bg-primary">
                    <td><?= $parent['category'];?></td>
                    <td>parent</td>
                    <td>
                        <a href="category.php?edit=<?=$parent['category_id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-edit"></span></a>
                        <a href="category.php?delete=<?=$parent['category_id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-circle"></span></a>
                    </td>
                </tr>
                <?php while ($child = mysqli_fetch_assoc($cresult)):?>
                 <tr class="bg-info">
                    <td><?= $child['category'];?></td>
                    <td><?= $parent['category'];?></td>
                    <td>
                        <a href="category.php?edit=<?=$child['category_id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-edit"></span></a>
                        <a href="category.php?delete=<?=$child['category_id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-circle"></span></a>
                    </td>
                </tr>
                <?php endwhile;?>
                <?php endwhile;?>
            </tbody>
        </table>
    </div>
    
</div>
<?php include 'inc/footer.php';?>
