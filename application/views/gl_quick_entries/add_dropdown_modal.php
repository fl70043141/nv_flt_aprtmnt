<style>
.btn-huge{
    height: 60px;
    padding-top:18px; 
}     
.btn-circle {
  width: 30px;
  height: 30px;
  text-align: center;
  padding: 6px 0;
  font-size: 12px;
  line-height: 1.428571429;
  border-radius: 15px;
} 
.btn-circle.btn-xl {
  width: 60px;
  height: 60px;
  padding: 15px 16px;
  font-size: 24px;
  line-height: 1.33;
  border-radius: 35px;
}

</style>
 <!-- Modal Checkout-->
   <?php echo form_open("", 'id="form_add_sup" class="form-horizontal"')?>  
   
<div class="modal fade" id="add_quick_entry_mpdal"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New Supplier
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </h5>

      </div> 
      <div class="modal-body form-horizontal">
            <div class="box-body">
              <div class="form-group">
                <label for="account_name" class="col-sm-3 control-label">Account Name<span style="color: red">*</span></label>
                <div class="col-sm-9">
                    <input type="account_name" name="account_name" class="form-control input-lg checkout_input" id="account_name" placeholder="Supplier Name">
                </div>
              </div>    
                <div id="res_op_mod1"></div>
                
            </div> 
      </div>
      <div class="modal-footer"> 
          <div class="row">
              <div class="col-md-6"><a id="back_add_supp"  class="col-md-6 btn btn-block btn-primary btn-lg">Back </a></div>
              <div class="col-md-6"><a id="confirm_add_supp"  class="col-md-6 btn btn-block btn-primary btn-lg">Add Account</a></div>
          </div>
      </div> 
    </div>
  </div>
</div>  
<?php echo form_close();?>
<script>
    $(document).ready(function(){ 
        $('#add_acc_btn').click(function(){ 
           $('#add_quick_entry_mpdal').modal({backdrop: 'static', keyboard: false }); 
        });
        $('#add_quick_entry_mpdal').on('shown.bs.modal', function () {
            $('#account_name').focus();
        })  
 
        
        $('#back_add_supp').click(function(){
            $('#add_quick_entry_mpdal').modal('toggle'); 
        }); 
        $('#confirm_add_supp').click(function(){  
            
            if($('#account_name').val()=='' || $('#account_name').val().length<3){
                fl_alert('info',"Supplier Name Invalid!");
                $('#account_name').focus().select();
                return false;
            } 
            add_quick_entry_acc(); 
        });
        
    });
    
    function add_quick_entry_acc(){
        var ret_val = 0;
        var post_data = jQuery('#form_add_sup').serializeArray(); 
            post_data.push({name:"function_name",value:'create_acc_ajax'}); 
                
            $.ajax({
                url: "<?php echo site_url('Quick_entry_accounts/fl_ajax/');?>",
                type: 'post',
                data : post_data,
                success: function(result){
                     $.ajax({
                            url: "<?php echo site_url('Quick_entry_accounts/fl_ajax/');?>",
                            type: 'post',
                            data : {function_name:'get_dropdown_formodal'},
                            success: function(dd_data){  
                                $('#quick_entry_account_id').empty();
                                if(dd_data!=''){
                                    ret_val =1;
//                                    console.log(dd_data)
                                    var dd_options  = JSON.parse(dd_data); 
                                    var $select = $('#quick_entry_account_id');   
                                    $.each(dd_options,function(index, o) { 
                                         var option = $("<option/>").attr("value", index).text(o);
                                         $select.append(option);
                                     });
                                    $('#quick_entry_account_id').select2();  
                                    $('#quick_entry_account_id').val(result).change();
                                    $('#add_quick_entry_mpdal').modal('toggle'); 
                                }else{  
                                    fl_alert('info',"Something went wrong!");
                                }
                            }
                    });
                    
                }
            }); 
    }
</script>