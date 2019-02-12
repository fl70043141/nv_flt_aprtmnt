<?php 

class Apartment_residents_model extends CI_Model
{
	function __construct(){	
            parent::__construct(); 
 	}
	 
        public function search_result($data=''){ 
            $this->db->select('a.*');
            $this->db->select('(select apartment_name from '.APARTMENTS.' where id = a.apartment_id) as apartment_name');
            $this->db->from(APARTMENT_RESIDENT.' a');  
            $this->db->where('a.deleted',0);
            if(isset($data['resident_name']) && $data['resident_name']!='') $this->db->like('a.resident_name', $data['resident_name']);  
            if(isset($data['resident_code']) && $data['resident_code']!='') $this->db->like('a.resident_code', $data['resident_code']);  
            if(isset($data['apartment_id']) && $data['apartment_id']!='') $this->db->where('a.apartment_id', $data['apartment_id']);  
            if(isset($data['phone']) && $data['phone']!='') $this->db->like('a.phone', $data['phone']);  
            
            $result = $this->db->get()->result_array();  
            return $result;
	}
	
         public function get_single_row($id){ 
            $this->db->select('*');
            $this->db->from(APARTMENT_RESIDENT); 
            $this->db->where('id',$id);
            $this->db->where('deleted',0);
            $result = $this->db->get()->result_array();  
            return $result;
	}
                        
        public function add_db($data){   
//            echo '<pre>';            print_r($data); die;
                $this->db->trans_start();
		$this->db->insert(APARTMENT_RESIDENT, $data); 
                $insert_id =  $this->db->insert_id();
		$status[0]=$this->db->trans_complete();
		$status[1]=$insert_id; 
		return $status;
	}
        
        public function edit_db($id,$data){
//            echo '<pre>'; print_r($data); die;
		$this->db->trans_start();
                
		$this->db->where('id', $id);
                $this->db->where('deleted',0);
		$this->db->update(APARTMENT_RESIDENT, $data);
                        
		$status=$this->db->trans_complete();
		return $status;
	}
        
        public function delete_db($id,$data){ 
		$this->db->trans_start();
		$this->db->where('id', $id); 
                $this->db->where('deleted',0);
		$this->db->update(APARTMENT_RESIDENT, $data);
		$status=$this->db->trans_complete();
		return $status;
	}
        
        function delete_db2($id){
                $this->db->trans_start();
                $this->db->delete(APARTMENT_RESIDENT, array('id' => $id));     
                $status = $this->db->trans_complete();
                return $status;	
	} 
        
 
}
?>