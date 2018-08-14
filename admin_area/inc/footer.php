</div>
        
        <!--Footer-->
        <div class="foters" style="text-align: center;margin-top: 10px;">
            Copyright &COPY; 2017 Aminur Rashid
        </div>
            
        <script>
            function updateSizes(){
               var sizeString ='';
               for(var i=1;i<=12;i++){
                   if(jQuery('#size'+i).val()!=''){
                       sizeString += jQuery('#size'+i).val()+':'+jQuery('#qty'+i).val()+':'+jQuery('#threshold'+i).val()+',';
                       
                   }
               }
               jQuery('#sizes').val(sizeString);
            }
            
            function get_child_options(selected){
                if(typeof selected === 'undefined'){
                    var selected = '';
                }
                var parentId = jQuery('#parent').val();
                jQuery.ajax({
                   url: '/fashion_store/admin_area/parser/child_category.php',
                   type: 'post',
                   data: {parentId : parentId, selected: selected},
                   success: function(data){
                       jQuery('#child').html(data);
                   },
                   error: function(){
                     alert("Something went wrong!!")  
                   },
                });
            }
        jQuery('select[name="parent"]').change(function(){
            get_child_options();
        });
        </script>
    </body>
</html>



