<?php
class Admin_model extends CI_Model{

	public function __construct(){
		parent::__construct();
	}
	public function get_user_roles($id=null,$exclude_admin=true){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('user_roles');
			if($id != null){
				$this->db->where('user_roles.id',$id);
			}
			if($exclude_admin)
				$this->db->where('user_roles.id !=', 1);
			$this->db->order_by('id desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_user_roles($items){
		$this->db->insert('user_roles',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_user_roles($user,$id){
		$this->db->where('id', $id);
		$this->db->update('user_roles', $user);

		return $this->db->last_query();
	}
	public function restart(){
		$this->db->trans_start();
			$this->db->query('truncate table `cashout_details`');
			$this->db->query('truncate table `cashout_entries`');
			$this->db->query('truncate table `ci_sessions`');
			$this->db->query('truncate table `item_moves`');
			$this->db->query('truncate table `logs`');
			$this->db->query('truncate table `read_details`');
			$this->db->query('truncate table `reasons`');
			$this->db->query('truncate table `rob_files`');
			$this->db->query('truncate table `shift_entries`');
			$this->db->query('truncate table `shifts`');
			$this->db->query('truncate table `trans_adjustment_details`');
			$this->db->query('truncate table `trans_adjustments`');
			$this->db->query('truncate table `trans_receiving_details`');
			$this->db->query('truncate table `trans_receivings`');
			$this->db->query('truncate table `trans_refs`');
			$this->db->query('truncate table `trans_sales`');
			$this->db->query('truncate table `trans_sales_charges`');
			$this->db->query('truncate table `trans_sales_discounts`');
			$this->db->query('truncate table `trans_sales_items`');
			$this->db->query('truncate table `trans_sales_menu_modifiers`');
			$this->db->query('truncate table `trans_sales_menus`');
			$this->db->query('truncate table `trans_sales_no_tax`');
			$this->db->query('truncate table `trans_sales_payments`');
			$this->db->query('truncate table `trans_sales_tax`');
			$this->db->query('truncate table `trans_sales_zero_rated`');
			$this->db->query('truncate table `trans_sales_local_tax`');
			$this->db->query('truncate table `ortigas_read_details`');
			$this->db->query("UPDATE trans_types set next_ref = '00000001' WHERE type_id = 10");
			$this->db->query("UPDATE trans_types set next_ref = 'R000001' WHERE type_id = 20");
			$this->db->query("UPDATE trans_types set next_ref = 'A000001' WHERE type_id = 30");
			$this->db->query("UPDATE trans_types set next_ref = 'V000001' WHERE type_id = 11");
		$this->db->trans_complete();
	}
}
?>