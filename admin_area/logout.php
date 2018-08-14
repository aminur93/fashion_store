<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/fashion_store/core/init.php';
unset($_SESSION['SBuser']);
header('Location: log.php');
