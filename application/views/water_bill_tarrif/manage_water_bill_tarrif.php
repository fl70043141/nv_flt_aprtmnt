<?php
	
	$result = array(
                        'id'=>"",
                        'tarrif_name'=>"",
                        'tarrif_days'=>"",
                        'resident_code'=>"",
                        'apartment_id'=>"",
                        'description'=>"", 
                        'vat_val'=>"", 
                        'vat_calculation_method'=>"1", //1 percentage
                        'email'=>"", 
                        'status'=>1
                        );   		
	
	 $add_hide ='';
	switch($action):
	case 'Add':
		$heading	= 'Add';
		$dis		= '';
		$view		= '';
		$o_dis		= ''; 
		$add_hide       = 'hidden'; 
	break;
	
	case 'Edit':
		if(!empty($tarrif_info[0])){$result= $tarrif_info[0];} 
		$heading	= 'Edit';
		$dis		= '';
		$view		= '';
		$o_dis		= ''; 
	break;
	
	case 'Delete':
		if(!empty($tarrif_info[0])){$result= $tarrif_info[0];} 
		$heading	= 'Delete';
		$dis		= 'readonly';
		$view		= '';
		$o_dis		= ''; 
		$check_bx_dis		= 'disabled'; 
	break;
      
	case 'View':
		if(!empty($tarrif_info[0])){$result= $tarrif_info[0];} 
		$heading	= 'View';
		$view		= 'hidden';
		$dis        = 'readonly';
		$o_dis		= 'disabled'; 
	break;
endswitch;	 

//echo '<pre>';print_r($tarrif_info);
?> 
<!-- Main content -->
 <br>
        <div class="col-md-12">
            
             <!--Flash Error Msg-->
                             <?php  if($this->session->flashdata('error') != ''){ ?>
					<div class='alert alert-danger ' id="msg2">
					<a class="close" data-dismiss="alert" href="#">&times;</a>
					<i ></i>&nbsp;<?php echo $this->session->flashdata('error'); ?>
					<script>jQuery(document).ready(function(){jQuery('#msg2').delay(1500).slideUp(1000);});</script>
					</div>
				<?php } ?>
				
					<?php  if($this->session->flashdata('warn') != ''){ ?>
					<div class='alert alert-success ' id="msg2">
					<a class="close" data-dismiss="alert" href="#">&times;</a>
					<i ></i>&nbsp;<?php echo $this->session->flashdata('warn'); ?>
					<script>jQuery(document).ready(function(){jQuery('#msg2').delay(1500).slideUp(1000);});</script>
					</div>
				<?php } ?>  
            <div class="top_links">
                <?php echo ($this->user_default_model->check_authority($this->session->userdata(SYSTEM_CODE)['user_role_ID'], $this->router->class, 'index'))?'<a href="'.base_url($this->router->fetch_class()).'" class="btn btn-app "><i class="fa fa-backward"></i>Back</a>':''; ?>
               <?php echo ($this->user_default_model->check_authority($this->session->userdata(SYSTEM_CODE)['user_role_ID'], $this->router->class, 'add'))?'<a href="'.base_url($this->router->fetch_class().'/add').'" class="'.$add_hide.' btn btn-app "><i class="fa fa-plus"></i>Create New</a>':''; ?> 
                <?php echo ($this->user_default_model->check_authority($this->session->userdata(SYSTEM_CODE)['user_role_ID'], $this->router->class, 'edit'))?'<a href="'.base_url($this->router->fetch_class().'/edit/'.$result['id']).'" class="'.$add_hide.' btn btn-app "><i class="fa fa-pencil"></i>Edit</a>':''; ?>
                <?php echo ($this->user_default_model->check_authority($this->session->userdata(SYSTEM_CODE)['user_role_ID'], $this->router->class, 'delete'))?'<a href="'.base_url($this->router->fetch_class().'/delete/'.$result['id']).'" class="'.$add_hide.' btn btn-app  '.(($action=='Delete')?'hide ':'').' "><i class="fa fa-trash"></i>Delete</a>':''; ?>
                 
            </div>
        </div>
 <br><hr>
    <section  class="content"> 
        <div class="">
              <!-- general form elements -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title"><?php echo $action;?> </h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
              
             <?php echo form_open_multipart($this->router->fetch_class()."/validate"); ?> 
   
                    <div class="box-body fl_scroll">
                              
                        <div class="row"> 
                                        <div class="col-md-6">

                                            <h5>Water Bill Tarrif Setups</h5>
                                            <hr> 
                                               <div class="form-group">
                                                   <label class="col-md-3 control-label">Tarrif Name<span style="color: red">*</span></label>
                                                   <div class="col-md-9">    
                                                       <?php echo form_input('tarrif_name', set_value('tarrif_name',$result['tarrif_name']), 'id="tarrif_name" class="form-control" placeholder="Enter Tarrif Name"'.$dis.' '.$o_dis.' '); ?>
                                                      <?php echo form_error('tarrif_name');?>&nbsp;
                                                   </div> 
                                               </div>
                                               <div class="form-group">
                                                   <label class="col-md-3 control-label">Days for Tarrif<span style="color: red">*</span></label>
                                                   <div class="col-md-9">    
                                                       <?php echo form_input('tarrif_days', set_value('tarrif_days',$result['tarrif_days']), 'id="tarrif_days" class="form-control" placeholder="Number days for calculation"'.$dis.' '.$o_dis.' '); ?>
                                                      <?php echo form_error('tarrif_days');?>&nbsp;
                                                   </div> 
                                               </div>
                                            
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Active</label>
                                                        <div class="col-md-9">                                            
                                                            <div class="input-group">
                                                                 <label class="switch  switch-small">
                                                                    <!--<input type="checkbox"  value="0">-->
                                                                    <?php echo form_checkbox('status', set_value('status','1'),$result['status'], 'id="status" placeholder=""'.$dis.' '.$o_dis.' '); ?>
                                                                     <span></span>
                                                                </label>
                                                             </div>                                            
                                                            <?php echo form_error('status');?>&nbsp;
                                                        </div>
                                                    </div> 
                                        </div>
                                    <div class="col-md-6">
                                       <h5>Addon Values </h5>
                                         <hr>  
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">VAT <?php echo (($result['vat_calculation_method']==1)?'(Percentage)':'Fixed Amount');?><span style="color: red">*</span></label>
                                                <div class="col-md-9">    
                                                    <?php echo form_input('vat_val', set_value('vat_val',$result['vat_val']), 'id="vat_val" class="form-control" placeholder="Enter VAT Percentage"'.$dis.' '.$o_dis.' '); ?>
                                                    <?php echo form_error('vat_val');?>&nbsp;
                                                </div> 
                                            </div> 
                                             
                                     </div>
                            
                                    <div class="col-md-12">
                                       <h4>Tarrif Table </h4>
                                         <hr>  
                                         <table class="table table-striped">
                                             <thead>
                                                <tr>
                                                    <th style="width: 18%">Order</th>
                                                    <th style="width: 18%">Units From</th>
                                                    <th style="width: 20%">Units To</th>
                                                    <th style="width: 20%">Usage Charges</th>
                                                    <th style="width: 20%">Service Charges</th>
                                                    <th style="width: 10%"><a id="add_tarrf_row" class="btn btn-success"><span class="fa fa-plus"></span></a></th>
                                                </tr>
                                             </thead>
                                             <tbody id="tarrif_rows">
                                                 <?php
                                                 $i=0;
                                                 if(isset($tarrif_row_info) && !empty($tarrif_row_info)){
                                                    foreach ($tarrif_row_info as $tarrif_row){
                                                        $i++;
                                                        echo '<tr id="tr_'.$i.'">
                                                                    <td><input style="width:70%;" type="text" name="tarrif_row['.$i.'][order]" placeholder="Calculation Order" class="form-control" value="'.$tarrif_row['order'].'"></td> 
                                                                    <td><input style="width:70%;" type="text" name="tarrif_row['.$i.'][units_from]" placeholder="Unit Range min" class="form-control" value="'.$tarrif_row['units_from'].'"></td> 
                                                                    <td><input style="width:70%;" type="text" name="tarrif_row['.$i.'][units_to]"  placeholder="Unit Range max" class="form-control" value="'.$tarrif_row['units_to'].'"></td>
                                                                    <td><input style="width:70%;" type="text" name="tarrif_row['.$i.'][usage_charge]"  placeholder="Usagae amount per unit" class="form-control" value="'.$tarrif_row['usage_charge'].'"></td>
                                                                    <td><input style="width:70%;" type="text" name="tarrif_row['.$i.'][service_charge]" placeholder="Fixed Service Charge" class="form-control" value="'.$tarrif_row['service_charge'].'"></td> 
                                                                    <td><a id="'.$i.'" class="btn btn-sm btn-danger row_remove"><span class="fa fa-trash"></span></a></td>
                                                                 </tr>
                                                            ';
                                                    }
                                                 }
//                                                 echo '<pre>';                                                 print_r($tarrif_row_info);
                                                 ?>
                                             </tbody>
                                         </table>
                                    </div>
                                        
                        </div>
                    </div>
                          <!-- /.box-body -->

                    <div class="box-footer">
                          <!--<butto style="z-index:1" n class="btn btn-default">Clear Form</button>-->                                    
                                    <!--<button class="btn btn-primary pull-right">Add</button>-->  
                                    <?php if($action != 'View'){?>
                                    <?php echo form_hidden('id', $result['id']); ?>
                                    <?php echo form_hidden('action',$action); ?>
                                    <?php echo form_submit('submit', constant($action) ,'class="btn btn-primary"'); ?>&nbsp;

                                    <?php echo anchor(site_url($this->router->fetch_class()),'Back','class="btn btn-info"');?>&nbsp;
                                    <?php // echo form_reset('reset','Reset','class = "btn btn-default"'); ?>

                                 <?php }else{ 
                                        echo form_hidden('action',$action);
                                        echo anchor(site_url($this->router->fetch_class()),'OK','class="btn btn-primary"');
                                    } ?>
                      <!--<button type="submit" class="btn btn-primary">Submit</button>-->
                    </div>
                  </form>
                </div>
                <!-- /.box -->
          </div> 
    </section> 
 
 
<!--     //image Lightbox-->
     <div tabindex="-1" class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
            <div class="modal-content"> 
                  <div align="" class="modal-body">
                      <div><center><img class="model_img"   src=""></center> </div>
                  </div>
                  <div class="modal-footer">
                          <button class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
             </div>
            </div>
          </div>
 <style>
    .modal-dialog {width:800px;}
    .thumbnail {margin-bottom:6px;}
    .modal-body {width:800px; align:center;}
    .model_img {width: 500px;}
</style>
<script>
    
$(document).ready(function(){  
    $("form").submit(function(){ 
        if(!confirm("Click Ok to Confirm form Submition.")){
               return false;
        }
    });
    $(".top_links a").click(function(){ 
        if(!confirm("Click Ok to Confirm leave from here. This form may have unsaved data.")){
               return false;
        }
    });
    
    $('#add_tarrf_row').click(function(){

        var rowId = $('#tarrif_rows tr').length+'_'+$.now();
//        alert(rowId);
        var row_str = '<tr id="tr_'+rowId+'">'+
                            '<td><input style="width:70%;" type="text" name="tarrif_row['+rowId+'][order]" placeholder="Calculation Order" class="form-control"></td> '+
                            '<td><input style="width:70%;" type="text" name="tarrif_row['+rowId+'][units_from]" placeholder="Unit Range min" class="form-control"></td> '+
                            '<td><input style="width:70%;" type="text" name="tarrif_row['+rowId+'][units_to]"  placeholder="Unit Range max" class="form-control"></td> '+
                            '<td><input style="width:70%;" type="text" name="tarrif_row['+rowId+'][usage_charge]"  placeholder="Usagae amount per unit" class="form-control"></td> '+
                            '<td><input style="width:70%;" type="text" name="tarrif_row['+rowId+'][service_charge]" placeholder="Fixed Service Charge" class="form-control"></td> '+ 
                            '<td><a id="'+rowId+'" class="btn btn-sm btn-danger row_remove"><span class="fa fa-trash"></span></a></td>'+
                        ' </tr>';
//               alert(row_str);
        var newRow = $(row_str);
        $('table #tarrif_rows').append(newRow);
        
        $('.row_remove').click(function(){
            if(!confirm("click ok Confirm remove this tarrif row.")){
                return false;
            }else{
                $('#tr_'+(this.id)).remove();
            }
        });
        
//        $('#tarrif_rows').sortable();
    });
    
        $('.row_remove').click(function(){
//            alert(this.id)
            if(!confirm("click ok Confirm remove this tarrif row.")){
                return false;
            }else{
                $('#tr_'+(this.id)).remove();
            }
        });
});
</script>