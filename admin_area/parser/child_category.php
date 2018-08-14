<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * project author is Aminur Rashid
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/fashion_store/core/init.php';
$parentId =(int)$_POST['parentId'];
$selected = sanitize($_POST['selected']);
$childQuery = $db->query("select * from category where parent='$parentId' ORDER BY category");
ob_start();
?>
<option value=""></option>
<?php while ($child = mysqli_fetch_assoc($childQuery)):?>
<option value="<?=$child['category_id'];?>"<?=(($selected == $child['category_id'])?' selected':'');?>><?=$child['category'];?></option>
<?php endwhile;?>
<?php echo ob_get_clean(); ?>