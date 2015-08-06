<?php
class Dine_model extends CI_Model{

	public function __construct()
	{
		parent::__construct();
	}
	public function get_restaurant_orders($order_id=null,$branch_id=null,$res_id=null,$status=null){
		$this->db->trans_start();
			$this->db->select('restaurant_orders.*,restaurant_branch_tables.name as tbl_name');
			$this->db->from('restaurant_orders');
			$this->db->join('restaurant_branch_tables','restaurant_orders.tbl_id = restaurant_branch_tables.tbl_id');
			if($order_id != null){
				if(is_array($order_id))
				{
					$this->db->where_in('restaurant_orders.order_id',$order_id);
				}else{
					$this->db->where('restaurant_orders.order_id',$order_id);
				}
			}
			if($res_id != null){
				$this->db->where('restaurant_orders.res_id',$res_id);
			}
			if($branch_id != null){
				$this->db->where('restaurant_orders.branch_id',$branch_id);
			}
			if($status != null){
				$this->db->where('restaurant_orders.status',$status);
			}
			$this->db->order_by('restaurant_orders.date desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_restaurant_orders($items){
		// $this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->set('date', 'NOW()', FALSE);
		$this->db->insert('restaurant_orders',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_restaurant_orders($items,$id){
		$this->db->set('done_date', 'NOW()', FALSE);
		$this->db->where('order_id', $id);
		$this->db->update('restaurant_orders', $items);

		return $this->db->last_query();
	}
	public function delete_restaurant_orders($id){
		$this->db->where('order_id', $id);
		$this->db->delete('restaurant_orders'); 
	}
	public function get_restaurant_order_details($order_id=null,$order_detail_id=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('restaurant_order_details');
			if($order_detail_id != null){
				if(is_array($order_detail_id))
				{
					$this->db->where_in('restaurant_order_details.order_detail_id',$order_detail_id);
				}else{
					$this->db->where('restaurant_order_details.order_detail_id',$order_detail_id);
				}
			}
			if($order_id != null){
				if(is_array($order_id))
				{
					$this->db->where_in('restaurant_order_details.order_id',$order_id);
				}else{
					$this->db->where('restaurant_order_details.order_id',$order_id);
				}
				// $this->db->where('restaurant_order_details.order_id',$order_id);
			}
			$this->db->order_by('item_name desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_restaurant_order_details($items){
		// $this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('restaurant_order_details',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function add_restaurant_order_details_batch($items){
		// $this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert_batch('restaurant_order_details',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_restaurant_order_details($items,$id){
		$this->db->where('order_detail_id', $id);
		$this->db->update('restaurant_order_details', $items);

		return $this->db->last_query();
	}
	public function delete_restaurant_order_details($id){
		$this->db->where('order_detail_id', $id);
		$this->db->delete('restaurant_order_details'); 
	}
	public function get_restaurant_branch_occupied_tables($tbl_id=null,$branch_id=null){
		$this->db->trans_start();
			$this->db->select('restaurant_orders.tbl_id');
			$this->db->from('restaurant_orders');
			$this->db->join('restaurant_branch_tables','restaurant_branch_tables.tbl_id = restaurant_orders.tbl_id');
			if($tbl_id != null){
				if(is_array($tbl_id))
				{
					$this->db->where_in('restaurant_branch_tables.tbl_id',$tbl_id);
				}else{
					$this->db->where('restaurant_branch_tables.tbl_id',$tbl_id);
				}
			}
			if($branch_id != null){
				$this->db->where('restaurant_branch_tables.branch_id',$branch_id);
			}
			$this->db->where('restaurant_orders.status','pending');
			$this->db->group_by('restaurant_orders.tbl_id');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
}
?>