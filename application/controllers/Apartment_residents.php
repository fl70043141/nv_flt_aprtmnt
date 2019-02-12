<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Apartment_residents extends CI_Controller {

	
        function __construct() {
            parent::__construct();
            $this->load->model('Apartment_residents_model'); 
        }

        public function index(){
            $this->view_search();
	}
        
        function view_search($datas=''){
            $data['search_list'] = $this->Apartment_residents_model->search_result();
            $data['main_content']='apartment_residents/search_apartment_residents'; 
            $data['apartment_list'] = get_dropdown_data(APARTMENTS,'apartment_name','id','No Apartment');
            $this->load->view('includes/template',$data);
	}
        
	function add(){ 
            $data['action']		= 'Add';
            $data['main_content']='apartment_residents/manage_apartment_residents'; 
            $data['currency_list'] = get_dropdown_data(CURRENCY,'code','code',''); 
            $data['country_list'] = get_dropdown_data(COUNTRY_LIST,'country_name','country_code',''); 
            $data['country_state_list'] = get_dropdown_data(COUNTRY_STATES,'state_name','id',''); 
            $data['country_district_list'] = get_dropdown_data(COUNTRY_DISTRICTS,'district_name','id',''); 
            $data['apartment_list'] = get_dropdown_data(APARTMENTS,'apartment_name','id',''); 
            $this->load->view('includes/template',$data);
	}
	
	function edit($id){ 
            $data  			= $this->load_data($id); 
            $data['action']		= 'Edit';
            $data['main_content']='apartment_residents/manage_apartment_residents'; 
            $this->load->view('includes/template',$data);
	}
	
	function delete($id){ 
            $data  			= $this->load_data($id);
            $data['action']		= 'Delete';
            $data['main_content']='apartment_residents/manage_apartment_residents'; 
            $this->load->view('includes/template',$data);
	}
	
	function view($id){ 
            $data  			= $this->load_data($id);
            $data['action']		= 'View';
            $data['main_content']='apartment_residents/manage_apartment_residents'; 
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

            $this->form_validation->set_rules('resident_name','Resident  Name','required|min_length[2]');
            $this->form_validation->set_rules('resident_code','Apartment No','required|min_length[2]'); 
//            $this->form_validation->set_rules('address','Address','required');
            $this->form_validation->set_rules('apartment_id','Building / Apartment','required'); 
            $this->form_validation->set_rules('phone','phone','min_length[10]');  
      }	
                    
	function create(){   
            $inputs = $this->input->post();
            $ar_id = get_autoincrement_no(APARTMENT_RESIDENT);
            $apr_code = ($inputs['resident_code']=='')?gen_id('FR', APARTMENT_RESIDENT, 'id',4):$inputs['resident_code'];
            if(isset($inputs['status'])){
                $inputs['status'] = 1;
            } else{
                $inputs['status'] = 0;
            }
                    
            
            $data = array(
                            'id' => $ar_id,
                            'resident_name' => $inputs['resident_name'],
                            'resident_code' => $apr_code, 
                            'apartment_id' => $inputs['apartment_id'], 
                            'description' => $inputs['description'], 
                            'phone' => $inputs['phone'], 
                            'email' => $inputs['email'], 
                            'status' => $inputs['status'],
                            'added_on' => date('Y-m-d'),
                            'added_by' => $this->session->userdata(SYSTEM_CODE)['ID'],
                        );
                     
		$add_stat = $this->Apartment_residents_model->add_db($data);
                
		if($add_stat[0]){ 
                    //update log data
                    $new_data = $this->Apartment_residents_model->get_single_row($add_stat[1]);
                    add_system_log(APARTMENT_RESIDENT, $this->router->fetch_class(), __FUNCTION__, '', $new_data);
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
            $data = array(
                            
                            'resident_name' => $inputs['resident_name'],
                            'resident_code' => $inputs['resident_code'], 
                            'apartment_id' => $inputs['apartment_id'], 
                            'description' => $inputs['description'], 
                            'phone' => $inputs['phone'], 
                            'email' => $inputs['email'], 
                            'status' => $inputs['status'],
                            'updated_on' => date('Y-m-d'),
                            'updated_by' => $this->session->userdata(SYSTEM_CODE)['ID'],
                        ); 
            
//            echo '<pre>'; print_r($data); die;
            //old data for log update
            $existing_data = $this->Apartment_residents_model->get_single_row($inputs['id']);

            $edit_stat = $this->Apartment_residents_model->edit_db($inputs['id'],$data);
            
            if($edit_stat){
                //update log data
                $new_data = $this->Apartment_residents_model->get_single_row($inputs['id']);
                add_system_log(APARTMENT_RESIDENT, $this->router->fetch_class(), __FUNCTION__, $new_data, $existing_data);
                $this->session->set_flashdata('warn',RECORD_UPDATE);
                    
                redirect(base_url($this->router->fetch_class().'/edit/'.$inputs['id']));
            }else{
                $this->session->set_flashdata('warn',ERROR);
                redirect(base_url($this->router->fetch_class()));
            } 
	}	
        
        function remove(){
            $inputs = $this->input->post();
                                  
//            $check_del = deletion_check(SUPPLIER_INVOICE, 'supplier_id', $inputs['id']); //has invoice
            
//            echo '<pre>';            print_r($check_del); die;
            
            if($check_del==0 || $check_del==0){  
                $data = array(
                                'deleted' => 1,
                                'deleted_on' => date('Y-m-d'),
                                'deleted_by' => $this->session->userdata(SYSTEM_CODE)['ID']
                             ); 

                $existing_data = $this->Apartment_residents_model->get_single_row($inputs['id']);
                $delete_stat = $this->Apartment_residents_model->delete_db($inputs['id'],$data);

                if($delete_stat){
                    //update log data
                    add_system_log(APARTMENT_RESIDENT, $this->router->fetch_class(), __FUNCTION__,$existing_data, '');
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
            
            $existing_data = $this->Apartment_residents_model->get_single_row($inputs['id']);
            if($this->Apartment_residents_model->delete2_db($id)){
                //update log data
                add_system_log(HOTELS, $this->router->fetch_class(), __FUNCTION__, '', $existing_data);
                
                $this->session->set_flashdata('warn',RECORD_DELETE);
                redirect(base_url('company'));

            }else{
                $this->session->set_flashdata('warn',ERROR);
                redirect(base_url('company'));
            }  
	}
        
        function load_data($id){
            
            $data['user_data'] = $this->Apartment_residents_model->get_single_row($id); 
            if(empty($data['user_data'])){
                $this->session->set_flashdata('error','INVALID! Please use the System Navigation');
                redirect(base_url($this->router->fetch_class()));
            }
            
            $data['currency_list'] = get_dropdown_data(CURRENCY,'code','code',''); 
            $data['country_list'] = get_dropdown_data(COUNTRY_LIST,'country_name','country_code','');
            $data['country_state_list'] = get_dropdown_data(COUNTRY_STATES,'state_name','id',''); 
            $data['country_district_list'] = get_dropdown_data(COUNTRY_DISTRICTS,'district_name','id',''); 
            $data['apartment_list'] = get_dropdown_data(APARTMENTS,'apartment_name','id',''); 
            return $data;	
	}	
        
        function search(){
		$search_data=array( 'resident_name' => $this->input->post('resident_name'), 
                                    'resident_code' => $this->input->post('resident_code'),      
                                    'apartment_id' => $this->input->post('apartment_id'),      
                                    'phone' => $this->input->post('phone'),      
                                    );  
		$data_view['search_list'] = $this->Apartment_residents_model->search_result($search_data);
                                        
		$this->load->view('apartment_residents/search_apartment_residents_result',$data_view);
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
        function get_dropdown_formodal($table='APARTMENT_RESIDENT',$name='apartment_name',$id="id"){ 
             echo json_encode(get_dropdown_data(APARTMENT_RESIDENT, $name, $id)); 
        }
        function add_suuplier_quick($data1){
            unset($data1['function_name']);
            $supp_ref = gen_id('SUP', APARTMENT_RESIDENT, 'id',2,0);
            $data2 = array(
                            'apartment_code' => $supp_ref, 
                            'status' => 1, 
                            'credit_limit' => 0, 
                            'added_on' => date('Y-m-d'),
                            'added_by' => $this->session->userdata(SYSTEM_CODE)['ID'],
                            );
                $data = array_merge($data1,$data2);
		$add_stat = $this->Apartment_residents_model->add_db($data);
                
		if($add_stat[0]){ 
                    //update log data
                    $new_data = $this->Apartment_residents_model->get_single_row($add_stat[1]);
                    add_system_log(APARTMENT_RESIDENT, $this->router->fetch_class(), __FUNCTION__, '', $new_data); 
                }
                echo $add_stat[1];
        }
             
        function test(){
            echo '<pre>';            print_r($this->router->class); die;
//            $this->load->model('Apartment_residents_model');
//            $data = $this->Apartment_residents_model->get_single_row(1);
            echo '<pre>' ; print_r(get_dropdown_data(HOTELS,'hotel_name','id','Hotel'));die;
//            log_message('error', 'Some variable did not contain a value.');
        }
        
                    
}
