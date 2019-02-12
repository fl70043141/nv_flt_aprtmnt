<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quick_entries extends CI_Controller {

        function __construct() {
            parent::__construct();
            $this->load->model('Quick_entries_model'); 
        }

        public function index(){
            $this->view_search();
	}
        
        function view_search($datas=''){
//            $this->add();
            $data['search_list'] = $this->Quick_entries_model->search_result();
            $data['main_content']='gl_quick_entries/search_gl_quick_entries'; 
            $data['quick_entry_acc_list'] = get_dropdown_data(GL_QUICK_ENTRY_ACC,'account_name','id','Account '); 
            
            $this->load->view('includes/template',$data);
	}
        
	function add(){ 
            $data  			= $this->load_data(); 
            $data['action']		= 'Add';
            $data['main_content']='gl_quick_entries/manage_gl_quick_entries';    
            $this->load->view('includes/template',$data);
	}
	
	function edit($id){ 
            $data  			= $this->load_data($id); 
            $data['action']		= 'Edit';
            $data['main_content']='vehicle_rates/view_maintenecne'; 
            $this->load->view('includes/template',$data);
	}
	
	function delete($id){ 
            $data  			= $this->load_data($id);
            $data['action']		= 'Delete';
            $data['main_content']='gl_quick_entries/view_maintenecne';  
            $this->load->view('includes/template',$data);
	}
	
	function view($id){ 
            $data  			= $this->load_data($id);
            $data['action']		= 'View';
            $data['main_content']='gl_quick_entries/view_maintenecne';  
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

            $this->form_validation->set_rules('entry_date','Date','required'); 
//            $this->form_validation->set_rules('vehicle_number','Vehicle Number','required'); 
      }	 
        
	function create(){  
            
            $inputs = $this->input->post(); 
            $this->load->model('GL_quick_entry_accounts_model');
            $entry_id = get_autoincrement_no(GL_QUICK_ENTRY);
            $maint_id = get_autoincrement_no(GL_MENTEN_ENTRY);
            $cur_det =  get_currency_for_code($inputs['currency_code']);
            $data = array();
            $data['main_tbl'] = array(
                                                'id' => $maint_id,
                                                'bill_month' => strtotime($inputs['bill_month']),
                                                'status' => 1,   
                                                'added_on' => date('Y-m-d'),
                                                'added_by' => $this->session->userdata(SYSTEM_CODE)['ID'],
                                            );
            $tot_entries = 0;
            if(!empty($inputs['inv_items'])){
                foreach ($inputs['inv_items'] as $entry){ 
                    $tot_entries += $entry['amount'];
                    $data['entry_tbl'][] = array(
                                                'id' => $entry_id,
                                                'maintenance_id' => $maint_id,
                                                'quick_entry_account_id' => $entry['quick_entry_account_id'],
                                                'amount' => $entry['amount'],
                                                'currency_code' => $inputs['currency_code'], 
                                                'currency_value' => $cur_det['value'], 
                                                'entry_date' => strtotime($entry['entry_date']),  
                                                'fiscal_year_id' => 1,  
                                                'status' => 1,   
                                                'added_on' => date('Y-m-d'),
                                                'added_by' => $this->session->userdata(SYSTEM_CODE)['ID'],
                                            );
                    
                    $qe_acc = $this->GL_quick_entry_accounts_model->get_single_row($entry['quick_entry_account_id'])[0];
                    //GL TRANS
                    $data['gl_trans'][] = array(
                                                'person_type' => 40,
                                                'person_id' =>0,
                                                'trans_ref' => $entry_id,
                                                'trans_date' => strtotime("now"),
                                                'account' => $qe_acc['debit_gl_id'], 
                                                'account_code' => $qe_acc['debit_gl_code'], 
                                                'memo' => 'Quick_entry',
                                                'amount' => ($entry['amount']),
                                                'currency_code' => $inputs['currency_code'], 
                                                'currency_value' => $cur_det['value'], 
                                                'fiscal_year'=> $this->session->userdata(SYSTEM_CODE)['active_fiscal_year_id'],
                                                'status' => 1,
                                        );
                    $data['gl_trans'][] = array(
                                                'person_type' => 40,
                                                'person_id' =>0,
                                                'trans_ref' => $entry_id,
                                                'trans_date' => strtotime("now"),
                                                'account' => $qe_acc['credit_gl_id'], 
                                                'account_code' => $qe_acc['credit_gl_code'], 
                                                'memo' => 'Quick_entry',
                                                'amount' => (-$entry['amount']),
                                                'currency_code' => $inputs['currency_code'], 
                                                'currency_value' => $cur_det['value'], 
                                                'fiscal_year'=> $this->session->userdata(SYSTEM_CODE)['active_fiscal_year_id'],
                                                'status' => 1,
                                        );
                    $entry_id++;
                }
            }
             $data['main_tbl']['total_entry_amount'] = $tot_entries;
            
            
//            echo '<pre>';            print_r($data); die; 
                    
		$add_stat = $this->Quick_entries_model->add_db($data);
                
		if($add_stat[0]){ 
                    //update log data
                    $new_data = $this->Quick_entries_model->get_single_row($add_stat[1]);
                    add_system_log(GL_MENTEN_ENTRY, $this->router->fetch_class(), __FUNCTION__, '', $new_data);
                    $this->session->set_flashdata('warn',RECORD_ADD);
                    redirect(base_url($this->router->fetch_class().'/view/'.$quote_id)); 
                }else{
                    $this->session->set_flashdata('warn',ERROR);
                    redirect(base_url($this->router->fetch_class()));
                } 
	}
	
	function update(){ 
            $inputs = $this->input->post();
            $agent_id = $inputs['id']; 
            if(isset($inputs['status'])){
                $inputs['status'] = 1;
            } else{
                $inputs['status'] = 0;
            }
            $data = array(
                            'vehicle_id' => $inputs['vehicle_id'], 
                            'description' => $inputs['description'], 
                            'rate_amount' => $inputs['rate_amount'], 
                            'owner_rate_plan' => $inputs['owner_rate_plan'], 
                            'owner_rate' => $inputs['owner_rate'], 
                            'km_limit_day' => $inputs['km_limit_day'], 
                            'extra_km_rate' => $inputs['extra_km_rate'], 
                            'extra_time_rate' => $inputs['extra_time_rate'], 
                            'status' => $inputs['status'],
                            'updated_on' => date('Y-m-d'),
                            'updated_by' => $this->session->userdata(SYSTEM_CODE)['ID'],
                        );
            
//            echo '<pre>'; print_r($data); die;
            //old data for log update
            $existing_data = $this->Quick_entries_model->get_single_row($inputs['id']);

            $edit_stat = $this->Quick_entries_model->edit_db($inputs['id'],$data);
            
            if($edit_stat){
                //update log data
                $new_data = $this->Quick_entries_model->get_single_row($inputs['id']);
                add_system_log(VEHICLE_RATES, $this->router->fetch_class(), __FUNCTION__, $new_data, $existing_data);
                $this->session->set_flashdata('warn',RECORD_UPDATE);
                    
                redirect(base_url($this->router->fetch_class().'/edit/'.$inputs['id']));
            }else{
                $this->session->set_flashdata('warn',ERROR);
                redirect(base_url($this->router->fetch_class()));
            } 
	}	
        
        function remove(){
            $inputs = $this->input->post();
                    
            $data = array(
                        'deleted' => 1,
                        'deleted_on' => date('Y-m-d'),
                        'deleted_by' => $this->session->userdata(SYSTEM_CODE)['ID']
                     ); 
                    
            $existing_data = $this->Quick_entries_model->get_single_row2($inputs['id']);
            $delete_stat = $this->Quick_entries_model->delete_db($inputs['id'],$data);
                    
            if($delete_stat){
                //update log data
                add_system_log(GL_QUICK_ENTRY, $this->router->fetch_class(), __FUNCTION__,$existing_data, '');
                $this->session->set_flashdata('warn',RECORD_DELETE);
                redirect(base_url($this->router->fetch_class()));
            }else{
                $this->session->set_flashdata('warn',ERROR);
                redirect(base_url($this->router->fetch_class()));
            }  
	}
	
	
	function remove2(){
            $id  = $this->input->post('id'); 
            
            $existing_data = $this->Quick_entries_model->get_single_row($inputs['id']);
            if($this->Quick_entries_model->delete2_db($id)){
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
                $data['entry_info'] = $this->Quick_entries_model->get_single_row2($id); 
                $data['entry_desc'] = $this->Quick_entries_model->get_entry_desc($id); 
                if(empty($data['entry_info'])){
                    $this->session->set_flashdata('error','INVALID! Please use the System Navigation');
                    redirect(base_url($this->router->fetch_class()));
                }
            }
            
            $data['quick_entry_acc_list'] = get_dropdown_data(GL_QUICK_ENTRY_ACC,'account_name','id','Account '); 
            $data['currency_list'] = get_dropdown_data(CURRENCY,'code','code','Currency');
                    
//            echo '<pre>';            print_r($data); die;
            return $data;
	}	
        
        function search(){ 
            $input = $this->input->post();
            $search_data=array( 
                                'from_date' => strtotime($this->input->post('from_date')),
                                'to_date' => strtotime($this->input->post('to_date')), 
                                'quick_entry_account_id' => $this->input->post('quick_entry_account_id'), 
                                ); 
//            echo '<pre>';            print_r( $search_data); die;
            $entries['search_list'] = $this->Quick_entries_model->search_result2($search_data); 
//            echo '<pre>';            print_r($entries); die;
            $this->load->view('gl_quick_entries/search_gl_quick_entries_result',$entries);
	}
        
        function get_single_item(){
            $inputs = $this->input->post(); 
            $data = $this->Quick_entries_model->get_single_item($inputs['item_code'],$inputs['sales_type_id']); 
            echo json_encode($data);
        }
        function test(){
//            echo '<pre>';            print_r($this->router->class); die;
            
//            $this->load->view('invoices/sales_invoices');
            $data = $this->Quick_entries_model->get_single_item(1002,15);
//            $enty_info = $data['entry_info'];
//            echo '<pre>' ; print_r($data);die;
//            log_message('error', 'Some variable did not contain a value.');
        }
        
        
        function print_entry($id){ 
//            $this->input->post() = 'aa';
            $entry_data = $this->load_data($id);    
            
            $entry_info = $entry_data['entry_info'];
            $entry_desc = $entry_data['entry_desc'];
            $this->load->library('Pdf');  
             
            // create new PDF document
            $pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->fl_header='header_jewel';//invice bg
            $pdf->fl_header_title='Entry Note';//invice bg
            $pdf->fl_header_title_RTOP='Maintenance Details';//invice bg
            
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
                            <td></td>
                            <td></td>
                        </tr> 
                    </table> ';
            $i=1; 
            
            $html .='
                        <table id="example1" class="table-line" border="0">
                            <thead> 
                                <tr class="colored_bg" style="background-color:#E0E0E0;">
                                    <th width="6%" >#</th>
                                    <th width="50%"  style="text-align: left;">Description</th> 
                                    <th width="22%"  style="text-align: right;">Amount </th> 
                                    <th width="22%"  style="text-align: center;">Date Paid</th> 
                                </tr> 
                            </thead>
                        <tbody>';
            
            if(isset($entry_desc) && count($entry_desc)>0){
                
                 $i=1;
                    $tot_units = $tot_units_2 = $tot_amount = 0; 
                    foreach ($entry_desc as $row){ 
                        $tot_amount += $row['amount'];
//            echo '<pre>';            print_r($row);  
                        $html .=  '<tr>
                                   <td width="6%"  style="text-align: center;">'.$i.'</td>   
                                   <td width="50%"  style="text-align: left;">'.$row['account_name'].'</td>     
                                   <td width="22%" style="text-align: right;">'.number_format($row['amount'],2).'</td>    
                                   <td width="22%" style="text-align: center;">'. date(SYS_DATE_FORMAT,$row['entry_date']).'</td>     
                               </tr> ';  
                        $i++;
                    }   
                
            $html .=    '<tr> 
                                <td colspan="2" style="text-align: right;"><b>TOTAL:</b></td>
                                <td style="text-align: right;"><b> '.number_format($tot_amount,2).'</b></td>
                                <td style="text-align: right;"><b></b></td>
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
                        line-height: 30px;
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
