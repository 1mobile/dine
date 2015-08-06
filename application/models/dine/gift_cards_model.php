<?php
class Gift_cards_model extends CI_Model{

	public function __construct()
	{
		parent::__construct();
	}
	public function get_gift_cards($id=null,$getInactive=true)
	{
		$this->db->select('*');
		$this->db->from('gift_cards');
		// $this->db->join('categories','items.cat_id = categories.cat_id');
		// $this->db->join('subcategories','items.subcat_id = subcategories.sub_cat_id');
		// $this->db->join('item_types','items.type = item_types.id');
		// $this->db->join('suppliers','items.supplier_id = suppliers.supplier_id');
		if (!is_null($id)) {
			if (is_array($id))
				$this->db->where_in('gift_cards.gc_id',$id);
			else
				$this->db->where('gift_cards.gc_id',$id);
		}
		if (!$getInactive)
			$this->db->where('inactive',0);

		$this->db->order_by('gift_cards.gc_id ASC');
		$query = $this->db->get();
		// echo $this->db->last_query();
		return $query->result();
	}
	public function get_gift_card_info($cardno=null,$getInactive=true)
	{
		$sql = "SELECT * FROM `gift_cards` WHERE '$cardno' = replace(card_no,'-','') ";
		if (!$getInactive) {
			$sql .= " AND inactive = 0 ";
		}
		$sql .= " ORDER BY gift_cards.gc_id ASC";
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}
	public function get_all_gift_card_count($cardno=null){
		$sql = "SELECT COUNT(*) as total_count FROM `gift_cards` WHERE '$cardno' = replace(card_no,'-','')";
		$query = $this->db->query($sql);
		// // echo $this->db->last_query();
		// // $total=$this->db->count_all_results();
		return $query->result();
	}
	public function add_gift_cards($items)
	{
		// $this->db->set('reg_date','NOW()',FALSE);
		$this->db->insert('gift_cards',$items);
		return $this->db->insert_id();
	}
	public function update_gift_cards($items,$id)
	{
		// $this->db->set('update_date','NOW()',FALSE);
		$this->db->where('gc_id',$id);
		$this->db->update('gift_cards',$items);
	}
}