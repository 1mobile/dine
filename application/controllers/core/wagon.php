<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wagon extends CI_Controller {
	var $data = array();
	public function __construct(){
        parent::__construct();	        
    }
	public function add_to_wagon($name=null,$uniq=null){
		$wagon = array();
		$error = null;
		if($this->session->userData($name)){
			$wagon = $this->session->userData($name);
		}
		if($uniq != null){
			$row = $this->input->post();
			if(count($wagon) > 0){
				if(isset($wagon[$uniq]))
					$error = 'It is already added';
				if($error == null)
					$wagon[$uniq] = $row;
			}
			else{
				$wagon[$uniq] = $row;
			}
			$this->session->set_userData($name,$wagon);
			echo json_encode(array("items"=>$row,"id"=>$uniq,"error"=>$error));
		}
		else{
			$row = $this->input->post();
			$wagon[] = $row;
			$id = max(array_keys($wagon));
			$this->session->set_userData($name,$wagon);
			echo json_encode(array("items"=>$row,"id"=>$id));
		}
	}
	public function update_to_wagon($name=null){
		$wagon = $this->session->userData($name);
		$id = $this->input->post('update');
		$row = $this->input->post();
		$wagon[$id] = $row;
		$this->session->set_userData($name,$wagon);
		echo json_encode(array("items"=>$row,"id"=>$id));
	}
	public function delete_to_wagon($name=null,$id=null){
		$wagon = $this->session->userData($name);
		$row = array();
		if(isset($wagon[$id])){
			$row = $wagon[$id];
			unset($wagon[$id]);
			$this->session->set_userData($name,$wagon);			
		}
		echo json_encode(array("items"=>$row,"id"=>$id));
	}
	public function check_wagon($name=null){
		$wagon = array();
		$error = 0;
		$msg = "";
		if($this->session->userData($name)){
			$wagon = $this->session->userData($name);
		}

		if(count($wagon) == 0){
			$msg = "No items.";
			$error = 1;
		}

		echo json_encode(array("msg"=>$msg,"error"=>$error));
	}
	public function clear_wagon($name=null){
		$this->session->unset_userData($name);
	}
	public function get_wagon($name=null,$wagon_id=null,$return=false){
		$wagon = array();
		if($this->session->userData($name)){
			$wagon = $this->session->userData($name);
		}
		echo var_dump($wagon);
		if(!$return){
			if($wagon_id != null){
				// $row = array();
				if(isset($wagon[$wagon_id]))
					echo json_encode(array('items'=>$wagon[$wagon_id])); 
				else
					echo json_encode(array('items'=>array() )); 
			}		
			else{
					echo json_encode(array('items'=>$wagon)); 
			}

		}
		else{
			if($wagon_id != null){
				if(isset($wagon[$wagon_id]))
					return $wagon[$wagon_id];
				else
					return array();
			}		
			else{
				return $wagon;
			}	
		}
	}
	public function total_wagon($name){
		$wagon = array();
		$item = $this->input->post('total');
		if($this->session->userData($name)){
			$wagon = $this->session->userData($name);
		}
		$totals = array();
		$total = 0;
		foreach ($wagon as $row_id => $row) {
			if(is_array($item)){
				foreach ($item as $val) {
					if(!isset($totals[$val]))
						$totals[$val] = $row[$val];		
					else
						$totals[$val] += $row[$val];		
				}
			}
			else{
				$total += $row[$item];
			}
		}
		if(is_array($item)){
			echo json_encode($totals);
		}
		else{
			echo json_encode(array($item=>$total));
		}
	}
}
