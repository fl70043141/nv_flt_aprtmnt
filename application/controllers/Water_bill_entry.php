<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Water_bill_entry extends CI_Controller {

	
        function __construct() {
            parent::__construct();
            $this->load->model('Water_bill_entry_model'); 
        }

        public function index(){
            $this->view_search();
	}
        
        function view_search($datas=''){
            $data['search_list'] = $this->Water_bill_entry_model->search_result();
            $data['main_content']='water_bill_entry/search_water_bill_entry';  
            $this->load->view('includes/template',$data);
	}
        
	function add(){ 
            $data  			= $this->load_data(); 
            $data['action']		= 'Add';
            $data['main_content']='water_bill_entry/manage_water_bill_entry'; 
            $this->load->view('includes/template',$data);
	}
	
	function edit($id){ 
            $data  			= $this->load_data($id); 
            $data['action']		= 'Edit';
            $data['main_content'] = 'water_bill_entry/manage_water_bill_entry'; 
            $this->load->view('includes/template',$data);
	}
	
	function delete($id){ 
            $data  			= $this->load_data($id);
            $data['action']		= 'Delete';
            $data['main_content'] = 'water_bill_entry/view_water_bill_entry'; 
            $this->load->view('includes/template',$data);
	}
	
	function view($id){
            $data  			= $this->load_data($id);
            $data['action']		= 'View';
            $data['main_content'] = 'water_bill_entry/view_water_bill_entry'; 
//            $data['user_role_list'] = get_dropdown_data(USER_ROLE,'user_role','id');
            $this->load->view('includes/template',$data);
	}
	
        
	function validate(){   
            $this->form_val_setrules(); 
            if($this->form_validation->run() == False){
                switch($this->input->post('action')){
                    case 'Add':
                            $this->session->set_flashdata('error','Not Saved! Please Recheck the form'); 
                            $this->add();
                            break;
                    case 'Edit':
                            $this->session->set_flashdata('error','Not Saved! Please Recheck the form');
                            $this->edit($this->input->post('id'));
                            break;
                    case 'Delete':
                            $this->delete($this->input->post('id'));
                            break;
                } 
            }
            else{
                switch($this->input->post('action')){
                    case 'Add':
                            $this->create();
                    break;
                    case 'Edit':
                        $this->update();
                    break;
                    case 'Delete':
                        $this->remove();
                    break;
                    case 'View':
                        $this->view();
                    break;
                }	
            }
	}
        
	function form_val_setrules(){

            $this->form_validation->set_error_delimiters('<p style="color:rgb(255, 115, 115);" class="help-block"><i class="glyphicon glyphicon-exclamation-sign"></i> ','</p>');

//            $this->form_validation->set_rules('resident_name','Resident  Name','required|min_length[2]');
            $this->form_validation->set_rules('bill_month','Bill Month ','required'); 
            $this->form_validation->set_rules('apartment_id','Apartment ','required'); 
//            $this->form_validation->set_rules('address','Address','required'); 
      }	
                    
	function create(){   
            $inputs = $this->input->post();
//            echo '<pre>';            print_r($inputs); die;
            
            $wtr_id = get_autoincrement_no(WATERBILL_ENTRY); 
            if(isset($inputs['status'])){
                $inputs['status'] = 1;
            } else{
                $inputs['status'] = 0;
            }
                    
            
            $data['wb_entry'] = array(
                                'id' => $wtr_id,
                                'water_bill_tarrif_id' => $inputs['water_bill_tarrif_id'],
                                'bill_month' => strtotime($inputs['bill_month']), 
                                'apartment_id' => $inputs['apartment_id'], 
                                'bill_date_from' => strtotime($inputs['bill_date_from']), 
                                'bill_date_to' => strtotime( $inputs['bill_date_to']), 
                                'tarrif_days' => $inputs['tarrif_days'], 
                                'previous_reading' => $inputs['previous_reading'], 
                                'current_reading' => $inputs['current_reading'], 
                                'total_tarrif_id' => $inputs['total_tarrif_id'], 
                                'tot_calculated_amount' => $inputs['tot_calculated_amount'], 
                                'tot_units_consumed' => $inputs['tot_units_consumed'], 
                                'resident_usage_data' => json_encode($inputs['resident_usage']),  
                                'status' => $inputs['status'],
                                'added_on' => date('Y-m-d'),
                                'added_by' => $this->session->userdata(SYSTEM_CODE)['ID'],
                            );
                     
//            echo '<pre>';            print_r($data); die;
//            if(isset($inputs['tarrif_row']) && !empty($inputs['tarrif_row'])){
//                foreach ($inputs['tarrif_row'] as $tarrif_row){
//                    $data['tarrif_rows'][] = array(
//                                                    'tarrif_waterbill_id'=> $wtr_id,
//                                                    'order'=> $tarrif_row['order'],
//                                                    'units_from'=> $tarrif_row['units_from'],
//                                                    'units_to'=> $tarrif_row['units_to'],
//                                                    'usage_charge'=> $tarrif_row['usage_charge'],
//                                                    'service_charge'=> $tarrif_row['service_charge'],
//                                                    'status'=> 1,
//                                                    'deleted'=> 0,
//                                                );
//                }
//            }
            
		$add_stat = $this->Water_bill_entry_model->add_db($data);
                
		if($add_stat[0]){ 
                    //update log data
                    $new_data = $this->Water_bill_entry_model->get_single_row($add_stat[1]);
                    add_system_log(WATERBILL_ENTRY, $this->router->fetch_class(), __FUNCTION__, '', $new_data);
                    $this->session->set_flashdata('warn',RECORD_ADD);
                    redirect(base_url($this->router->fetch_class())); 
                }else{
                    $this->session->set_flashdata('warn',ERROR);
                    redirect(base_url($this->router->fetch_class()));
                } 
	}
	
	function update(){ 
            $inputs = $this->input->post();
//            echo '<pre>';            print_r($this->input->post()); die; 
            if(isset($inputs['status'])){
                $inputs['status'] = 1;
            } else{
                $inputs['status'] = 0;
            } 
                    
            
            $data['wb_entry'] = array( 
                                'water_bill_tarrif_id' => $inputs['water_bill_tarrif_id'],
                                'bill_month' => strtotime($inputs['bill_month']), 
                                'apartment_id' => $inputs['apartment_id'], 
                                'bill_date_from' => strtotime($inputs['bill_date_from']), 
                                'bill_date_to' => strtotime( $inputs['bill_date_to']), 
                                'tarrif_days' => $inputs['tarrif_days'], 
                                'previous_reading' => $inputs['previous_reading'], 
                                'current_reading' => $inputs['current_reading'], 
                                'total_tarrif_id' => $inputs['total_tarrif_id'], 
                                'tot_calculated_amount' => $inputs['tot_calculated_amount'], 
                                'tot_units_consumed' => $inputs['tot_units_consumed'], 
                                'resident_usage_data' => json_encode($inputs['resident_usage']),  
                                'status' => $inputs['status'],
                                'updated_on' => date('Y-m-d'),
                                'updated_by' => $this->session->userdata(SYSTEM_CODE)['ID'],
                            );
            
            //old data for log update
            $existing_data = $this->load_data($inputs['id']);

            $edit_stat = $this->Water_bill_entry_model->edit_db($inputs['id'],$data);
            
            if($edit_stat){
                //update log data
                $new_data = $this->Water_bill_entry_model->get_single_row($inputs['id']);
                add_system_log(WATERBILL_ENTRY, $this->router->fetch_class(), __FUNCTION__, $new_data, $existing_data);
                $this->session->set_flashdata('warn',RECORD_UPDATE);
                    
                redirect(base_url($this->router->fetch_class().'/edit/'.$inputs['id']));
            }else{
                $this->session->set_flashdata('warn',ERROR);
                redirect(base_url($this->router->fetch_class()));
            } 
	}	
        
        function remove(){
            $inputs = $this->input->post();
                                 $check_del = 0; 
//            $check_del = deletion_check(SUPPLIER_INVOICE, 'supplier_id', $inputs['id']); //has invoice
            
//            echo '<pre>';            print_r($check_del); die;
            
            if($check_del==0 || $check_del==0){  
                $data = array(
                                'deleted' => 1,
                                'deleted_on' => date('Y-m-d'),
                                'deleted_by' => $this->session->userdata(SYSTEM_CODE)['ID']
                             ); 

                $existing_data = $this->load_data($inputs['id']);
                $delete_stat = $this->Water_bill_entry_model->delete_db($inputs['id'],$data);

                if($delete_stat){
                    //update log data
                    add_system_log(WATERBILL_ENTRY, $this->router->fetch_class(), __FUNCTION__,$existing_data, '');
                    $this->session->set_flashdata('warn',RECORD_DELETE);
                    redirect(base_url($this->router->fetch_class()));
                }else{
                    $this->session->set_flashdata('warn',ERROR);
                    redirect(base_url($this->router->fetch_class()));
                }  
            }else{
                $this->session->set_flashdata('error','Can not delete! This Supplier has Invoice(s).');
                redirect(base_url($this->router->fetch_class().'/delete/'.$inputs['id']));
            }
	}
	
	
	function remove2(){
            $id  = $this->input->post('id'); 
            
            $existing_data = $this->Water_bill_entry_model->get_single_row($inputs['id']);
            if($this->Water_bill_entry_model->delete2_db($id)){
                //update log data
                add_system_log(HOTELS, $this->router->fetch_class(), __FUNCTION__, '', $existing_data);
                
                $this->session->set_flashdata('warn',RECORD_DELETE);
                redirect(base_url('company'));

            }else{
                $this->session->set_flashdata('warn',ERROR);
                redirect(base_url('company'));
            }  
	}
        
        function load_data($id=''){
            $data = array();
            if($id!=''){
                $data['entry_info'] = $this->Water_bill_entry_model->get_single_row($id);   
                if(empty($data['entry_info'])){
                    $this->session->set_flashdata('error','INVALID! Please use the System Navigation');
                    redirect(base_url($this->router->fetch_class()));
                }else{
                    $data['entry_info'] = $data['entry_info'][0];
                }
            }
            $data['apartment_list'] = get_dropdown_data(APARTMENTS, 'apartment_name', 'id');
            $data['tarrif_list'] = get_dropdown_data(TARRIF_WATERBILL, 'tarrif_name', 'id');
//            echo '<pre>';            print_r($data); die;
            return $data;	
	}	
        
        function search(){
		$search_data=array( 'tarrif_name' => $this->input->post('tarrif_name'), 
                                    );  
		$data_view['search_list'] = $this->Water_bill_entry_model->search_result($search_data);
                                        
		$this->load->view('water_bill_entry/search_water_bill_entry_result',$data_view);
	}
                
         function fl_ajax(){ 
            $func = $this->input->post('function_name');
            $param = $this->input->post();
            
            if(method_exists($this, $func)){ 
                (!empty($param))?$this->$func($param):$this->$func();
            }else{
                return false;
            }
        }
        function get_apartment_residents(){
            $inputs = $this->input->post();
            $resident_list = $this->Water_bill_entry_model->get_apartment_resident_list($inputs['apartment_id']);
            $resident_list1 = $resident_list;
            $entry_data = array();
            if($inputs['entry_id'] != ''){
                $resident_list1= array();
                $entry_data = json_decode($this->Water_bill_entry_model->get_single_row($inputs['entry_id'])[0]['resident_usage_data'], true);
            
                foreach ($resident_list as $key=> $resident){
                    foreach ($entry_data as $entry){
                        if($resident['id'] == $entry['resident_id']){
                            $resident_list1[$key] = $resident;
                            $resident_list1[$key]['previous_entry'] = $entry['previous_reading'];
                            $resident_list1[$key]['current_entry'] = $entry['current_reading'];
                            $resident_list1[$key]['consumed_units'] = $entry['consumed_units'];
                            $resident_list1[$key]['bill_total'] = $entry['bill_total'];
                        }
                    } 
                }
            }
//            echo '<pre>';            print_r($entry_data); die;
            echo json_encode($resident_list1);
            
        }
        function calculate_tarrif(){
            $inputs = $this->input->post();
            $this->load->model('Water_bill_tarrif_model');
            $calculation_tarrif =  $this->Water_bill_tarrif_model->get_single_row($inputs['tarrif_id']); 
            $calculation_tarrif_rows =  $this->Water_bill_tarrif_model->get_tarrif_rows($inputs['tarrif_id']); 
            
//            echo '<pre>';            print_r($inputs); 
//            echo '<pre>';            print_r($calculation_tarrif); die;
            $total_usage_charge =$total_service_charges = $total_charge = $vat_amount =  0;
            $tmp_units = $inputs['units'];
            $tarrif_days = (isset( $inputs['tarrif_days']))? $inputs['tarrif_days']:30; //defult 30 daus
            $tarrif_row_last_id = '';
            if(!empty($calculation_tarrif_rows)){
                
                //usage calculation
                foreach ($calculation_tarrif_rows as $key=>$tarrif_row){
//            echo '<pre>';            print_r($tarrif_row);
                    $prev_row_to = 0;
                    $prev_key = $key-1;
                    if(isset($calculation_tarrif_rows[$prev_key]))
                        $prev_row_to = $calculation_tarrif_rows[$prev_key]['units_to'];
                    
                    if($tmp_units>0){
                        $unit_dif = ($tarrif_row['units_to'] - $prev_row_to)*($tarrif_days/$calculation_tarrif[0]['tarrif_days']); //dif for days
                        if($tmp_units > $unit_dif && $unit_dif>0 && ($tarrif_row['units_to']!=0 ||  $tarrif_row['units_to']!=-1)){
                            $total_usage_charge += $unit_dif * $tarrif_row['usage_charge']; 
//                            echo '====='.$unit_dif.'x'.$tarrif_row['usage_charge'].'<br>';
                        }else{
                             $total_usage_charge += $tmp_units * $tarrif_row['usage_charge'];
//                            echo '====='.$tmp_units.'x'.$tarrif_row['usage_charge'].'<br>';
                        }
                            $tarrif_row_last_id = $key;
                            $tmp_units -= $unit_dif;
                    }
                }
                
                //service charge_calculation
                $total_service_charges += $calculation_tarrif_rows[$tarrif_row_last_id]['service_charge'] * ($tarrif_days/$calculation_tarrif[0]['tarrif_days']);
                $total_charge = $total_service_charges + $total_usage_charge;
//                echo '<br>service charge:'.$total_service_charges;
            }
            //vat calculation
            if(isset($calculation_tarrif[0]['vat_val'])){
                if($calculation_tarrif[0]['vat_calculation_method']==1){
//                    $vat_amount = 0.12*$total_charge;
                    $vat_amount = ($calculation_tarrif[0]['vat_val']/100)*$total_charge;
//                    echo '<br>VAT ('.$calculation_tarrif[0]['vat_val'].')charge:'.$vat_amount;
                    $total_charge += $vat_amount; 
                }
            }
            
//            echo '<br>=========='.$inputs['units'].'Units | usage: '.$total_usage_charge.' | total charge: '.$total_charge; die;
            echo $total_charge;
            
        }
                
        function get_dropdown_formodal($table='WATERBILL_ENTRY',$name='apartment_name',$id="id"){ 
             echo json_encode(get_dropdown_data(WATERBILL_ENTRY, $name, $id)); 
        }
             
        function test(){
            echo '<pre>';            print_r($this->router->class); die;
//            $this->load->model('Water_bill_entry_model');
//            $data = $this->Water_bill_entry_model->get_single_row(1);
            echo '<pre>' ; print_r(get_dropdown_data(HOTELS,'hotel_name','id','Hotel'));die;
//            log_message('error', 'Some variable did not contain a value.');
        }
        
        function print_entry($id){ 
//            $this->input->post() = 'aa';
            $entry_data = $this->load_data($id);   
            $entry_info = $entry_data['entry_info'];
            $resident_usage_data = json_decode($entry_data['entry_info']['resident_usage_data'],true);
//            echo '<pre>';            print_r($resident_usage_data); die;  
            
            $this->load->library('Pdf');  
             
            // create new PDF document
            $pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->fl_header='header_jewel';//invice bg
            $pdf->fl_header_title='Entry Note';//invice bg
            $pdf->fl_header_title_RTOP='Water Bill';//invice bg
            
            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Fahry Lafir');
            $pdf->SetTitle('PDF AM Invoice');
            $pdf->SetSubject('FL Invoice');
            $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
            
            // set default header data
            $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

            // set header and footer fonts
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, 50, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
                    
            // set font
            $fontname = TCPDF_FONTS::addTTFfont('storage/fonts/Lato-Regular.ttf', 'TrueTypeUnicode', '', 96);
            $pdf->SetFont($fontname, 'I', 9);
        
        
            $pdf->AddPage();   
            $pdf->SetTextColor(32,32,32);     
            
            $html = '<table border="0">
                        <tr>
                            <td><b>Month: '.(($entry_info['bill_month']>0)?date('F-Y',$entry_info['bill_month']):'').' </b></td>
                            <td align="center">Bill Date from:'.(($entry_info['bill_date_from']>0)?date(SYS_DATE_FORMAT,$entry_info['bill_date_from']):'').'</td>
                            <td align="right">Total Units Consumed: '.$entry_info['tot_units_consumed'].'</td>
                        </tr> 
                        <tr>
                            <td>Total Bill Tarrif: '.$entry_info['total_tarrif_name'].'</td>
                            <td align="center">Bill Date to : '.(($entry_info['bill_date_to']>0)?date(SYS_DATE_FORMAT,$entry_info['bill_date_to']):'').'</td>
                            <td align="RIGHT">Total Bill : '. number_format($entry_info['tot_calculated_amount'],2).'</td>
                        </tr> 
                        <tr>
                            <td>Apartment/Building: '.$entry_info['apartment_name'].'</td>
                            <td align="center">Days: '.$entry_info['tarrif_days'].'</td>
                            <td align="right">Printed on : '.date(SYS_DATE_FORMAT).'</td>
                        </tr> 
                        <tr><td style="line-height:30px;" colspan="3"></td></tr>
                        <tr><td style="line-height:30px;" colspan="3">Tarrif for Aprtment Bill: '.$entry_info['tarrif_name'].' </td></tr>
                    </table> ';
            $i=1; 
            
            $html .='
                        <table id="example1" class="table-line" border="0">
                            <thead> 
                                <tr class="colored_bg" style="background-color:#E0E0E0;">
                                    <th width="4%" >#</th>
                                    <th width="16%"  style="text-align: left;">Aprtment No</th> 
                                    <th width="20%"  style="text-align: left;">Resident </th>
                                    <th width="15%"  style="text-align: right;">Previous Reading</th> 
                                    <th width="15%"  style="text-align: right;">Current Reading</th> 
                                    <th width="15%" style="text-align: right;">Consumed Units</th> 
                                    <th width="15%" style="text-align: right;">Calculated Bill</th> 
                                </tr> 
                            </thead>
                        <tbody>';
            
            if(isset($resident_usage_data) && count($resident_usage_data)>0){
                
                $i=1;
                $tot_units = $tot_units_2 = $tot_amount = 0; 
                foreach ($resident_usage_data as $row){
                    $tot_units += $row['consumed_units'];
                    $tot_amount += $row['bill_total'];
                    $html .= '<tr>
                                <td width="4%" style="text-align: left;">'.$i.'</td> 
                                <td width="16%" style="text-align: left;">'.$row['resident_code'].'</td>  
                                <td width="20%" style="text-align: left;">'.$row['resident_name'].'</td>  
                                <td width="15%" style="text-align: right;">'.number_format($row['previous_reading'],2).'</td>  
                                <td width="15%" style="text-align: right;">'.number_format($row['current_reading'],2).'</td>  
                                <td width="15%" style="text-align: right;">'.number_format($row['consumed_units'],2).'</td>  
                                <td width="15%" style="text-align: right;">'.number_format($row['bill_total'],2).'</td>  
                                 
                            </tr> ';
                    $i++;
                } 
                
            $html .=    '<tr> 
                                <td colspan="5" style="text-align: right;"><b>TOTAL:</b></td>
                                <td style="text-align: right;"><b> '.number_format($tot_units,2).'</b></td>
                                <td style="text-align: right;"><b> '.number_format($tot_amount,2).'</b></td>
                            </tr>'; 
            }
//            echo '<pre>';            print_r($rows_desc); die;
            
            $html .= '</tbody> 
                    </table>   ';      
            
            
            $html .= '
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
                        line-height: 20px;
                    }
                    </style>';
            $pdf->writeHTMLCell(190,'',10,'',$html);
            
            $pdf->SetFont('times', '', 12.5, '', false);
            $pdf->SetTextColor(255,125,125);            
            // force print dialog
            $js = 'this.print();';
//            $js = 'print(true);';
            // set javascript
//            $pdf->IncludeJS($js);
            $pdf->Output('Issue_note_'.'.pdf', 'I');
//            $pdf->Output('Issue_note_'.$gi_info['id'].'.pdf', 'I');
                
        } 
        
                    
}
