<?php 

class Water_bill_entry_model extends CI_Model
{
	function __construct(){	
            parent::__construct(); 
 	}
	 
        public function search_result($data=''){ 
            $this->db->select('wbe.*');
            $this->db->select('(select apartment_name from '.APARTMENTS.' where id = wbe.apartment_id) as apartment_name');
            $this->db->from(WATERBILL_ENTRY.' wbe');  
            $this->db->where('wbe.deleted',0);
            if(isset($data['tarrif_name']) && $data['tarrif_name']!='') $this->db->like('wbe.tarrif_name', $data['tarrif_name']);  
               
            $result = $this->db->get()->result_array();  
            return $result;
	}
	
         public function get_single_row($id){ 
            $this->db->select('tw.*');
            $this->db->select('(SELECT apartment_name from '.APARTMENTS.' where id = tw.apartment_id) as apartment_name');
            $this->db->select('(SELECT tarrif_name from '.TARRIF_WATERBILL.' where id = tw.total_tarrif_id) as total_tarrif_name');
            $this->db->select('(SELECT tarrif_name from '.TARRIF_WATERBILL.' where id = tw.water_bill_tarrif_id) as tarrif_name');
            $this->db->from(WATERBILL_ENTRY." tw"); 
            $this->db->where('tw.id',$id);
            $this->db->where('tw.deleted',0);
            $result = $this->db->get()->result_array();  
            return $result;
	}
         public function get_tarrif_rows($tarrif_id){ 
            $this->db->select('twr.*');
            $this->db->from(TARRIF_WATERBILL_ROWS." twr"); 
            $this->db->where('twr.tarrif_waterbill_id',$tarrif_id);
            $this->db->where('twr.deleted',0);
            $result = $this->db->get()->result_array();  
            return $result;
	}
         public function get_apartment_resident_list($apartment_id,$where=''){ 
            $this->db->select('ar.*');
            $this->db->from(APARTMENT_RESIDENT." ar"); 
            $this->db->where('ar.apartment_id',$apartment_id);
            $this->db->where('ar.deleted',0);
            if($where!=''){ $this->db->where($where); }
            
            $result = $this->db->get()->result_array();  
            return $result;
	}
                        
        public function add_db($data){    
//            echo '<pre>';            print_r($data); die; 
                $this->db->trans_start();
		$this->db->insert(WATERBILL_ENTRY, $data['wb_entry']); 
//		$this->db->insert_batch(TARRIF_WATERBILL_ROWS, $data['tarrif_rows']); 
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
		$this->db->update(WATERBILL_ENTRY, $data['wb_entry']);
                 
                        
		$status=$this->db->trans_complete();
		return $status;
	}
        
        public function delete_db($id,$data){ 
		$this->db->trans_start();
                
		$this->db->where('id', $id); 
                $this->db->where('deleted',0);
		$this->db->update(WATERBILL_ENTRY, $data);
                
                
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