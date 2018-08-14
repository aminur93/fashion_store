<h3 class="text-center text-success">Shopping Cart</h3>
<div>
    <?php if(empty($cart_id)):?>
    <p>Your Shopping is Empty</p>
    <?php else:
        $cartQ = $db->query("select * from cart where id='{$cart_id}'");
        $results = mysqli_fetch_assoc($cartQ);
        $items = json_decode($results['items'],true);
        $sub_total=0;
        
        ?>
    <table class="table table-condensed table-responsive" id="cart_widget">
        <tbody>
            <?php foreach ($items as $item):
                $productQ = $db->query("select * from products where id='{$item['id']}'");
                $product = mysqli_fetch_assoc($productQ);
                ?>
            <tr>
                <td><?=$item['quantity'];?></td>
                <td><?=  substr($product['title'],0,13);?></td>
                <td><?=  money($item['quantity']* $product['price']);?></td>
            </tr>
            <?php 
            $sub_total += ($item['quantity'] * $product['price']); 
            endforeach;?>
            <tr>
                <td></td>
                <td>Sub Total</td>
                <td><?=  money($sub_total);?></td>
            </tr>
        </tbody>
    </table>
    <br>
    <a href="carts.php" class="btn btn-xs btn-primary pull-right sally">view cart</a>
    <div class="clearfix"></div>
    <?php endif;?>
</div>

