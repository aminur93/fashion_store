<?php
require_once '../core/init.php';
$id = $_POST['id'];
$id = (int)$id;
$sql = "select * from products where id = '$id'";
$result = $db->query($sql);
$product = mysqli_fetch_assoc($result);
$brand_id = $product['brand'];
$sql = "select brand from brand where id ='$brand_id'";
$brand_query = $db->query($sql);
$brand = mysqli_fetch_assoc($brand_query);
$sizestring = $product['sizes'];
$size_array = explode(',', $sizestring);
?> 
<!--details modal-->
 <?php ob_start();?>
        <div class="modal fade details-1" id="details-modal" tabindex="-1" role="dialog" aria-labelledby="details-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" onclick="closeModal()" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title text-center"><?= $product['title']; ?></h4>
                </div><!--end modal headers-->
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <span id="modal_errors" class="bg-danger"></span>
                            <div class="col-sm-6 fotorama">
                                <?php $imagess = explode(',', $product['image']);
                                      foreach ($imagess as $images):
                                          
                                        ?>
                                <img src="<?= $images; ?>" class="details-img img-responsive"/>
                                <?php endforeach;?>
                            </div><!--end col sm 6-->
                            <div class="col-sm-6">
                                <h4 class="text-danger text-center">Details</h4>
                                <p><?= $product['description'];?></p><hr>
                                <p>price: $<?= $product['price'];?></p>
                                <p>Brand: <?= $brand['brand'];?></p>
                                <form action="add_cart.php" method="post" id="add_product_form">
                                    <input type="hidden" name="product_id" value="<?=$id;?>">
                                    <input type="hidden" name="available" id="available" value="">
                                    <div class="form-group">
                                        <div class="col-xs-3">
                                            <label for="quantity">Quantity: </label>
                                            <input type="number" name="quantity" min="0" id="quantity" class="form-control">
                                        </div><!--end col xs 3-->
                                    </div><!--end form group-->
                                    <br><br><br>
                                    <div class="form-group">
                                        <label for="size">Size:</label>
                                        <select name="size" id="size" class="form-control">
                                            <option value=""></option>
                                            <?php 
                                            foreach ($size_array as $string){
                                                $string_array = explode(':', $string);
                                                $size = $string_array[0];
                                                $available = $string_array[1];
                                                if($available>0){
                                                echo '<option value="'.$size.'" data-available="'.$available.'">'.$size.' ('.$available.' Available)</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </form><!--end form-->
                            </div><!--end col sm 6-->
                        </div><!--row-->
                    </div><!--end conatiner fluid-->
                </div><!--end modal body-->
                <div class="modal-footer">
                    <button class="btn btn-default" onclick="closeModal()">Close</button>
                    <button class="btn btn-primary" onclick="add_to_cart();return false;"><span class="glyphicon glyphicon-shopping-cart"></span>&nbsp; Add to Cart</button>
                </div><!--end modal footers-->
                </div>
            </div><!--end modal dialogue-->
        </div><!--end modal-->
        <script>
            jQuery('#size').change(function(){
                var available = jQuery('#size option:selected').data("available");
                jQuery('#available').val(available);
            });
            
            $(function () {
                $('.fotorama').fotorama({'loop':true,'autoplay':true});
              });
            
             function closeModal(){
                 jQuery('#details-modal').modal('hide');
                 setTimeout(function(){
                     jQuery('#details-modal').remove();
                     jQuery('.modal-backdrop').remove();
                 },500);
             }
        </script>
<?php echo ob_get_clean();?>