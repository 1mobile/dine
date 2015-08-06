<?php
class Setup_model extends CI_Model{

	public function __construct()
	{
		parent::__construct();
	}
	public function get_branch_details(){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('branch_details');
			$this->db->where('branch_details.branch_code',BRANCH_CODE);
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	//-----------Categories-----start-----allyn
	public function get_details($id=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('branch_details');
			$this->db->where('branch_details.branch_id',$id);
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function update_details($items,$id){
		// $this->db->where('code', $id);
		$this->db->where('branch_id', $id);
		$this->db->update('branch_details', $items);
	}
	//-----------Categories-----end-----allyn
}
?>