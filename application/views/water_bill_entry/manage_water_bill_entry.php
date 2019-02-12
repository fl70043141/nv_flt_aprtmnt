<?php
	
	$result = array(
                        'id'=>"",
                        'bill_month'=> strtotime(date('Y-M')),
                        'water_bill_tarrif_id'=>"",
                        'bill_date_from'=>  strtotime('-30 days'),
                        'bill_date_to'=> strtotime('now'),
                        'previous_reading'=>"",
                        'current_reading'=>"",
                        'total_tarrif_id'=>3,
            
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
		if(!empty($entry_info)){$result= $entry_info;} 
		$heading	= 'Edit';
		$dis		= '';
		$view		= '';
		$o_dis		= ''; 
	break;
	
	case 'Delete':
		if(!empty($entry_info)){$result= $entry_info;} 
		$heading	= 'Delete';
		$dis		= 'readonly';
		$view		= '';
		$o_dis		= ''; 
		$check_bx_dis		= 'disabled'; 
	break;
      
	case 'View':
		if(!empty($entry_info)){$result= $entry_info;} 
		$heading	= 'View';
		$view		= 'hidden';
		$dis        = 'readonly';
		$o_dis		= 'disabled'; 
	break;
endswitch;	 

//echo '<pre>';print_r($entry_info);
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
                 <?php echo ($this->user_default_model->check_authority($this->session->userdata(SYSTEM_CODE)['user_role_ID'], $this->router->class, 'print_entry'))?'<a target="_blank" href="'.base_url($this->router->fetch_class().'/print_entry/'.((isset($entry_info['id']))?$entry_info['id']:'')).'" class="'.$add_hide.' btn btn-app "><i class="fa fa-print"></i>Print Entry</a>':''; ?> 
            </div>
        </div>
 <br><hr>
    <section  class="content"> 
        <div class="">
              <!-- general form elements -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title"><?php echo $action;?> Water Bill </h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
              
             <?php echo form_open_multipart($this->router->fetch_class()."/validate"); ?> 
   
                    <div class="box-body fl_scroll">
                              
                        <div class="row"> 
                                        <div class="col-md-6">

                                            <h5>Bill General Information</h5>
                                            <hr> 
                                               <div class="form-group">
                                                   <label class="col-md-3 control-label">Month<span style="color: red">*</span></label>
                                                   <div class="col-md-9">                                   
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                            <?php echo form_input('bill_month', set_value('bill_month',date('F-Y',$result['bill_month'])), 'id="bill_month" class="form-control" placeholder="Click to select month"'); ?>
                                                            
                                                        </div> 
                                                        <?php echo form_error('bill_month');?>&nbsp;
                                                   </div> 
                                               </div>
                                            
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Bill Tarrif</label>
                                                <div class="col-md-9">    
                                                     <?php  echo form_dropdown('water_bill_tarrif_id',$tarrif_list,set_value('water_bill_tarrif_id',$result['water_bill_tarrif_id']),' class="form-control select2" data-live-search="true" id="water_bill_tarrif_id"'.$o_dis.'');?> 
                                                    <?php echo form_error('water_bill_tarrif_id');?>&nbsp;
                                                </div> 
                                            </div> 
                                            
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Apartment/ Building</label>
                                                    <div class="col-md-9">    
                                                         <?php  echo form_dropdown('apartment_id',$apartment_list,set_value('apartment_id',$result['apartment_id']),' class="form-control select2" data-live-search="true" id="apartment_id"'.$o_dis.'');?> 
                                                        <?php echo form_error('apartment_id');?>&nbsp;
                                                    </div> 
                                                </div> 
                                        </div>
                                    <div class="col-md-6">
                                       <h5>Tarrif Info </h5>
                                         <hr>  
                                              <div class="form-group">
                                                   <label class="col-md-3 control-label">Bill Date from<span style="color: red">*</span></label>
                                                   <div class="col-md-9">                                   
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                            <?php echo form_input('bill_date_from', set_value('bill_date_from',date(SYS_DATE_FORMAT,$result['bill_date_from'])), 'id="bill_date_from" class="form-control datepicker" placeholder="Click to select Bill Date"'); ?>
                                                            
                                                        </div> 
                                                        <?php echo form_error('bill_date_from');?>&nbsp;
                                                   </div> 
                                               </div>
                                              <div class="form-group">
                                                   <label class="col-md-3 control-label">Bill Date to<span style="color: red">*</span></label>
                                                   <div class="col-md-9">                                   
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                            <?php echo form_input('bill_date_to', set_value('bill_date_to', date(SYS_DATE_FORMAT,$result['bill_date_to'])), 'id="bill_date_to" class="form-control datepicker" placeholder="Click to select Bill Date"'); ?>
                                                            
                                                        </div> 
                                                        <?php echo form_error('bill_date_to');?>&nbsp;
                                                   </div> 
                                               </div>
                                         
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Days</label>
                                                    <div class="col-md-9">    
                                                        <?php echo form_input('tarrif_days', set_value('tarrif_days',$result['tarrif_days']), 'id="tarrif_days" class="form-control" placeholder="Enter Number of days"'.$dis.' '.$o_dis.' '); ?>
                                                       <?php echo form_error('tarrif_days');?>&nbsp;
                                                    </div> 
                                                </div>
                                     </div>
                            
                                    <div class="col-md-12 bg-gray">
                                        <div class="row">
                                            
                                        <div class="col-md-12 ">
                                            <h5>Total Water bill Calculation</h5></div>
                                            
                                                <div class="col-md-2">
                                                    <div class="form-group pad">
                                                        <label for="previous_reading">Previous Reading<span style="color: red">*</span></label>
                                                         <?php echo form_input('previous_reading', set_value('previous_reading',$result['previous_reading']), 'id="previous_reading" class="form-control" placeholder="Enter Reading"'.$dis.' '.$o_dis.' '); ?>
                                                         <?php echo form_error('previous_reading');?>&nbsp;
                                                        </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group pad">
                                                        <label for="current_reading">Current Reading<span style="color: red">*</span></label>
                                                         <?php echo form_input('current_reading', set_value('current_reading',$result['current_reading']), 'id="current_reading" class="form-control" placeholder="Enter Reading"'.$dis.' '.$o_dis.' '); ?>
                                                         <?php echo form_error('current_reading');?>&nbsp;
                                                        </div>
                                                </div> 
                                                <div class="col-md-3">
                                                    <div class="form-group pad">
                                                        <label for="total_tarrif_id">Tarrif for Total Bill<span style="color: red">*</span></label>
                                                         <?php  echo form_dropdown('total_tarrif_id',$tarrif_list,set_value('total_tarrif_id',$result['total_tarrif_id']),' class="form-control select2" data-live-search="true" id="total_tarrif_id"'.$o_dis.'');?> 
                                                        <?php echo form_error('total_tarrif_id');?>&nbsp;
                                                        </div>
                                                </div> 
                                                <div class="col-md-1">
                                                    <div class="form-group pad">
                                                        <br>
                                                        <a id="calc_full_bill" class="btn btn-lg btn-success">Calculate</a>
                                                    </div>
                                                </div> 
                                            <div class="col-md-offset-1 col-md-3">
                                                <br>
                                                <dl class="dl-horizontal"> 
                                                    <dt>Total Units Consumed :</dt><dd><span id="tot_units_consumed_txt">0.00</span> Units<input hidden type="text" name="tot_units_consumed" id="tot_units_consumed"></dd> 
                                                    <dt>Bill Amount :</dt><dd><span id="tot_calculated_amount_txt">0.00</span><input hidden type="text" name="tot_calculated_amount" id="tot_calculated_amount"></dd> 
                                                </dl> 
                                            </div>
                                        </div>
                                    </div>
                            <div id="search_result_1">aaaa</div>
                                    <div class="col-md-12">
                                       <h4>Residents Bill Entry</h4>
                                         <hr>  
                                         <table class="table table-striped">
                                             <thead>
                                                <tr>
                                                    <th style="width: 4%">#</th>
                                                    <th style="width: 10%">Apartment No</th>
                                                    <th style="width: 20%">Resident Name</th>
                                                    <th style="width: 15%">Previous reading</th>
                                                    <th style="width: 15%">Current Reading</th>
                                                    <th style="width: 18%">Consumed Units</th>
                                                    <th style="width: 18%">Calculated Bill</th>
                                                </tr>
                                             </thead>
                                             <tbody id="resident_bill_rows">
                                             </tbody>
                                             
                                             <tfoot>
                                                <tr>
                                                    <th colspan="5" style="text-align: left;">Total</th>
                                                    <th style="text-align: center;"><span id="row_total_units">0.00</span></th>
                                                    <th style="text-align: center;"><span id="row_total_bill_amount">0.00</span></th>
                                                </tr>
                                             </tfoot>
                                         </table>
                                    </div>
                                        
                        </div>
                        <input hidden id="inpt_tarrif_data" name="inpt_tarrif_data" value='<?php echo $tarrif_data;?>'>
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
    
    
    $("#bill_date_from, #bill_date_to").change(function(){ 
        var start= $("#bill_date_from").datepicker("getDate");
        var end= $("#bill_date_to").datepicker("getDate");
        var days = (end- start) / (1000 * 60 * 60 * 24);
        if(!isNaN(days)){
            days = Math.round(days);
            $('#tarrif_days').val(days);
        } 
    });
    
    $('#apartment_id').change(function(){
        
        load_residents();
    });
    $('#water_bill_tarrif_id').change(function(){
        
        $('table #resident_bill_rows tr').each(function(){
            $('#'+(this.id)+' .resident_read').trigger('keyup');
            
//            alert(this.id)
        });
        
    });
    $('#bill_date_from').trigger('change');
    
   
    load_residents();
//    $('#previous_reading, #current_reading').keyup(function(){
    $('#calc_full_bill').click(function(){
        var tot_prev_read = (isNaN(parseFloat($('#previous_reading').val())))?0:parseFloat($('#previous_reading').val());
        var tot_curr_read = (isNaN(parseFloat($('#current_reading').val())))?0:parseFloat($('#current_reading').val());
        var tot_consumed = tot_curr_read - tot_prev_read;
//        alert(tot_prev_read)
        if(tot_consumed>0){
            $('#tot_units_consumed').val(tot_consumed);
            $('#tot_units_consumed_txt').text(tot_consumed);
            tarrif_calculation($('#total_tarrif_id').val(),$('#tarrif_days').val(),$('#tot_units_consumed').val());
        }
        
    });
    
    $('#calc_full_bill').trigger('click');
    
        $('.row_remove').click(function(){
//            alert(this.id)
            if(!confirm("click ok Confirm remove this tarrif row.")){
                return false;
            }else{
                $('#tr_'+(this.id)).remove();
            }
        });
        $("#bill_month").datepicker( {
            format: "MM-yyyy", 
            minViewMode: "months",
            autoclose: true
        });
});

function load_residents(){ 
      $.ajax({
                url: "<?php echo site_url($this->router->class.'/fl_ajax');?>",
                type: 'post',
                data : {function_name:'get_apartment_residents',apartment_id:$('#apartment_id').val(), entry_id:$('input[name=id]').val()},
                success: function(result){ 
//                                $("#search_result_1").html(result);
                                var res2 = JSON.parse(result);  
                                    $('table #resident_bill_rows').html("");
                                
                                var total = 0;
                                var count=1;
                                $(res2).each(function (index, elment) {
//                                    console.log(elment); 
//                                    alert(elment.previous_entry); return false;
                                    var rowId = elment.id+'_'+$('#resident_bill_rows tr').length;
                                     var row_str = '<tr id="tr_'+rowId+'">'+
                                                        '<td>'+count+'</td>'+
                                                        '<td>'+elment.resident_code+'<input hidden type="text" name="resident_usage['+rowId+'][resident_code]" value="'+elment.resident_code+'"><input hidden hidden type="text" name="resident_usage['+rowId+'][resident_id]" value="'+elment.id+'"></td> '+
                                                        '<td>'+elment.resident_name+'<input hidden type="text" name="resident_usage['+rowId+'][resident_name]" value="'+elment.resident_name+'"></td> '+
                                                        '<td><input id="'+rowId+'_previous_read" style="width:70%;" type="text" name="resident_usage['+rowId+'][previous_reading]" placeholder="Last Reading" value="'+((typeof(elment.previous_entry)==='undefined')?0:elment.previous_entry)+'" class="form-control resident_read"></td> '+
                                                        '<td><input id="'+rowId+'_current_read" style="width:70%;" type="text" name="resident_usage['+rowId+'][current_reading]"  placeholder="Enter New Reading" value="'+((typeof(elment.current_entry)==='undefined')?0:elment.current_entry)+'" class="form-control resident_read"></td> '+
                                                        '<td><input id="'+rowId+'_consumed_units" style="width:70%;" type="text" name="resident_usage['+rowId+'][consumed_units]"  placeholder="Consumed Units" value="'+((typeof(elment.consumed_units)==='undefined')?0:elment.consumed_units)+'" class="form-control cl_row_units"></td> '+
                                                        '<td><input id="'+rowId+'_bill_total" style="width:70%;" type="text" name="resident_usage['+rowId+'][bill_total]" value="'+((typeof(elment.bill_total)==='undefined')?0:elment.bill_total)+'"  class="form-control cl_row_bill"></td> '+ 
                                                    ' </tr>';
                            //               alert(row_str);
                                    var newRow = $(row_str);
                                    $('table #resident_bill_rows').append(newRow);
                                    
                                        count++;
                                });
                                
                                        $('.resident_read').keyup(function(){
                                            var rowId = (this.id).split('_')[0]+'_'+(this.id).split('_')[1]; 
//                                            alert(rowId)
                                            var tot_prev_read = (isNaN(parseFloat($('#'+rowId+'_previous_read').val())))?0:parseFloat($('#'+rowId+'_previous_read').val());
                                            var tot_curr_read = (isNaN(parseFloat($('#'+rowId+'_current_read').val())))?0:parseFloat($('#'+rowId+'_current_read').val());
//                                            alert(tot_curr_read); return false;
                                            var tot_consumed = tot_curr_read - tot_prev_read;
                                            if(tot_consumed>0){
                                                $('#'+rowId+'_consumed_units').val(tot_consumed);
//                                                $('#tot_units_consumed_txt').text(tot_consumed);


                                               var calcd_amount = tarrif_calculation_frontend($('#water_bill_tarrif_id').val(),$('#tarrif_days').val(),tot_consumed);
                                                
                                                $('#'+rowId+'_bill_total').val(parseFloat(calcd_amount).toFixed(2));
                                                
                                                                
                                            calc_total_row();
//                                                return false;
//                                                $.ajax({
//                                                        url: "<?php echo site_url($this->router->class.'/fl_ajax');?>",
//                                                        type: 'post',
//                                                        data : {function_name:'calculate_tarrif',tarrif_id:$('#water_bill_tarrif_id').val(), tarrif_days:$('#tarrif_days').val(),units:tot_consumed},
//                                                        success: function(result){
//                                                            if(!isNaN(result)){
//                                                                $('#'+rowId+'_bill_total').val(parseFloat(result).toFixed(2));
//                                                                
//                                            calc_total_row();
////                                                                $("#tot_calculated_amount_txt").text(parseFloat(result).toFixed(2));
//                                                            } 
//                                                        }
//                                                });
                                                
                                            }
                                            
                                        });
                                         $('.resident_read').trigger('keyup');
                                        
                                
//                                fl_aler
                            }
            });
            
}
function tarrif_calculation(tarrif_id,tarrif_days,units){

      $.ajax({
                url: "<?php echo site_url($this->router->class.'/fl_ajax');?>",
                type: 'post',
                data : {function_name:'calculate_tarrif',tarrif_id:tarrif_id, tarrif_days:tarrif_days,units:units},
                success: function(result){
                    if(!isNaN(result)){
                        $("#tot_calculated_amount").val(result);
                        $("#tot_calculated_amount_txt").text(parseFloat(result).toFixed(2));
                    } 
                }
    });
}
function calc_total_row(){
    
        var tot_consumed = 0;
        var tot_amount = 0;
        $('table #resident_bill_rows tr').each(function(){
            var rowId = (this.id).split('_')[1]+'_'+(this.id).split('_')[2]; 
            var row_bill = $('#'+rowId+'_bill_total').val();
            var row_consumed = $('#'+rowId+'_consumed_units').val();
//            alert(row_bill)
            
            if(row_bill!='' && row_bill!=null && !isNaN(row_bill)){
                tot_amount += parseFloat(row_bill);
            }
            if(row_consumed!='' && row_consumed!=null && !isNaN(row_consumed)){
                tot_consumed += parseFloat(row_consumed);
            }
//        alert('Consumed units: '+tot_consumed+' | Amount Bill : '+tot_amount);
            
        });
        $('#row_total_bill_amount').text(tot_amount.toFixed(2));
        $('#row_total_units').text(tot_consumed.toFixed(2));
//        alert('Consumed units: '+tot_consumed+' | Amount Bill : '+tot_amount);
}

function tarrif_calculation_frontend(tarrif_id, days, units){
    
    var tr_json = $('#inpt_tarrif_data').val();
//    $('#search_result_1').html(tr_json);
    var res1 = JSON.parse(tr_json);
    var total_usage_charge = 0; var total_service_charges = 0; var total_charge =0; var vat_amount =  0;
    var tmp_units = parseFloat(units);
    var tarrif_days = (typeof(parseFloat(days)) == 'undefined')? 30:parseFloat(days); //defult 30 daus
     var tarrif_row_last_id = '';
     
    if((res1[tarrif_id]['rows']).length>0){
        
             
        $.each(res1[tarrif_id]['rows'],function(key,tarrif_row){
            
                    var prev_row_to = 0;
                    var prev_key = key-1;
                    if(typeof(res1[tarrif_id]['rows'][prev_key]) != 'undefined')
                        prev_row_to = parseFloat(res1[tarrif_id]['rows'][prev_key]['units_to']);
                    
                    if(tmp_units>0){
                        var unit_dif = (parseFloat(tarrif_row['units_to']) - prev_row_to)*(tarrif_days/parseFloat(res1[tarrif_id]['tarrif_days'])); //dif for days
                        
                        if(tmp_units > unit_dif && unit_dif>0 && (tarrif_row['units_to']!='0' ||  tarrif_row['units_to']!='-1')){
                            total_usage_charge += unit_dif * parseFloat(tarrif_row['usage_charge']); 
                        }else{
                             total_usage_charge += tmp_units * parseFloat(tarrif_row['usage_charge']);
                        }
                            tarrif_row_last_id = key;
                            tmp_units -= unit_dif;
                    }
                    
                });
                total_service_charges += parseFloat(res1[tarrif_id]['rows'][tarrif_row_last_id]['service_charge']) * (days/parseFloat(res1[tarrif_id]['tarrif_days']));
                total_charge = total_service_charges + total_usage_charge;
     }
     
                        
            //vat calculation
            if(typeof(res1[tarrif_id]['vat_val']) != 'undefined'){
                if(res1[tarrif_id]['vat_calculation_method']=='1'){
//                    $vat_amount = 0.12*$total_charge;
                    var vat_amount = (parseFloat(res1[tarrif_id]['vat_val'])/100)*total_charge;
//                    echo '<br>VAT ('.$calculation_tarrif[0]['vat_val'].')charge:'.$vat_amount;
                    total_charge += vat_amount; 
                }
            }
            return total_charge;
//    $.each(res1,function(index,item){
//        
//        console.log(item); 
//        $('#search_result_1').append(item.tarrif_name);
//    }); 
    
}
</script>