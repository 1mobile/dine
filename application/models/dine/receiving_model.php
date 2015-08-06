<?php
class Receiving_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	public function get_trans_receivings($id=null){
		$this->db->trans_start();
			$this->db->select('trans_receivings.*,suppliers.name as supplier_name,users.username as username');
			$this->db->from('trans_receivings');
			$this->db->join('suppliers','trans_receivings.supplier_id=suppliers.supplier_id');
			$this->db->join('users','trans_receivings.user_id=users.id');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('trans_receivings.receiving_id',$id);
				}else{
					$this->db->where('trans_receivings.receiving_id',$id);
				}
			$this->db->order_by('trans_receivings.reg_date desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_trans_receivings($items){
		$this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('trans_receivings',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_trans_receivings($user,$id){
		$this->db->where('receiving_id', $id);
		$this->db->update('trans_receivings', $user);
		return $this->db->last_query();
	}
	public function add_trans_receiving_batch($items){
		$this->db->trans_start();
		$this->db->insert_batch('trans_receiving_details',$items);
		$this->db->trans_complete();
	}
}
?>