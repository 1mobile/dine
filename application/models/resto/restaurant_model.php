<?php
class Restaurant_model extends CI_Model{

	public function get_restaurants($id=null){
		// $this->db->trans_start();
			$this->db->select('*');
			$this->db->from('restaurants');
			$this->db->join('restaurant_types','restaurants.type_id=restaurant_types.type_id');
			// $this->db->join('0_student_levels','0_students.grade_level = 0_student_levels.id');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('restaurants.res_id',$id);
				}else{
					$this->db->where('restaurants.res_id',$id);
				}
			$this->db->order_by('res_id desc');
		$query = $this->db->get();
		$result = $query->result();
		return $result;
	}
	public function add_restaurant($items){
		// $this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('restaurants',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_restaurant($items,$id){
		$this->db->where('res_id', $id);
		$this->db->update('restaurants', $items);

		return $this->db->last_query();
	}
	/***************************************************************************************
		Taxes per restaurant
	****************************************************************************************/
	public function get_restaurant_taxes($res_id=null){
		$this->db->from('restaurant_tax_rates');
		if($res_id != null){
			$this->db->where('res_id',$res_id);
		}
		$query = $this->db->get();
		$result = $query->result();
		return $result;
	}
	public function get_restaurant_tax($id=null){
		$this->db->from('restaurant_tax_rates');
		if($res_id != null){
			$this->db->where('id',$id);
		}
		$query = $this->db->get();
		$result = $query->result();
		return $result;
	}
	public function add_restaurant_tax($items){
		$this->db->insert('restaurant_tax_rates',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function delete_restaurant_tax($id){
		$this->db->where('id', $id);
		$this->db->delete('restaurant_tax_rates');
	}
	/***************************************************************************************
		Discounts per restaurant
	****************************************************************************************/
	public function get_restaurant_discounts($res_id=null){
		$this->db->from('restaurant_discounts');
		if($res_id != null){
			$this->db->where('res_id',$res_id);
		}
		$query = $this->db->get();
		$result = $query->result();
		return $result;
	}
	public function get_restaurant_discount($disc_id=null){
		$this->db->from('restaurant_discounts');
		if($disc_id != null){
			$this->db->where('disc_id',$disc_id);
		}
		$query = $this->db->get();
		$result = $query->result();
		return $result;
	}
	public function add_restaurant_discount($items){
		$this->db->insert('restaurant_discounts',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function delete_restaurant_discount($id){
		$this->db->where('disc_id', $id);
		$this->db->delete('restaurant_discounts');
	}
}
?>