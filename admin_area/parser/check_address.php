<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once $_SERVER['DOCUMENT_ROOT'].'/fashion_store/core/init.php';

$name = sanitize($_POST['full_name']);
$email = sanitize($_POST['email']);
$street = sanitize($_POST['street']);
$street2 = sanitize($_POST['street2']);
$city = sanitize($_POST['city']);
$state = sanitize($_POST['state']);
$zip_code = sanitize($_POST['zip_code']);
$country = sanitize($_POST['country']);

$errors = array();
$required = array(
        'full_name' => 'Full Name',
        'email' => 'Email',
        'street' => 'Street',
        'street2' => 'street2',
        'city' => 'city',
        'state' => 'state',
        'zip_code' => 'Zip Code',
        'country' => 'Country',
);

//check if all required fileds are fill
foreach ($required as $f => $d){
    if (empty($_POST[$f]) || $_POST[$f] == ''){
        $errors[] = $d.'is Required';
    }
}

//check if valid email address
if (!filter_var($email,FILTER_VALIDATE_EMAIL)){
    $errors[] = 'Please Enter a valid Email';
}

if (!empty($errors)){
    echo display_error($errors);
}  else {
    echo 'passed';    
}