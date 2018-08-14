<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'core/init.php';
include 'inc/head.php';
include 'inc/headerpartial.php';
include 'inc/navigation.php';

//show cart items form databse
if($cart_id != ''){
    $cartQ = $db->query("select * from cart where id='{$cart_id}'");
    $result = mysqli_fetch_assoc($cartQ);
    $items = json_decode($result['items'],true);
    $i = 1;
    $sub_total =0;
    $items_count = 0;    
}
?>
<div class="col-md-12">
    <div class="row">
        <h2 class="text-center">My Shopping Cart</h2><hr>
       <?php if ($cart_id == ''):?>
        <div class="bg-danger">
            <p class="text-center text-danger">Your Shopping Cart is Empty</p>
        </div>
        <?php else:?>
        <table class="table table-bordered table-condensed table-striped table-responsive">
            <thead>
            <th>#</th>
            <th>Items</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Size</th>
            <th>Sub Total</th>
            </thead>
            <tbody>
                <?php
                    foreach ($items as $item){
                         $product_id = $item['id'];
                         $productQ = $db->query("select * from products where id='{$product_id}'");
                         $product = mysqli_fetch_assoc($productQ);
                         $sArray = explode(',', $product['sizes']);
                         
                         foreach ($sArray as $sizeString){
                             $s = explode(':', $sizeString);
                             if ($s[0] == $item['size']) {
                                 $available = $s[1];
                             }
                         }
                         ?>
                <tr>
                    <td><?=$i;?></td>
                    <td><?=$product['title']?></td>
                    <td><?=  money($product['price'])?></td>
                    <td>
                        <button class="btn btn-default btn-xs" onclick="update_cart('removeone','<?=$product['id'];?>','<?=$item['size'];?>');">-</button>
                        <?=$item['quantity'];?>
                        <?php if ($item['quantity'] < $available):?>
                        <button class="btn btn-default btn-xs" onclick="update_cart('addone','<?=$product['id'];?>','<?=$item['size'];?>');">+</button>
                        <?php else:?>
                        <span class="text-danger">Max</span>
                        <?php endif;?>
                    </td>
                    <td><?=$item['size'];?></td>
                    <td><?=  money($item['quantity'] * $product['price']);?></td>
                </tr>
                    <?php 
                    $i++;
                    $items_count += $item['quantity'];
                    $sub_total += ($product['price'] * $item['quantity']); 
                    } 
                    $tax = TAXRATE * $sub_total;
                    $tax = number_format($tax,2);
                    $grand_total = $tax + $sub_total;
                    ?>
            </tbody>
        </table>
        
        <table class="table table-bordered table-condensed table-responsive table-striped text-right">
            <legend class="text-center">Totals</legend>
            <thead class="totals-table-header">
            <th class="text-center">Total Items</th>
            <th class="text-center">Sub Total</th>
            <th class="text-center">Tax</th>
            <th class="text-center">Grand Total</th>
            </thead>
            <tbody>
                <tr>
                    <td><?=$items_count?></td>
                    <td><?=  money($sub_total);?></td>
                    <td><?=  money($tax)?></td>
                    <td class="bg-success"><?=  money($grand_total);?></td>
                </tr>
            </tbody>
        </table>
        
         <!-- Checkout Button trigger modal -->
        <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#checkoutModal">
            <span class="glyphicon glyphicon-check"></span>&nbsp;Check Out
        </button>
         <a href="index.php" class="btn btn-success pull-right" style="margin-right: 10px;"><span class="glyphicon glyphicon-backward"></span>&nbsp;Cancel</a>
        <!-- Modal -->
        <div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="checkoutModalLabel">Shipping Address</h4>
              </div>
              <div class="modal-body">
                  <div class="row">
                  <form action="thankYou.php" method="post" id="payment-form"><!--modal form start-->
                      <span class="bg-danger" id="payment-errors"></span>
                      
                      <!--hidden input start-->
                      <input type="hidden" name="tax" value="<?=$tax;?>">
                      <input type="hidden" name="sub_total" value="<?=$sub_total;?>">
                      <input type="hidden" name="grand_total" value="<?=$grand_total;?>">
                      <input type="hidden" name="cart_id" value="<?=$cart_id;?>"> 
                      <input type="hidden" name="description" value="<?=$items_count.' item'.(($items_count>1)?'$':'').' from fshion store';?>">
                      <!--End hidden input-->
                      
                      <div id="step1" style="display: block;">
                          <div class="form-group col-md-6">
                              <label for="full_name">Full Name: </label>
                              <input type="text" name="full_name" id="full_name" class="form-control">
                          </div>
                          
                           <div class="form-group col-md-6">
                              <label for="email">Email: </label>
                              <input type="text" name="email" id="email" class="form-control">
                          </div>
                          
                           <div class="form-group col-md-6">
                              <label for="street">Street Address: </label>
                              <input type="text" name="street" id="street" class="form-control" data-stripe="address_line1">
                          </div>
                          
                          <div class="form-group col-md-6">
                              <label for="street2">Street Address 2: </label>
                              <input type="text" name="street2" id="street2" class="form-control" data-stripe="address_line2">
                          </div>
                          
                           <div class="form-group col-md-6">
                              <label for="city">City: </label>
                              <input type="text" name="city" id="city" class="form-control" data-stripe="address_city">
                          </div>
                          
                           <div class="form-group col-md-6">
                              <label for="state">State: </label>
                              <input type="text" name="state" id="state" class="form-control" data-stripe="address_state">
                          </div>
                          
                           <div class="form-group col-md-6">
                              <label for="zip_code">Zip Code: </label>
                              <input type="text" name="zip_code" id="zip_code" class="form-control" data-stripe="address_zip">
                          </div>
                          
                           <div class="form-group col-md-6">
                              <label for="country">Country: </label>
                              <input type="text" name="country" id="country" class="form-control" data-stripe="address_country">
                          </div>
                          
                      </div>
                      
                      <div id="step2" style="display:none;">
                          <div class="form-group col-md-3">
                              <label for="name">Name on Card: </label>
                              <input type="text" id="name" class="form-control" data-stripe="name">
                          </div>
                          
                          <div class="form-group col-md-3">
                              <label for="number">Card Number: </label>
                              <input type="text" id="number" class="form-control" data-stripe="number">
                          </div>
                          
                          <div class="form-group col-md-3">
                              <label for="cvc">CVC: </label>
                              <input type="text" id="cvc" class="form-control" data-stripe="cvc">
                          </div>
                          
                          <div class="form-group col-md-3">
                              <label for="exp_month">Expire Month: </label>
                              <select id="exp_month" class="form-control" data-stripe="exp_month">
                                  <option value=""></option>
                                  <?php for($i=1;$i<13;$i++):?>
                                  <option value="<?=$i;?>"><?=$i;?></option>
                                  <?php endfor;?>
                              </select>
                          </div>
                          
                          <div class="form-group col-md-3">
                              <label for="exp_year">Expire Year: </label>
                              <select id="exp_year" class="form-control" data-stripe="exp_year">
                                  <option value=""></option>
                                  <?php $yr = date("Y")?>
                                  <?php for ($i=0;$i<11;$i++):?>
                                  <option value="<?=$yr + $i;?>"><?=$yr+$i;?></option>
                                  <?php endfor;?>
                              </select>
                          </div>
                      </div>
                  
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="check_address();" id="next_button">Next&nbsp;<span class="glyphicon glyphicon-forward"></span></button>
                <button type="button" class="btn btn-primary" onclick="back_address();" id="back_button" style="display: none;"><span class="glyphicon glyphicon-backward"></span>&nbsp;Back</button>
                <button type="submit" class="btn btn-primary" id="checkout_button" style="display: none;">Checkout</button>
                </form><!--end modal form-->
              </div>
            </div>
          </div>
        </div>
        <?php endif;?>
    </div>
</div>

<!--this is check address-->
<script>
    function back_address(){
        jQuery('#payment-error').html("");
        jQuery('#step1').css("display","block");
        jQuery('#step2').css("display","none");
        jQuery('#next_button').css("display","inline-block");
        jQuery('#back_button').css("display","none");
        jQuery('#checkout_button').css("display","none");
        jQuery('#checkoutModalLabel').html("Shipping Address");
    }
    
    function check_address(){
        var data = {
            'full_name' : jQuery('#full_name').val(),
            'email' : jQuery('#email').val(),
            'street' : jQuery('#street').val(),
            'street2' : jQuery('#street2').val(),
            'city' : jQuery('#city').val(),
            'state' : jQuery('#state').val(),
            'zip_code' : jQuery('#zip_code').val(),
            'country' : jQuery('#country').val(),
    };
    jQuery.ajax({
        url  : '/fashion_store/admin_area/parser/check_address.php',
        method : 'post',
        data : data,
        success : function(data){
            if(data != 'passed'){
                jQuery('#payment-error').html(data);
               
            }
            if(data == 'passed'){
                jQuery('#payment-error').html("");
                jQuery('#step1').css("display","none");
                jQuery('#step2').css("display","block");
                jQuery('#next_button').css("display","none");
                jQuery('#back_button').css("display","inline-block");
                jQuery('#checkout_button').css("display","inline-block");
                jQuery('#checkoutModalLabel').html("Enter Your Card Details");
            }
        },
        error : function(){alert("Something Went Wrong!");},
    });
 }
  Stripe.setPublishableKey('<?=STRIPE_PUBLIC;?>');
  
  function stripeResponseHandler(status, response) {
  // Grab the form:
  var $form = $('#payment-form');

  if (response.error) { // Problem!

    // Show the errors on the form:
    $form.find('#payment-errors').text(response.error.message);
    $form.find('button').prop('disabled', false); // Re-enable submission

  } else { // Token was created!

    // Get the token ID:
    var token = response.id;

    // Insert the token ID into the form so it gets submitted to the server:
    $form.append($('<input type="hidden" name="stripeToken">').val(token));

    // Submit the form:
    $form.get(0).submit();
  }
};
  
  $(function() {
  var $form = $('#payment-form');
  $form.submit(function(event) {
    // Disable the submit button to prevent repeated clicks:
    $form.find('button').prop('disabled', true);

    // Request a token from Stripe:
    Stripe.card.createToken($form, stripeResponseHandler);

    // Prevent the form from being submitted:
    return false;
  });
});
</script>

<?php include 'inc/footer.php'; ?>
