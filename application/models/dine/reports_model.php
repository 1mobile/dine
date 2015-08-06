<?php
class Reports_model extends CI_Model{

	public function __construct()
	{
		parent::__construct();
	}
	public function get_logs($user_id=null,$args=array(),$limit=0)
	{
		$this->db->select('
			logs.*,
			users.username,users.fname,users.mname,users.lname,users.suffix
			');
		$this->db->from('logs');
		$this->db->join('users','logs.user_id = users.id','left');
		if (!is_null($user_id)) {
			if (is_array($user_id))
				$this->db->where_in('logs.user_id',$user_id);
			else
				$this->db->where('logs.user_id',$user_id);
		}
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						$this->db->where_in($col,$val);
					}
					else{
						$func = $val['use'];
						$this->db->$func($col,$val['val']);
					}
				}
				else
					$this->db->where($col,$val);
			}
		}
		$this->db->order_by('logs.datetime desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_item_brief($item_id=null)
	{
		$this->db->select('
				items.item_id,items.barcode,items.code,items.name,items.uom
			');
		$this->db->from('items');
		if (!is_null($item_id)) {
			if (is_array($item_id))
				$this->db->where_in('items.item_id',$item_id);
			else
				$this->db->where('items.item_id',$item_id);
		}
		$this->db->order_by('items.name ASC');
		$query = $this->db->get();
		return $query->result();
	}
	public function add_item($items)
	{
		$this->db->set('reg_date','NOW()',FALSE);
		$this->db->insert('items',$items);
		return $this->db->insert_id();
	}
	public function update_item($items,$item_id)
	{
		$this->db->set('update_date','NOW()',FALSE);
		$this->db->where('item_id',$item_id);
		$this->db->update('items',$items);
	}
}