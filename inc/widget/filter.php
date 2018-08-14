<?php
$cat_id = ((isset($_REQUEST['cat_id']))?sanitize($_POST['cat_id']):'');
$price_sort = ((isset($_REQUEST['price_sort']))?sanitize($_REQUEST['price_sort']):'');
$min_price = ((isset($_REQUEST['min_price']))?  sanitize($_REQUEST['min_price']):'');
$max_price = ((isset($_REQUEST['max_price']))?  sanitize($_REQUEST['max_price']):'');
$b = ((isset($_REQUEST['brand']))?sanitize($_REQUEST['brand']):'');
$brandQ = $db->query("select * from brand ORDER BY brand");
?>
<h3 class="text-center text-danger">Search By</h3>
<h4 class="text-center text-success">Price</h4>
<form action="search.php" method="post">
    <input type="hidden" name="cat" value="<?=$cat_id;?>">
    <input type="hidden" name="price_sort" value="0">
    <input type="radio" name="price_sort" value="low"<?=(($price_sort == 'low')?'checked':'');?>>Low to High<br>
    <input type="radio" name="price_sort" value="high"<?=(($price_sort == 'low')?'checked':'');?>>High to Low<br><br>
    <input type="text" name="min_price" placeholder="MIN $" class="price-range" value="<?=$min_price;?>">
    To
    <input type="text" name="max_price" placeholder="MAX $" class="price-range" value="<?=$max_price;?>"><br><br>
    
    <h4 class="text-center text-success">Brand</h4>
    <input type="radio" name="brand" value=""<?=(($b == '')?'checked':'');?>>
    All<br>
    <?php while ($brand = mysqli_fetch_assoc($brandQ)):?>
    <input type="radio" name="brand" value="<?=$brand['id'];?>"<?=(($b == $brand['id'])?'checked':'');?>><?=$brand['brand'];?><br>
    <?php endwhile;?>
    <br>
    <input type="submit" class="btn btn-xs btn-primary" value="search">
</form>

