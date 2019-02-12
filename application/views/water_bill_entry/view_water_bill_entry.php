<?php
$res_usage_data = json_decode($entry_info['resident_usage_data'],true);
//echo '<pre>';print_r($entry_info);  
$show_edit = ($action=='Edit')?'':'hidden';
?>
<style>
    .colored_bg{
        background-color:#E0E0E0;
    }
    .table-line th, .table-line td {
        padding-bottom: 2px;
        border-bottom: 1px solid #ddd;
        text-align:center; 
    }
    .text-right,.table-line.text-right{
        text-align:right;
    }
    .table-line tr{
        line-height: 30px;
    }
    </style>
<div class="row">
<div class="col-md-12">
    <br>   <br>   
    <div class="col-md-12">

    
    
        <div class="top_links">
            <?php echo ($this->user_default_model->check_authority($this->session->userdata(SYSTEM_CODE)['user_role_ID'], $this->router->class, 'index'))?'<a href="'.base_url($this->router->fetch_class()).'" class="btn btn-app "><i class="fa fa-backward"></i>Back</a>':''; ?>
            <?php echo ($this->user_default_model->check_authority($this->session->userdata(SYSTEM_CODE)['user_role_ID'], $this->router->class, 'add'))?'<a href="'.base_url($this->router->fetch_class().'/add/').'" class="btn btn-app "><i class="fa fa-plus"></i>Create New</a>':''; ?>
            <?php echo ($this->user_default_model->check_authority($this->session->userdata(SYSTEM_CODE)['user_role_ID'], $this->router->class, 'delete'))?'<a href="'.base_url($this->router->fetch_class().'/delete/'.$entry_info['id']).'" class="btn btn-app "><i class="fa fa-trash"></i>Delete</a>':''; ?>
            <?php echo ($this->user_default_model->check_authority($this->session->userdata(SYSTEM_CODE)['user_role_ID'], $this->router->class, 'edit'))?'<a href="'.base_url($this->router->fetch_class().'/edit/'.$entry_info['id']).'" class="btn btn-app "><i class="fa fa-pencil"></i>Edit</a>':''; ?>
            <?php echo ($this->user_default_model->check_authority($this->session->userdata(SYSTEM_CODE)['user_role_ID'], $this->router->class, 'print_entry'))?'<a target="_blank" href="'.base_url($this->router->fetch_class().'/print_entry/'.$entry_info['id']).'" class="btn btn-app "><i class="fa fa-print"></i>Print Entry</a>':''; ?> 
             
        </div>
    </div>
    
 <br><hr>
    <section  class="content"> 
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
        
        <div class="">
            
            <?php echo form_open($this->router->class."/validate", 'id="form_search" class="form-horizontal"')?>    
              <!-- general form elements -->
              <?php echo form_hidden('bill_month',(($entry_info['bill_month']>0)?date('F-Y',$entry_info['bill_month']):''));?>
              <?php echo form_hidden('apartment_id',$entry_info['apartment_id']);?>
              <div class="box box-primary"> 
                  <div class="box-body">
                <!-- /.box-header -->
                <!-- form start -->
                <div style="font-size: 15px;" class="row header_form_sales">
                        <div id="result_search" class="col-md-12">
                            <br>
                        </div> 
                        <div class="col-md-4">
                            <dl class="dl-horizontal">
                                <dt>Month:</dt><dd><?php echo (($entry_info['bill_month']>0)?date('F-Y',$entry_info['bill_month']):'');?></dd>
                                <dt>Total Bill Tarrif:</dt><dd><?php echo $entry_info['total_tarrif_name'];?></dd> 
                                <dt>Apartment/Building:</dt><dd><?php echo $entry_info['apartment_name'];?></dd> 
                            </dl> 
                        </div> 
                        <div class="col-md-4">
                            <dl class="dl-horizontal"> 
                                <dt>Bill Date from :</dt><dd><?php echo (($entry_info['bill_date_from']>0)?date(SYS_DATE_FORMAT,$entry_info['bill_date_from']):'');?></dd> 
                                <dt>Bill Date to :</dt><dd><?php echo (($entry_info['bill_date_to']>0)?date(SYS_DATE_FORMAT,$entry_info['bill_date_to']):'');?></dd> 
                                <dt>Days :</dt><dd><?php echo $entry_info['tarrif_days'];?></dd> 
                            </dl> 
                        </div> 
                        <div class="col-md-4">
                            <dl class="dl-horizontal"> 
                                <dt>Previous Reading:</dt><dd><?php echo $entry_info['previous_reading'];?></dd> 
                                <dt>Current UniReading:</dt><dd><?php echo $entry_info['current_reading'];?></dd> 
                                <dt>Total Units Consumed :</dt><dd><?php echo $entry_info['tot_units_consumed'];?></dd> 
                                <dt>Total Bill :</dt><dd><?php echo number_format($entry_info['tot_calculated_amount'],2);?></dd> 
                                <!--<dt>Memo :</dt><dd><?php // echo $entry_info['memo'];?></dd>--> 
                            </dl> 
                        </div> 

                        <div id="result_search" class="col-md-12">
                            <hr>
                        </div>
                    </div>
             
                    <div class="box-body">
                    <div class="col-md-12 col-md-offset-0">
                        <table width="100%" id="example1" class="table table-bordered table-striped fl_scrollable" border="0">
                           <thead>
                               <tr class="colored_bg" style="background-color:#E0E0E0;">
                                    <th width="4%" >#</th>
                                    <th width="14%"  style="text-align: left;">Aprtment No</th> 
                                    <th width="18%"  style="text-align: left;">Resident </th> 
                                    <th width="13%"  style="text-align: left;">Resident Tarrif</th> 
                                    <th width="12%"  style="text-align: right;">Previous Reading</th> 
                                    <th width="12%"  style="text-align: right;">Current Reading</th> 
                                    <th width="12%" style="text-align: right;">Consumed Units</th> 
                                    <th width="15%" style="text-align: right;">Calculated Bill</th> 
                                </tr> 
                           </thead>
                            <tbody>
                    <?php 
                    $i=1;
                    $tot_units = $tot_units_2 = $tot_amount = 0; 
                    foreach ($res_usage_data as $row){
                        $tot_units += $row['consumed_units'];
                        $tot_amount += $row['bill_total'];
//            echo '<pre>';            print_r($row);  
                        echo  '<tr>
                                   <td style="text-align: center;">'.$i.'</td>   
                                   <td style="text-align: left;">'.$row['resident_code'].'</td>   
                                   <td style="text-align: left;">'.$row['resident_name'].'</td>   
                                   <td style="text-align: left;">'.$entry_info['tarrif_name'].'</td>   
                                   <td style="text-align: right;">'.number_format($row['previous_reading'],2).'</td>   
                                   <td style="text-align: right;">'.number_format($row['current_reading'],2).'</td>   
                                   <td style="text-align: right;">'.number_format($row['consumed_units'],2).'</td>   
                                   <td style="text-align: right;">'.number_format($row['bill_total'],2).'</td>   
                               </tr> ';  
                        $i++;
                }  
//             echo $html;
                       ?>
                                <tr> 
                                    <td colspan="6" style="text-align: right;"><b>TOTAL:</b></td>
                                    <td style="text-align: right;"><b> <?php echo number_format($tot_units,2);?></b></td>
                                    <td style="text-align: right;"><b> <?php echo number_format($tot_amount,2);?></b></td>
                                </tr>
                    </tbody>
                </table> 
                    </div>
                         <div class="col-md-6"> 
                            </div>
                    </div>
              </div>
                   <div class="box-footer">
                          <!--<butto style="z-index:1" n class="btn btn-default">Clear Form</button>-->                                    
                                    <!--<button class="btn btn-primary pull-right">Add</button>-->  
                                    <?php if($action != 'View'){?>
                                    <?php echo form_hidden('id', $entry_info['id']); ?>
                                    <?php echo form_hidden('action',$action); ?>
                                    <?php echo form_submit('submit', constant($action) ,'class="btn btn-primary"'); ?>&nbsp;

                                    <?php echo anchor(site_url($this->router->class),'Back','class="btn btn-info"');?>&nbsp;
                                    <?php // echo form_reset('reset','Reset','class = "btn btn-default"'); ?>

                                 <?php }else{ 
                                        echo form_hidden('action',$action);
                                        echo anchor(site_url($this->router->fetch_class()),'OK','class="btn btn-primary"');
                                    } ?>
                      <!--<button type="submit" class="btn btn-primary">Submit</button>-->
                    </div>
              </div>
              <?php echo form_close();?>
        </div>
    </section>
</div>
</div>
    
   
<script>
$(document).keypress(function(e) {
//    fl_alert('info',e.keyCode)
        if(e.keyCode == 80) {//80 for shit+p (print invoice)
           window.open('<?php echo base_url($this->router->fetch_class().'/sales_invoice_print/'.$entry_info['id']);?>');
        }
        if(e.keyCode == 78) {//80 for shit+p (print invoice)
           window.location.replace('<?php echo base_url('Invoices/add/');?>');
        }
        
    });
    
        
$(document).ready(function(){ 
        $("input[name = 'submit']").click(function(){
            if(confirm("Click Ok to confirmation for Cancel or Remove this Rent Reservation")){
                return true;
            }else{
                return false;
            }
        });
        
    $(".top_links a").click(function(){ 
        if($('input[name=action]').val()=='Add' || $('input[name=action]').val()=='Edit'){
            if(!confirm("Click Ok to Confirm leave from here. This form may have unsaved data.")){
                   return false;
            }
        }
    });
        
        $('#saleret_receipt_print').click(function(){
            if(!confirm("Click Ok to confirm Return Note Print")){ 
                return false;
            }
            $.ajax({
			url: "<?php echo site_url('Sales_returns/pos_sales_ret_print_direct');?>",
			type: 'post',
			data : jQuery('#form_search').serializeArray(),
			success: function(result){
                             $("#result_search").html(result);
                             $(".dataTable").DataTable();
                    }
		});
        });
         //delete row
        $('.del_btn_inv_row').click(function(){
            if(!confirm("click ok Confirm remove this item.")){
                return false;
            } 
            $(this).closest('tr').remove();  
        });
});
</script>