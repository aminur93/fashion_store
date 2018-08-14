<link rel="stylesheet" href="style.css">
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once $_SERVER['DOCUMENT_ROOT'].'/fashion_store/core/init.php';
include 'inc/head.php';
$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
$email = trim($email);
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password);
$errors = array();

?>
<div id="login-form">
    <div>
        <?php
           if($_POST){
               //form validation
               if (empty($_POST['email']) || empty($_POST['password'])) {
                   $errors[] = 'You must provide email and password';
               }
               
               //validate email
               if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                   $errors[] = 'you must enter valid email';
               }
               
               //password more then 6 character
               if(strlen($password) < 6){
                   $errors[] = 'Password must be at least 6 Characters';
               }
               
               //check if user exist database
               $query = $db->query("select * from users where email='$email'");
               $user = mysqli_fetch_assoc($query);
               $userCount = mysqli_num_rows($query);
               if($userCount<1){
                   $errors[] = 'The email dosent esixt in our database';
               }
               
               if(!password_verify($password, $user['password'])){
                   $errors[] = 'The password did not match our records. Please try again';
               }
               //check for errors
               if(!empty($errors)){
                   echo display_error($errors);
               }  else {
                   //log user in
                   $user_id = $user['id']; 
                   login($user_id);
               }
           }
        ?>
        <style>
            body{
            background-image: url("/fashion_store/images/slide4.jpg");
            background-repeat: no-repeat;
            background-size:cover;
            background-attachment: fixed;
             }
        </style>
    </div>
    <h2 class="text-center">Login</h2>
    <form action="log.php" method="post">
        <div class="form-group">
            <label for="email">Email: </label>
            <input type="text" name="email" id="email" class="form-control" value="<?=$email;?>">
        </div>
        
        <div class="form-group">
            <label for="password">Password: </label>
            <input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
        </div>
        
        <div class="form-group">
            <input type="submit" name="log_submit" value="Login" class="btn btn-success">
        </div>
    </form>
    <p class="text-right"><a href="/fashion_store/index.php" alt="home">Visit Site</a></p>
</div>
