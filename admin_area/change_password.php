<link rel="stylesheet" href="style.css">
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
$hashed = $user_data['password'];
$old_password = ((isset($_POST['old_password']))?sanitize($_POST['old_password']):'');
$old_password = trim($old_password);

$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password);

$confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
$confirm = trim($confirm);

$new_hashed = password_hash($password, PASSWORD_DEFAULT);
$user_id = $user_data['id'];
$errors = array();
?>
<div id="login-form">
    <div>
        <?php
           if($_POST){
               //form validation
               if (empty($_POST['old_password']) || empty($_POST['password']) || empty($_POST['confirm'])) {
                   $errors[] = 'You must fill out all fields';
               }
           
               
               //password more then 6 character
               if(strlen($password) < 6){
                   $errors[] = 'Password must be at least 6 Characters';
               }
               
               //if new password match confirm
               if($password != $confirm){
                   $errors[] = 'The new password and Confirm new password does not Match';
               }
               
               //verify old password with our database
               if(!password_verify($old_password, $hashed)){
                   $errors[] = 'Your old password does not match our record.';
               }
               //check for errors
               if(!empty($errors)){
                   echo display_error($errors);
               }  else {
                   //change password
                   $db->query("update user set password='$new_hashed' where id='$user_id'");
                   $_SESSION['success_flash'] = 'your password has been updated';
                   header('Location: index.php');
               }
           }
        ?>
        
    </div>
    <h2 class="text-center">Change Password</h2>
    <form action="change_password.php" method="post">
        <div class="form-group">
            <label for="old_password">Old Password: </label>
            <input type="password" name="old_password" id="old_password" class="form-control" value="<?=$old_password;?>">
        </div>
        
        <div class="form-group">
            <label for="password">New Password: </label>
            <input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
        </div>
        
        <div class="form-group">
            <label for="confirm">Confirm Password: </label>
            <input type="password" name="confirm" id="confirm" class="form-control" value="<?=$confirm;?>">
        </div>
        
        <div class="form-group">
            <a href="index.php" class="btn btn-default">Cancel</a>
            <input type="submit" name="log_submit" value="Login" class="btn btn-success">
        </div>
    </form>
</div>

