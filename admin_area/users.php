<link rel="stylesheet" href="style.css">
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
if (!has_permission('admin')) {
    permission_error_redirect('index.php');
}
include 'inc/head.php';
include 'inc/navigation.php';

//delete user from database
if(isset($_GET['delete'])){
    $delete_id = sanitize($_GET['delete']);
    $db->query("delete from user where id='$delete_id'");
    $_SESSION['success_flash'] = 'User has been deleted';
    header('Location: users.php');
}

//now add user
if (isset($_GET['add'])) {
    $name = ((isset($_POST['name']))?sanitize($_POST['name']):'');
    $email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
    $password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
    $confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
    $permissions = ((isset($_POST['permissions']))?sanitize($_POST['permissions']):'');
    $errors = array();
    //validation
    if($_POST){
        $emailquery = $db->query("select * from user where email='$email'");
        $emailcount = mysqli_num_rows($emailquery);
        //email exist in database
        if ($emailcount != 0) {
            $errors[] = 'That email exist in our database';
        }
        
        $required = array('name','email','password','confirm','permissions');
        foreach ($required as $f){
            if(empty($_POST[$f])){
                $errors[] = 'You must fill out all fileds';
                break;
            }
        }
        //password length
        if(strlen($password) < 6){
            $errors[] = 'Your password must be 6 character';
        }
        
        //password match with confirm
        if ($password != $confirm) {
            $errors[] = 'Your password does not match.';
        }
        
        //email validation
        if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'You must be enter valid email';
        }
        
        //display error
        if (!empty($errors)) {
            echo display_error($errors);
        }  else {
            //add user to database
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $insertsql = "insert into user(full_name,email,password,permissions) values('$name','$email','$hashed','$permissions')";
            $db->query($insertsql);
            $_SESSION['success_flash'] = 'User Has Been Added';
            header('Location: users.php');
        }
    }
    ?>
<h2 class="text-center">Add New User</h2><hr>
<form class="form" action="users.php?add=1" method="post">
    <div class="form-group col-md-6">
        <label for="name">Full Name: </label>
        <input type="text" name="name" id="name" class="form-control" value="<?=$name;?>">
    </div>
    
    <div class="form-group col-md-6">
        <label for="email">Email: </label>
        <input type="email" name="email" id="email" class="form-control" value="<?=$email;?>">
    </div>
    
    <div class="form-group col-md-6">
        <label for="password">Password: </label>
        <input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
    </div>
    
    <div class="form-group col-md-6">
        <label for="confirm">Confirm: </label>
        <input type="password" name="confirm" id="confirm" class="form-control" value="<?=$confirm;?>">
    </div>
    
    <div class="form-group col-md-6">
        <label for="premissions">Permissions: </label>
        <select name="permissions" id="permissions" class="form-control">
            <option value=""<?=(($permissions == '')?'selected':'')?>></option>
            <option value="editor"<?=(($permissions == 'editor')?'selected':'')?>>Editor</option>
            <option value="admin,editor"<?=(($permissions == 'admin,editor')?'selected':'')?>>Admin</option>
        </select>
    </div>
    
    <div class="form-group col-md-6 text-right" style="margin-top: 20px;">
        <a href="users.php" class="btn btn-default">Cancel</a>
        <input type="submit" value="Add User" class="btn btn-success">
    </div>
</form>
   <?php
}  else {
    


//show users data from database
$sql = "select * from user ORDER BY full_name";
$result = $db->query($sql);
?>
<h2 class="text-center">Users</h2>
<a href="users.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Add User</a>
<hr>
<table class="table table-bordered table-striped table-responsive">
    <thead>
    <th></th>
    <th>Name</th>
    <th>Email</th>
    <th>Join Date</th>
    <th>last Login</th>
    <th>Permissions</th>
    </thead>
    <tbody>
        <?php while ($user = mysqli_fetch_assoc($result)):?>
        <tr>
            <td>
                <?php if($user['id'] != $user_data['id']):?>
                <a href="users.php?delete=<?=$user['id'];?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-remove-sign"></span></a>
                <?php endif;?>
            </td>
            <td><?=$user['full_name'];?></td>
            <td><?=$user['email'];?></td>
            <td><?=pretty_date($user['join_date']);?></td>
            <td><?=(($user['last_login'] == '0000-00-00 00:00:00')?'Never':pretty_date($user['last_login']));?></td>
            <td><?=$user['permissions'];?></td>
        </tr>
        <?php endwhile;?>
    </tbody>
</table>
<?php } include 'inc/footer.php';?>