</div>
<!--footer start from here-->
<footer>
  <div class="container">
    <div class="row">
      <div class="col-md-4 col-sm-6 footerleft ">
        <div class="logofooter"> Logo</div>
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley.</p>
        <p><i class="fa fa-map-pin"></i> 210, Monprui Para, 2 No gate,Dhaka Framgate -        110085, Bangladesh</p>
        <p><i class="fa fa-phone"></i> Phone (Dhaka) : +880 1687938424</p>
        <p><i class="fa fa-envelope"></i> E-mail : info@webenlance.com</p>
        
      </div>
      <div class="col-md-2 col-sm-6 paddingtop-bottom">
        <h6 class="heading7">GENERAL LINKS</h6>
        <ul class="footer-ul">
          <li><a href="#"> Career</a></li>
          <li><a href="#"> Privacy Policy</a></li>
          <li><a href="#"> Terms & Conditions</a></li>
          <li><a href="#"> Client Gateway</a></li>
          <li><a href="#"> Ranking</a></li>
          <li><a href="#"> Case Studies</a></li>
          <li><a href="#"> Frequently Ask Questions</a></li>
        </ul>
      </div>
      <div class="col-md-3 col-sm-6 paddingtop-bottom">
        <h6 class="heading7">LATEST POST</h6>
        <div class="post">
          <p>facebook crack the movie advertisment code:what it means for you <span>August 3,2017</span></p>
          <p>facebook crack the movie advertisment code:what it means for you <span>August 3,2017</span></p>
          <p>facebook crack the movie advertisment code:what it means for you <span>August 3,2017</span></p>
        </div>
      </div>
      <div class="col-md-3 col-sm-6 paddingtop-bottom">
          <h6 class="heading7">Social Media</h6>
          <ul class="social-ul">
              <li><a href=""><span class="fa fa-facebook-official"></span></a></li> 
              <li><a href=""><span class="fa fa-youtube-play"></span></a></li> 
              <li><a href=""><span class="fa fa-google-plus-official"></span></a></li> 
              <li><a href=""><span class="fa fa-twitter"></span></a></li> 
              <li><a href=""><span class="fa fa-linkedin"></span></a></li> 
          </ul>
      </div>
    </div>
  </div>
</footer>
<!--footer start from here-->

<div class="copyright">
  <div class="container">
    <div class="col-md-6">
      <p>© 2017 - All Rights Developed By Aminur Rashid</p>
    </div>
    <div class="col-md-6">
      <ul class="bottom_ul">
        <li><a href="#">FashionStore.com</a></li>
        <li><a href="#">About us</a></li>
        <li><a href="#">Blog</a></li>
        <li><a href="#">Faq's</a></li>
        <li><a href="#">Contact us</a></li>
        <li><a href="#">Site Map</a></li>
      </ul>
    </div>
  </div>
</div>
     
    <!--header animations-->
    <script>
    jQuery(window).scroll(function(){
        var vscroll = jQuery(this).scrollTop();
        jQuery('#logotext').css({
            "transform" : "translate(0px, "+vscroll/2+"px)"
        });

        var vscroll = jQuery(this).scrollTop();
        jQuery('#back-flower').css({
            "transform" : "translate("+vscroll/5+"px, -"+vscroll/12+"px)"
        });

        var vscroll = jQuery(this).scrollTop();
        jQuery('#fore-flower').css({
            "transform" : "translate(0px, -"+vscroll/2+"px)"
        });
    });
    
    function detailsmodal(id){
        var data = {"id" : id};
        jQuery.ajax({
           url : '/fashion_store/inc/detailsmodal.php',
           method : "post",
           data : data,
           success: function(data){
               jQuery('body').append(data);
               jQuery('#details-modal').modal('toggle');
           },
           error: function(){
               alert("Something Went Wrong!");
           }
        });
    }
    
    function update_cart(mode,edit_id,edit_size){
        var data = {"mode" : mode, "edit_id" : edit_id, "edit_size" : edit_size};
        jQuery.ajax({
            url : '/fashion_store/admin_area/parser/update_cart.php',
            method : "post",
            data : data,
            success : function(){location.reload();},
            error : function(){alert("Something Went Wrong!");}
        });
    }
    
    function add_to_cart(){
        jQuery('#modal_errors').html("");
        var size = jQuery('#size').val();
        var quantity = jQuery('#quantity').val();
        var available = jQuery('#available').val();
        var error = '';
        var data = jQuery('#add_product_form').serialize();
        
        if(size == '' || quantity == '' || quantity == 0){
            error +='<p class="text-danger text-center">You must chose a size & quantity</p>';
            jQuery('#modal_errors').html(error);
            return;
        }else if(quantity > available){
            error +='<p class="text-danger text-center">There are only '+available+' available</p>';
            jQuery('#modal_errors').html(error);
            return;
        }else{
            jQuery.ajax({
                url : '/fashion_store/admin_area/parser/add_cart.php',
                method : 'post',
                data : data,
                success : function(){
                    location.reload();
                },
                error : function(){alert("Somwthing Went Wrong!");}
            });
        }
    }
    </script>               
    </body>
</html>

