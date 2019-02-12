<?php 

class Water_bill_tarrif_model extends CI_Model
{
	function __construct(){	
            parent::__construct(); 
 	}
	 
        public function search_result($data=''){ 
            $this->db->select('a.*');
//            $this->db->select('(select apartment_name from '.APARTMENTS.' where id = a.apartment_id) as apartment_name');
            $this->db->from(TARRIF_WATERBILL.' a');  
            $this->db->where('a.deleted',0);
            if(isset($data['tarrif_name']) && $data['tarrif_name']!='') $this->db->like('a.tarrif_name', $data['tarrif_name']);  
               
            $result = $this->db->get()->result_array();  
            return $result;
	}
	
         public function get_single_row($id){ 
            $this->db->select('tw.*');
            $this->db->from(TARRIF_WATERBILL." tw"); 
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
            $this->db->order_by('twr.order');
            $result = $this->db->get()->result_array();  
            return $result;
	}
                        
        public function add_db($data){    
//            echo '<pre>';            print_r($data); die; 
                $this->db->trans_start();
		$this->db->insert(TARRIF_WATERBILL, $data['tarrif_tbl']); 
		$this->db->insert_batch(TARRIF_WATERBILL_ROWS, $data['tarrif_rows']); 
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
		$this->db->update(TARRIF_WATERBILL, $data['tarrif_tbl']);
                
//                $this->db->where('tarrif_waterbill_id',$id);
//                $this->db->update(TARRIF_WATERBILL_ROWS,array('status'=>0, 'deleted'=>1)); 
                $this->db->delete(TARRIF_WATERBILL_ROWS, array('tarrif_waterbill_id' => $id));    
                
		$this->db->insert_batch(TARRIF_WATERBILL_ROWS, $data['tarrif_rows']);  
                        
		$status=$this->db->trans_complete();
		return $status;
	}
        
        public function delete_db($id,$data){ 
		$this->db->trans_start();
                
		$this->db->where('id', $id); 
                $this->db->where('deleted',0);
		$this->db->update(TARRIF_WATERBILL, $data);
                
                $this->db->where('tarrif_waterbill_id',$id);
                $this->db->update(TARRIF_WATERBILL_ROWS,array('status'=>0, 'deleted'=>1)); 
                
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