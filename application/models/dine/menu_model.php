<?php
class Menu_model extends CI_Model{

	public function __construct()
	{
		parent::__construct();
	}
	public function get_menus($id=null,$cat_id=null,$notAll=false){
		$this->db->trans_start();
			$this->db->select('menus.*,menu_categories.menu_cat_name as category_name,menu_schedules.desc as menu_schedule_name');
			$this->db->from('menus');
			$this->db->join('menu_categories','menus.menu_cat_id = menu_categories.menu_cat_id');
			$this->db->join('menu_schedules','menus.menu_sched_id = menu_schedules.menu_sched_id');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('menus.menu_id',$id);
				}else{
					$this->db->where('menus.menu_id',$id);
				}
			if($cat_id != null){
				$this->db->where('menus.menu_cat_id',$cat_id);
			}
			if($notAll){
				$this->db->where('menus.inactive',0);
			}
			$this->db->order_by('menus.menu_name desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_menus($items){
		$this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('menus',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_menus($user,$id){
		$this->db->where('menu_id', $id);
		$this->db->update('menus', $user);

		return $this->db->last_query();
	}
	public function get_menu_categories($id=null,$notAll=false){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('menu_categories');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('menu_categories.menu_cat_id',$id);
				}else{
					$this->db->where('menu_categories.menu_cat_id',$id);
				}
			if($notAll){
				$this->db->where('menu_categories.inactive',0);
			}
			$this->db->order_by('menu_cat_id desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_menu_categories($items){
		$this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('menu_categories',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_menu_categories($user,$id){
		$this->db->where('menu_cat_id', $id);
		$this->db->update('menu_categories', $user);

		return $this->db->last_query();
	}
	public function get_menu_subcategories($id=null,$notAll=false){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('menu_subcategories');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('menu_subcategories.menu_sub_cat_id',$id);
				}else{
					$this->db->where('menu_subcategories.menu_sub_cat_id',$id);
				}
			if($notAll){
				$this->db->where('menu_subcategories.inactive',0);
			}
			$this->db->order_by('menu_sub_cat_id asc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_menu_subcategories($items){
		$this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('menu_subcategories',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_menu_subcategories($user,$id){
		$this->db->where('menu_sub_cat_id', $id);
		$this->db->update('menu_subcategories', $user);

		return $this->db->last_query();
	}
	public function get_menu_schedules($id=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('menu_schedules');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('menu_schedules.menu_sched_id',$id);
				}else{
					$this->db->where('menu_schedules.menu_sched_id',$id);
				}
			$this->db->order_by('menu_sched_id desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_menu_schedules($items){
		$this->db->insert('menu_schedules',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_menu_schedules($item,$id){
		$this->db->where('menu_sched_id', $id);
		$this->db->update('menu_schedules', $item);

		return $this->db->last_query();
	}
	public function add_menu_schedule_details($items){
		$this->db->insert('menu_schedule_details',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_menu_schedule_details($item,$id){
		$this->db->where('id', $id);
		$this->db->update('menu_schedule_details', $item);

		return $this->db->last_query();
	}
	public function get_menu_schedule_details($id){
		$this->db->from('menu_schedule_details');
		// if($id != '')
			$this->db->where('menu_sched_id',$id);
		$query = $this->db->get();
		$result = $query->result();

		return $result;
	}
	public function validate_menu_schedule_details($id,$day){
		$this->db->from('menu_schedule_details');
		$this->db->where('menu_sched_id',$id);
		$this->db->where('day',$day);

		// $query = $this->db->get();
		// $result = $query->result();
		return $this->db->count_all_results();
	}
	public function delete_menu_schedule_details($id){
		$this->db->where('id', $id);
		$this->db->delete('menu_schedule_details');
	}
	// public function get_recipe_items($menu_id=null,$item_id=null,$id=null){
	// 	$this->db->trans_start();
	// 		$this->db->select('menu_recipe.*,menus.menu_name as item_name,menus.cost as item_cost');
	// 		$this->db->from('menu_recipe');
	// 		$this->db->join('menus','menu_recipe.menu_id=menus.menu_id');
	// 		$this->db->join('items','items.item_id=menus.menu_id');
	// 		if($id != null)
	// 			if(is_array($id))
	// 			{
	// 				$this->db->where_in('menu_recipe.recipe_id',$id);
	// 			}else{
	// 				$this->db->where('menu_recipe.recipe_id',$id);
	// 			}
	// 		if($menu_id != null)
	// 			$this->db->where_in('menu_recipe.menu_id',$menu_id);
	// 		if($item_id != null)
	// 			$this->db->where_in('menu_recipe.item_id',$item_id);
	// 		$this->db->order_by('recipe_id desc');
	// 		$query = $this->db->get();
	// 		$result = $query->result();
	// 	$this->db->trans_complete();
	// 	return $result;
	// }
	public function get_recipe_items($menu_id,$item_id = null,$id=null)
	{
		$this->db->select('
			menus.menu_code,
			menus.menu_barcode,
			menus.menu_name,
			menus.cost "menu_cost",
			items.item_id,
			items.name "item_name",
			items.barcode "item_barcode",
			items.code "item_code",
			items.cost "item_cost",
			menu_recipe.recipe_id,
			menu_recipe.uom,
			menu_recipe.qty,
			menu_recipe.menu_id
			');
		$this->db->from('menu_recipe');
		$this->db->join('menus','menu_recipe.menu_id = menus.menu_id');
		$this->db->join('items','menu_recipe.item_id = items.item_id');

		if (is_array($menu_id))
			$this->db->where_in('menu_recipe.menu_id',$menu_id);
		else
			$this->db->where('menu_recipe.menu_id',$menu_id);

		if(!is_null($id)) {
			if(is_array($id))
				$this->db->where_in('menu_recipe.recipe_id',$id);
			else
				$this->db->where('menu_recipe.recipe_id',$id);
		}

		if (!is_null($item_id))
			$this->db->where('menu_recipe.item_id',$item_id);

		$this->db->order_by('menus.menu_name ASC, items.name ASC');
		$query = $this->db->get();

		return $query->result();
	}
	// public function add_recipe_item($items){
	// 	$this->db->insert('menu_recipe',$items);
	// 	$x=$this->db->insert_id();
	// 	return $x;
	// }
	public function add_recipe_item($items)
	{
		$this->db->trans_start();
		$this->db->insert('menu_recipe',$items);
		$this->db->trans_complete();
		$id = $this->db->insert_id();
		return $id;
	}
	// public function update_recipe_item($menu_id=null,$item_id=null){
	// 	$this->db->where('menu_id', $menu_id);
	// 	$this->db->where('item_id', $item_id);
	// 	$this->db->update('menu_recipe', $item);

	// 	return $this->db->last_query();
	// }
	public function update_recipe_item($items,$menu_id,$item_id)
	{
		$this->db->trans_start();
		$this->db->where(array('menu_id'=>$menu_id,'item_id'=>$item_id));
		$this->db->update('menu_recipe',$items);
		$this->db->trans_complete();
	}
	// public function remove_recipe_item($id){
	// 	$this->db->where('recipe_id', $id);
	// 	$this->db->delete('menu_recipe');
	// }
	public function remove_recipe_item($recipe_id)
	{
		$this->db->trans_start();
		$this->db->where('recipe_id',$recipe_id);
		$this->db->delete('menu_recipe');
		$this->db->trans_complete();
	}
	// public function search_items($search=""){
	// 	$this->db->trans_start();
	// 		$this->db->select('items.item_id,items.code,items.barcode,items.name');
	// 		$this->db->from('items');
	// 		if($search != ""){
	// 			$this->db->like('items.name', $search);
	// 			$this->db->or_like('items.code', $search);
	// 			$this->db->or_like('items.barcode', $search);
	// 		}
	// 		$this->db->order_by('items.name');
	// 		$query = $this->db->get();
	// 		$result = $query->result();
	// 	$this->db->trans_complete();
	// 	return $result;
	// }
	public function search_items($search=""){
		$this->db->trans_start();
			$this->db->select('items.item_id,items.code,items.barcode,items.name');
			$this->db->from('items');
			if($search != ""){
				$this->db->like('items.name', $search);
				$this->db->or_like('items.code', $search);
				$this->db->or_like('items.barcode', $search);
			}
			$this->db->order_by('items.name');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	/********	 	Menu Modifiers 		********/
	public function get_menu_modifiers($menu_id=null,$mod_group_id=null,$id=null){
			$this->db->select('menu_modifiers.*,modifier_groups.name as mod_group_name,modifier_groups.mandatory,modifier_groups.multiple');
			$this->db->from('menu_modifiers');
			$this->db->join('modifier_groups','menu_modifiers.mod_group_id=modifier_groups.mod_group_id');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('menu_modifiers.id',$id);
				}else{
					$this->db->where('menu_modifiers.id',$id);
				}
			if($menu_id != null)
				$this->db->where_in('menu_modifiers.menu_id',$menu_id);
			if($mod_group_id != null)
				$this->db->where_in('menu_modifiers.mod_group_id',$mod_group_id);
			$this->db->order_by('id desc');
			$query = $this->db->get();
			$result = $query->result();
		return $result;
	}
	public function get_modifier_groups($constraints = null)
	{
		$this->db->from('modifier_groups');
		if (!empty($constraints))
			$this->db->where($constraints);
		$this->db->order_by('name ASC');
		$query = $this->db->get();
		return $query->result();
	}
	public function search_modifier_groups($search="")
	{
		$this->db->from('modifier_groups');
		if ($search != "")
			$this->db->like('name',$search);
		$query = $this->db->get();
		return $query->result();
	}
	public function add_menu_modifier($items)
	{
		$this->db->trans_start();
		$this->db->insert('menu_modifiers',$items);
		$id = $this->db->insert_id();
		$this->db->trans_complete();
		return $id;
	}
	public function remove_menu_modifier($id)
	{
		$this->db->trans_start();
		$this->db->where('id',$id);
		$this->db->delete('menu_modifiers');
		$this->db->trans_complete();
	}
	/********	 End of	Menu Modifiers 	********/
}
?>