<?php
class Logs_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	public function add_logs($type=null,$user_id=null,$action=null,$reference=null){
		$this->db->set('datetime', 'NOW()', FALSE);
		$items = array(
			"type" => $type,
			"action" => $action,
			"user_id" => $user_id,
			"reference" => $reference
		);
		$this->db->insert('logs',$items);
		$x=$this->db->insert_id();
		return $x;
	}
}
?>