<?php
class Menu_model extends CI_Model{

	public function __construct()
	{
		parent::__construct();
	}
	public function get_restaurant_categories($cat_id=null,$res_id=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('restaurant_item_categories');
			if($cat_id != null){
				if(is_array($cat_id))
				{
					$this->db->where_in('restaurant_item_categories.cat_id',$cat_id);
				}else{
					$this->db->where('restaurant_item_categories.cat_id',$cat_id);
				}
			}
			if($res_id != null){
				$this->db->where('restaurant_item_categories.res_id',$res_id);
			}
			$this->db->order_by('name desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_restaurant_categories($items){
		// $this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('restaurant_item_categories',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_restaurant_categories($items,$id){
		$this->db->where('cat_id', $id);
		$this->db->update('restaurant_item_categories', $items);

		return $this->db->last_query();
	}
	public function get_restaurant_subcategories($sub_cat_id=null,$cat_id=null,$res_id=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('restaurant_item_subcategories');
			if($sub_cat_id != null){
				if(is_array($sub_cat_id))
				{
					$this->db->where_in('restaurant_item_subcategories.sub_cat_id',$sub_cat_id);
				}else{
					$this->db->where('restaurant_item_subcategories.sub_cat_id',$sub_cat_id);
				}
			}
			if($cat_id != null){
				$this->db->where('restaurant_item_subcategories.cat_id',$cat_id);
			}
			if($res_id != null){
				$this->db->where('restaurant_item_subcategories.res_id',$res_id);
			}
			$this->db->order_by('name desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_restaurant_subcategories($items){
		// $this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('restaurant_item_subcategories',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_restaurant_subcategories($items,$id){
		$this->db->where('sub_cat_id', $id);
		$this->db->update('restaurant_item_subcategories', $items);

		return $this->db->last_query();
	}
	public function get_restaurant_items($item_id=null,$res_id=null,$cat_id=null,$sub_cat_id=null){
		$this->db->trans_start();
			$this->db->select('restaurant_items.*,restaurant_item_categories.name as cat_name,restaurant_item_subcategories.name as sub_cat_name');
			$this->db->from('restaurant_items');
			$this->db->join('restaurant_item_categories','restaurant_items.cat_id=restaurant_item_categories.cat_id');
			$this->db->join('restaurant_item_subcategories','restaurant_items.sub_cat_id=restaurant_item_subcategories.sub_cat_id');
			if($item_id != null){
				if(is_array($item_id))
				{
					$this->db->where_in('restaurant_items.item_id',$item_id);
				}else{
					$this->db->where('restaurant_items.item_id',$item_id);
				}
			}
			if($res_id != null){
				$this->db->where('restaurant_items.res_id',$res_id);
			}
			if($cat_id != null){
				$this->db->where('restaurant_items.cat_id',$cat_id);
			}
			if($sub_cat_id != null){
				$this->db->where('restaurant_items.sub_cat_id',$sub_cat_id);
			}
			$this->db->order_by('restaurant_items.name');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function search_restaurant_items($res_id=null,$search=""){
		$this->db->trans_start();
			$this->db->select('restaurant_items.item_id,restaurant_items.code,restaurant_items.barcode,restaurant_items.name');
			$this->db->from('restaurant_items');
			if($res_id != null){
				$this->db->where('restaurant_items.res_id',$res_id);
			}
			if($search != ""){
				$this->db->like('restaurant_items.name', $search); 
				$this->db->or_like('restaurant_items.code', $search); 
				$this->db->or_like('restaurant_items.barcode', $search); 
			}
			$this->db->order_by('restaurant_items.name');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_restaurant_items($items){
		// $this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('restaurant_items',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_restaurant_items($items,$id){
		$this->db->where('item_id', $id);
		$this->db->update('restaurant_items', $items);

		return $this->db->last_query();
	}
	public function get_restaurant_combos($combo_id=null,$res_id=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('restaurant_combos');
			if($combo_id != null){
				if(is_array($combo_id))
				{
					$this->db->where_in('restaurant_combos.combo_id',$combo_id);
				}else{
					$this->db->where('restaurant_combos.combo_id',$combo_id);
				}
			}
			if($res_id != null){
				$this->db->where('restaurant_combos.res_id',$res_id);
			}
			$this->db->order_by('combo_name desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_restaurant_combos($items){
		// $this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('restaurant_combos',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function updates_restaurant_combos($items,$id){
		$this->db->where('combo_id', $id);
		$this->db->update('restaurant_combos', $items);

		return $this->db->last_query();
	}
	public function get_restaurant_combo_details($combo_detail_id=null,$item_id=null,$combo_id=null){
		$this->db->trans_start();
			$this->db->select('restaurant_combo_details.*,restaurant_items.name,restaurant_items.code,restaurant_items.barcode,restaurant_items.price,restaurant_items.portion,restaurant_items.portion_price');
			$this->db->from('restaurant_combo_details');
			$this->db->join('restaurant_items','restaurant_items.item_id=restaurant_combo_details.item_id');
			if($combo_detail_id != null){
				if(is_array($combo_detail_id))
				{
					$this->db->where_in('restaurant_combo_details.combo_id',$combo_detail_id);
				}else{
					$this->db->where('restaurant_combo_details.combo_id',$combo_detail_id);
				}
			}
			if($item_id != null){
				$this->db->where('restaurant_combo_details.item_id',$item_id);
			}
			if($combo_id != null){
				$this->db->where('restaurant_combo_details.combo_id',$combo_id);
			}
			$this->db->order_by('restaurant_items.name desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function get_restaurant_combo_detail_prices($combo_detail_id=null,$item_id=null,$combo_id=null){
		$this->db->trans_start();
			$this->db->select('restaurant_combo_details.*,restaurant_items.name,restaurant_items.code,restaurant_items.barcode,restaurant_items.price,restaurant_items.portion,restaurant_items.portion_price');
			$this->db->from('restaurant_combo_details');
			$this->db->join('restaurant_items','restaurant_items.item_id=restaurant_combo_details.item_id');
			if($combo_detail_id != null){
				if(is_array($combo_detail_id))
				{
					$this->db->where_in('restaurant_combo_details.combo_id',$combo_detail_id);
				}else{
					$this->db->where('restaurant_combo_details.combo_id',$combo_detail_id);
				}
			}
			if($item_id != null){
				$this->db->where('restaurant_combo_details.item_id',$item_id);
			}
			if($combo_id != null){
				$this->db->where('restaurant_combo_details.combo_id',$combo_id);
			}
			$this->db->order_by('restaurant_items.name desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_restaurant_combo_detail($items){
		// $this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('restaurant_combo_details',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_restaurant_combo_detail($items,$id){
		$this->db->where('combo_det_id', $id);
		$this->db->update('restaurant_combo_details', $items);

		return $this->db->last_query();
	}
	public function delete_restaurant_combo_detail($id){
		$this->db->where('combo_det_id', $id);
		$this->db->delete('restaurant_combo_details'); 
	}

}
?>