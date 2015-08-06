<?php
class Management_model extends CI_Model{

	public function __construct()
	{
		parent::__construct();
	}
	public function get_restaurant_types($id=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('restaurant_types');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('restaurant_types.type_id',$id);
				}else{
					$this->db->where('restaurant_types.type_id',$id);
				}
			$this->db->order_by('type_id desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_restaurant_types($items){
		// $this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('restaurant_types',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_restaurant_types($user,$id){
		$this->db->where('type_id', $id);
		$this->db->update('restaurant_types', $user);

		return $this->db->last_query();
	}
	public function get_restaurant_staffs($id=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('restaurant_staffs');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('restaurant_staffs.staff_id',$id);
				}else{
					$this->db->where('restaurant_staffs.staff_id',$id);
				}
			$this->db->order_by('staff_id desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_restaurant_staffs($items){
		// $this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('restaurant_staffs',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_restaurant_staffs($items,$id){
		$this->db->where('staff_id', $id);
		$this->db->update('restaurant_staffs', $items);

		return $this->db->last_query();
	}
}
?>