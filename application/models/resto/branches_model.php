<?php
class Branches_model extends CI_Model{

	public function __construct()
	{
		parent::__construct();
	}
	public function get_restaurant_branches($id=null,$res_id=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('restaurant_branches');
			if($id != null){
				if(is_array($id))
				{
					$this->db->where_in('restaurant_branches.branch_id',$id);
				}else{
					$this->db->where('restaurant_branches.branch_id',$id);
				}
			}
			if($res_id != null){
				$this->db->where('restaurant_branches.res_id',$res_id);
			}
			$this->db->order_by('branch_id desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_restaurant_branches($items){
		// $this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('restaurant_branches',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_restaurant_branches($items,$id){
		$this->db->where('branch_id', $id);
		$this->db->update('restaurant_branches', $items);

		return $this->db->last_query();
	}
	public function get_restaurant_branch_staffs($user_id=null,$branch_id=null){
		$this->db->trans_start();
			$this->db->select('restaurant_branch_staffs.*,users.fname,users.mname,users.lname,users.suffix,
							   restaurant_staffs.staff_name,restaurant_staffs.access as dflt_access
							  ');
			$this->db->from('restaurant_branch_staffs');
			$this->db->join('users','restaurant_branch_staffs.user_id = users.id');
			$this->db->join('restaurant_staffs','restaurant_branch_staffs.staff_id = restaurant_staffs.staff_id');
			if($user_id != null){
				if(is_array($user_id))
				{
					$this->db->where_in('restaurant_branch_staffs.user_id',$user_id);
				}else{
					$this->db->where('restaurant_branch_staffs.user_id',$user_id);
				}
			}
			if($branch_id != null){
				$this->db->where('restaurant_branch_staffs.branch_id',$branch_id);
			}
			$this->db->order_by('users.fname desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_restaurant_branch_staffs($items){
		// $this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('restaurant_branch_staffs',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function delete_restaurant_branch_staffs($id){
		$this->db->where('id', $id);
		$this->db->delete('restaurant_branch_staffs'); 
	}
	public function get_restaurant_branch_tables($tbl_id=null,$branch_id=null){
		$this->db->trans_start();
			$this->db->select('restaurant_branch_tables.*');
			$this->db->from('restaurant_branch_tables');
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
			$this->db->order_by('restaurant_branch_tables.name');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_restaurant_branch_tables($items){
		// $this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('restaurant_branch_tables',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_restaurant_branch_tables($items,$id){
		$this->db->where('tbl_id', $id);
		$this->db->update('restaurant_branch_tables', $items);
	}
	public function delete_restaurant_branch_tables($id){
		$this->db->where('tbl_id', $id);
		$this->db->delete('restaurant_branch_tables'); 
	}
	public function delete_restaurant_branch_tables_by_branch($branch_id){
		$this->db->where('branch_id', $branch_id);
		$this->db->delete('restaurant_branch_tables'); 
	}
	public function get_restaurant_branch_menu_item($menu_item_id=null,$item_id=null,$branch_id=null){
		$this->db->trans_start();
			$this->db->select('restaurant_branch_items.*,
							   restaurant_items.name as item_name,
							   restaurant_items.code as item_code,
							   restaurant_items.price as dflt_price,
							   restaurant_items.portion_price as dflt_portion_price,
							   restaurant_items.img as img 
							   ');
			$this->db->from('restaurant_branch_items');
			$this->db->join('restaurant_items','restaurant_branch_items.item_id=restaurant_items.item_id');
			if($menu_item_id != null){
				if(is_array($menu_item_id))
				{
					$this->db->where_in('restaurant_branch_items.menu_item_id',$menu_item_id);
				}else{
					$this->db->where('restaurant_branch_items.menu_item_id',$menu_item_id);
				}
			}
			if($item_id != null){
				$this->db->where('restaurant_branch_items.item_id',$item_id);
			}
			if($branch_id != null){
				$this->db->where('restaurant_branch_items.branch_id',$branch_id);
			}
			$this->db->order_by('item_name');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_restaurant_branch_menu_item($items){
		// $this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('restaurant_branch_items',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_restaurant_branch_menu_item($items,$menu_item_id=null,$item_id=null,$branch_id=null){
		if($menu_item_id != null)
			$this->db->where('menu_item_id', $menu_item_id);
		if($item_id != null)
			$this->db->where('item_id', $item_id);
		if($branch_id != null)
			$this->db->where('branch_id', $branch_id);
		$this->db->update('restaurant_branch_items', $items);
		return $this->db->last_query();
	}
	public function delete_restaurant_branch_menu_item($id){
		$this->db->where('menu_item_id', $id);
		$this->db->delete('restaurant_branch_items'); 
	}
	public function get_restaurant_branch_menu_combo($menu_combo_id=null,$combo_id=null,$branch_id=null){
		$this->db->trans_start();
			$this->db->select('restaurant_branch_combos.*,
							   restaurant_combos.combo_name as combo_name,
							   restaurant_combos.combo_code as combo_code,
							   restaurant_combos.selling_price as dflt_selling_price,
							   restaurant_combos.img as img 
							   ');
			$this->db->from('restaurant_branch_combos');
			$this->db->join('restaurant_combos','restaurant_branch_combos.combo_id=restaurant_combos.combo_id');
			if($menu_combo_id != null){
				if(is_array($menu_combo_id))
				{
					$this->db->where_in('restaurant_branch_combos.menu_combo_id',$menu_combo_id);
				}else{
					$this->db->where('restaurant_branch_combos.menu_combo_id',$menu_combo_id);
				}
			}
			if($combo_id != null){
				$this->db->where('restaurant_branch_combos.combo_id',$combo_id);
			}
			if($branch_id != null){
				$this->db->where('restaurant_branch_combos.branch_id',$branch_id);
			}
			$this->db->order_by('combo_name');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_restaurant_branch_menu_combo($items){
		// $this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('restaurant_branch_combos',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_restaurant_branch_menu_combo($items,$menu_combo_id=null,$combo_id=null,$branch_id=null){
		if($menu_combo_id != null)
			$this->db->where('menu_combo_id', $menu_combo_id);
		if($combo_id != null)
			$this->db->where('combo_id', $combo_id);
		if($branch_id != null)
			$this->db->where('branch_id', $branch_id);
		$this->db->update('restaurant_branch_combos', $items);
		return $this->db->last_query();
	}
	public function delete_restaurant_branch_menu_combo($id){
		$this->db->where('menu_combo_id', $id);
		$this->db->delete('restaurant_branch_combos'); 
	}
}
?>