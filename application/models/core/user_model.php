<?php
class User_model extends CI_Model{

	public function __construct(){
		parent::__construct();
	}
	public function get_users($id=null,$args=array()){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('users');
			if($id != null){
				if(is_array($id))
				{
					$this->db->where_in('users.id',$id);
				}else{
					$this->db->where('users.id',$id);
				}
			}
			if(!empty($args)){
				foreach ($args as $col => $val) {
					if(is_array($val)){
						if(!isset($val['use'])){
							$this->db->where_in($col,$val);
						}
						else{
							$func = $val['use'];
							if(isset($val['third'])){
								if(isset($val['operator'])){
									$this->db->$func($col." ".$val['operator']." ".$val['val']);
								}
								else
									$this->db->$func($col,$val['val'],$val['third']);
							}
							else{
								$this->db->$func($col,$val['val']);
							}
						}
					}
					else
						$this->db->where($col,$val);
				}
			}
			$this->db->order_by('fname');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_users($items){
		$this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('users',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_users($user,$id){
		$this->db->where('id', $id);
		$this->db->update('users', $user);

		return $this->db->last_query();
	}
}
?>