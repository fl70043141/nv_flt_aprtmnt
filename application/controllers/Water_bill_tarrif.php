<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Water_bill_tarrif extends CI_Controller {

	
        function __construct() {
            parent::__construct();
            $this->load->model('Water_bill_tarrif_model'); 
        }

        public function index(){
            $this->view_search();
	}
        
        function view_search($datas=''){
            $data['search_list'] = $this->Water_bill_tarrif_model->search_result();
            $data['main_content']='water_bill_tarrif/search_water_bill_tarrif';  
            $this->load->view('includes/template',$data);
	}
        
	function add(){ 
            $data  			= $this->load_data(); 
            $data['action']		= 'Add';
            $data['main_content']='water_bill_tarrif/manage_water_bill_tarrif'; 
            $this->load->view('includes/template',$data);
	}
	
	function edit($id){ 
            $data  			= $this->load_data($id); 
            $data['action']		= 'Edit';
            $data['main_content']='water_bill_tarrif/manage_water_bill_tarrif'; 
            $this->load->view('includes/template',$data);
	}
	
	function delete($id){ 
            $data  			= $this->load_data($id);
            $data['action']		= 'Delete';
            $data['main_content']='water_bill_tarrif/manage_water_bill_tarrif'; 
            $this->load->view('includes/template',$data);
	}
	
	function view($id){ 
            $data  			= $this->load_data($id);
            $data['action']		= 'View';
            $data['main_content']='water_bill_tarrif/manage_water_bill_tarrif'; 
            $data['user_role_list'] = get_dropdown_data(USER_ROLE,'user_role','id');
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
            $this->form_validation->set_rules('tarrif_days','Mumber of Days Days ','required|min_length[2]'); 
//            $this->form_validation->set_rules('address','Address','required'); 
      }	
                    
	function create(){   
            $inputs = $this->input->post();
            
            $wtr_id = get_autoincrement_no(TARRIF_WATERBILL); 
            if(isset($inputs['status'])){
                $inputs['status'] = 1;
            } else{
                $inputs['status'] = 0;
            }
                    
            
            $data['tarrif_tbl'] = array(
                                'id' => $wtr_id,
                                'tarrif_name' => $inputs['tarrif_name'],
                                'tarrif_days' => $inputs['tarrif_days'], 
                                'vat_val' => $inputs['vat_val'], 
                                'vat_calculation_method' => 1,  // percentage hardcoded
                                'status' => $inputs['status'],
                                'added_on' => date('Y-m-d'),
                                'added_by' => $this->session->userdata(SYSTEM_CODE)['ID'],
                            );
                     
            if(isset($inputs['tarrif_row']) && !empty($inputs['tarrif_row'])){
                foreach ($inputs['tarrif_row'] as $tarrif_row){
                    $data['tarrif_rows'][] = array(
                                                    'tarrif_waterbill_id'=> $wtr_id,
                                                    'order'=> $tarrif_row['order'],
                                                    'units_from'=> $tarrif_row['units_from'],
                                                    'units_to'=> $tarrif_row['units_to'],
                                                    'usage_charge'=> $tarrif_row['usage_charge'],
                                                    'service_charge'=> $tarrif_row['service_charge'],
                                                    'status'=> 1,
                                                    'deleted'=> 0,
                                                );
                }
            }
            
		$add_stat = $this->Water_bill_tarrif_model->add_db($data);
                
		if($add_stat[0]){ 
                    //update log data
                    $new_data = $this->Water_bill_tarrif_model->get_single_row($add_stat[1]);
                    add_system_log(TARRIF_WATERBILL, $this->router->fetch_class(), __FUNCTION__, '', $new_data);
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
            $data['tarrif_tbl'] = array( 
                                        'tarrif_name' => $inputs['tarrif_name'],
                                        'tarrif_days' => $inputs['tarrif_days'], 
                                        'vat_val' => $inputs['vat_val'], 
                                        'vat_calculation_method' => 1,  // percentage hardcoded
                                        'status' => $inputs['status'],
                                        'updated_on' => date('Y-m-d'),
                                        'updated_by' => $this->session->userdata(SYSTEM_CODE)['ID'],
                                         ); 
            
            if(isset($inputs['tarrif_row']) && !empty($inputs['tarrif_row'])){
                foreach ($inputs['tarrif_row'] as $tarrif_row){
                    $data['tarrif_rows'][] = array(
                                                    'tarrif_waterbill_id'=> $inputs['id'],
                                                    'order'=> $tarrif_row['order'],
                                                    'units_from'=> $tarrif_row['units_from'],
                                                    'units_to'=> $tarrif_row['units_to'],
                                                    'usage_charge'=> $tarrif_row['usage_charge'],
                                                    'service_charge'=> $tarrif_row['service_charge'],
                                                    'status'=> 1,
                                                    'deleted'=> 0,
                                                );
                }
            }
            //old data for log update
            $existing_data = $this->load_data($inputs['id']);

            $edit_stat = $this->Water_bill_tarrif_model->edit_db($inputs['id'],$data);
            
            if($edit_stat){
                //update log data
                $new_data = $this->Water_bill_tarrif_model->get_single_row($inputs['id']);
                add_system_log(TARRIF_WATERBILL, $this->router->fetch_class(), __FUNCTION__, $new_data, $existing_data);
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
                $delete_stat = $this->Water_bill_tarrif_model->delete_db($inputs['id'],$data);

                if($delete_stat){
                    //update log data
                    add_system_log(TARRIF_WATERBILL, $this->router->fetch_class(), __FUNCTION__,$existing_data, '');
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
            
            $existing_data = $this->Water_bill_tarrif_model->get_single_row($inputs['id']);
            if($this->Water_bill_tarrif_model->delete2_db($id)){
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
                $data['tarrif_info'] = $this->Water_bill_tarrif_model->get_single_row($id); 
                $data['tarrif_row_info'] = $this->Water_bill_tarrif_model->get_tarrif_rows($id); 
                if(empty($data['tarrif_info'])){
                    $this->session->set_flashdata('error','INVALID! Please use the System Navigation');
                    redirect(base_url($this->router->fetch_class()));
                }
            }
            
            return $data;	
	}	
        
        function search(){
		$search_data=array( 'tarrif_name' => $this->input->post('tarrif_name'), 
                                    );  
		$data_view['search_list'] = $this->Water_bill_tarrif_model->search_result($search_data);
                                        
		$this->load->view('water_bill_tarrif/search_water_bill_tarrif_result',$data_view);
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
        function get_dropdown_formodal($table='TARRIF_WATERBILL',$name='apartment_name',$id="id"){ 
             echo json_encode(get_dropdown_data(TARRIF_WATERBILL, $name, $id)); 
        }
             
        function test(){
            echo '<pre>';            print_r($this->router->class); die;
//            $this->load->model('Water_bill_tarrif_model');
//            $data = $this->Water_bill_tarrif_model->get_single_row(1);
            echo '<pre>' ; print_r(get_dropdown_data(HOTELS,'hotel_name','id','Hotel'));die;
//            log_message('error', 'Some variable did not contain a value.');
        }
        
                    
}
