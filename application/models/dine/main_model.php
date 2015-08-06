<?php
class Main_model extends CI_Model {
	var $mb;	
	public function __construct(){
		parent::__construct();
		$this->mb = $this->load->database('main',true);
	}
	public function add_trans_sales_batch($items){
		$this->mb->insert_batch('trans_sales',$items);
		return $this->mb->insert_id();
	}
	public function add_trans_tbl_batch($table_name,$items){
		$this->mb->insert_batch($table_name,$items);
		return $this->mb->insert_id();
	}
	public function add_trans_tbl($table_name,$items){
		$this->mb->insert($table_name,$items);
		return $this->mb->insert_id();
	}
	public function delete_trans_tbl_batch($table_name=null,$args=null){
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						$this->mb->where_in($col,$val);
					}
					else{
						$func = $val['use'];
						$this->mb->$func($col,$val['val']);
					}
				}
				else
					$this->mb->where($col,$val);
			}
		}
		$this->mb->delete($table_name);
	}
	public function delete_trans_tbl($table_name=null,$args=null){
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						$this->mb->where_in($col,$val);
					}
					else{
						$func = $val['use'];
						$this->mb->$func($col,$val['val']);
					}
				}
				else
					$this->mb->where($col,$val);
			}
		}
		$this->mb->delete($table_name);
	}
	public function update_tbl($table_name,$table_key,$items,$id){
		$this->mb->where($table_key,$id);
		$this->mb->update($table_name,$items);
		return $this->mb->last_query();
	}
}