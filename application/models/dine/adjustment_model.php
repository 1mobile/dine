<?php
class Adjustment_model extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
	public function get_adjustments($id = null)
	{
		$this->db->select('trans_adjustments.*,users.username');
		$this->db->from('trans_adjustments');
		$this->db->join('users','trans_adjustments.user_id = users.id');
		if (!is_null($id)) {
			if (is_array($id))
				$this->db->where_in('adjustment_id',$id);
			else
				$this->db->where('adjustment_id',$id);
		}
		$this->db->order_by('reg_date DESC');
		$query = $this->db->get();
		return $query->result();
	}
	public function add_adjustment($items)
	{
		$this->db->trans_start();
		$this->db->set('reg_date','NOW()',false);
		$this->db->insert('trans_adjustments',$items);
		$id = $this->db->insert_id();
		$this->db->trans_complete();
		return $id;
	}
	public function update_adjustment($items,$id)
	{
		$this->db->trans_start();
		$this->db->where('adjustment_id',$id);
		$this->db->set('update_date','NOW()',false);
		$this->db->update('trans_adjustments',$items);
		$this->db->trans_complete();
	}
	public function add_adjustment_detail_batch($items)
	{
		$this->db->trans_start();
		$this->db->insert_batch('trans_adjustment_details',$items);
		$this->db->trans_complete();
	}
}