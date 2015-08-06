<?php
class Clock_model extends CI_Model{

	public function __construct()
	{
		parent::__construct();
	}
	public function get_trans_sales($sales_id=null,$args=array()){
		$this->db->select('trans_sales.*,users.username,terminals.terminal_name');
		$this->db->from('trans_sales');
		$this->db->join('users','trans_sales.user_id = users.id');
		$this->db->join('terminals','trans_sales.terminal_id = terminals.terminal_id');
		if (!is_null($sales_id)){
			if (is_array($sales_id))
				$this->db->where_in('trans_sales.sales_id',$sales_id);
			else
				$this->db->where('trans_sales.sales_id',$sales_id);
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
		$this->db->order_by('trans_sales.datetime desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function add_trans_sales($items){
		// $this->db->set('datetime','NOW()',FALSE);
		$this->db->insert('trans_sales',$items);
		return $this->db->insert_id();
	}
	public function update_trans_sales($items,$sales_id){
		$this->db->set('trans_sales.update_date','NOW()',FALSE);
		$this->db->where('trans_sales.sales_id',$sales_id);
		$this->db->update('trans_sales',$items);
	}
	public function get_trans_sales_menus($sales_menu_id=null,$args=array()){
		$this->db->select('trans_sales_menus.*,menus.menu_code,menus.menu_name');
		$this->db->from('trans_sales_menus');
		$this->db->join('menus','trans_sales_menus.menu_id=menus.menu_id');
		if (!is_null($sales_menu_id)){
			if (is_array($sales_menu_id))
				$this->db->where_in('trans_sales_menus.sales_menu_id',$sales_menu_id);
			else
				$this->db->where('trans_sales_menus.sales_menu_id',$sales_menu_id);
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
		$this->db->order_by('trans_sales_menus.line_id asc');
		$query = $this->db->get();
		return $query->result();
	}
	public function add_trans_sales_menus($items){
		$this->db->insert_batch('trans_sales_menus',$items);
		return $this->db->insert_id();
	}
	public function delete_trans_sales_menus($sales_id){
		$this->db->where('trans_sales_menus.sales_id', $sales_id);
		$this->db->delete('trans_sales_menus');
	}
	public function get_trans_sales_menu_modifiers($sales_mod_id=null,$args=array()){
		$this->db->select('trans_sales_menu_modifiers.*,modifiers.name as mod_name');
		$this->db->from('trans_sales_menu_modifiers');
		$this->db->join('modifiers','trans_sales_menu_modifiers.mod_id=modifiers.mod_id');
		if (!is_null($sales_mod_id)){
			if (is_array($sales_mod_id))
				$this->db->where_in('trans_sales_menu_modifiers.sales_mod_id',$sales_mod_id);
			else
				$this->db->where('trans_sales_menu_modifiers.sales_mod_id',$sales_mod_id);
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
		$this->db->order_by('trans_sales_menu_modifiers.menu_id desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function add_trans_sales_menu_modifiers($items){
		$this->db->insert_batch('trans_sales_menu_modifiers',$items);
		return $this->db->insert_id();
	}
	public function delete_trans_sales_menu_modifiers($sales_id){
		$this->db->where('trans_sales_menu_modifiers.sales_id', $sales_id);
		$this->db->delete('trans_sales_menu_modifiers');
	}

	//////////////////////////////Jed//////////////////
	//////////////////////////////////////////////////

	public function get_sched($date,$user_id){
		$this->db->select('dtr_shifts.*');
		$this->db->from('dtr_scheduler');
		$this->db->join('dtr_shifts','dtr_shifts.id=dtr_scheduler.dtr_id');
		$this->db->where('dtr_scheduler.date',$date);
		$this->db->where('dtr_scheduler.user_id',$user_id);

		$query = $this->db->get();
		return $query->result();
	}

	public function insert_clockin($items){
		// $this->db->set('datetime','NOW()',FALSE);
		$this->db->insert('shifts',$items);
		return $this->db->insert_id();
	}

	public function insert_cashout($items){
		// $this->db->set('datetime','NOW()',FALSE);
		$this->db->insert('cashout_entries',$items);
		return $this->db->insert_id();
	}
	public function update_cashout($items,$cashout_id){
		// $this->db->set('update_date','NOW()',FALSE);
		$this->db->where('cashout_id',$cashout_id);
		$this->db->update('cashout_entries',$items);
	}
	public function insert_cashout_details($items){
		// $this->db->set('datetime','NOW()',FALSE);
		$this->db->insert_batch('cashout_details',$items);
		return $this->db->insert_id();
	}
	public function delete_cashout_details($id){
		$this->db->where('cashout_id', $id);
		$this->db->delete('cashout_details');
	}

	public function check_in($date,$user_id){
		$this->db->select('shifts.*');
		$this->db->from('shifts');
		$this->db->where('user_id',$user_id);
		//$this->db->where('check_in',$date);
		$this->db->where("DATE_FORMAT(check_in,'%Y-%m-%d')",$date);

		$query = $this->db->get();
		return $query->result();
	}

	public function get_shift_id($date=null,$user_id=null,$yesterday=null){
		$this->db->select('*');
		$this->db->from('shifts');
		// $this->db->join('dtr_shifts','dtr_shifts.id=dtr_scheduler.dtr_id');
		if($date != null){
			$this->db->where("DATE_FORMAT(check_in,'%Y-%m-%d')",$date);
		}
		if($user_id != null)
			$this->db->where('user_id',$user_id);
		if($yesterday != null)
			$this->db->where('DATE(check_in) <= DATE("'.$yesterday.'")',null,false);
		$this->db->where('check_out',null);
		// $this->db->where('cashout_id',null);

		$query = $this->db->get();
		return $query->result();
	}
	public function get_curr_shift($date,$user_id){
		$this->db->select('*');
		$this->db->from('shifts');
		// $this->db->join('dtr_shifts','dtr_shifts.id=dtr_scheduler.dtr_id');
		// $this->db->where("DATE_FORMAT(check_in,'%Y-%m-%d')",$date);
		$this->db->where('user_id',$user_id);
		$this->db->where('check_out',null);
		// $this->db->where('cashout_id',null);

		$query = $this->db->get();
		$res =  $query->result();
		if(count($res) > 0 ){
			return $res[0];
		}
		else return array();
	}
	public function get_old_shift($date,$user_id){
		$this->db->select('*');
		$this->db->from('shifts');
		// $this->db->join('dtr_shifts','dtr_shifts.id=dtr_scheduler.dtr_id');
		$this->db->where("DATE_FORMAT(check_in,'%Y-%m-%d')",$date);
		$this->db->where('user_id',$user_id);
		// $this->db->where('cashout_id',null);

		$query = $this->db->get();
		$res =  $query->result();
		return $res;
	}
	public function get_shift_entries($id=null,$args=array()){
		$this->db->select('shift_entries.*,users.username,shifts.check_in,shifts.check_out,terminals.terminal_code,terminals.terminal_name');
		$this->db->from('shift_entries');
		$this->db->join('shifts','shifts.shift_id = shift_entries.shift_id');
		$this->db->join('terminals','shifts.terminal_id = terminals.terminal_id');
		$this->db->join('users','users.id = shift_entries.user_id');
		if($id != null)
			if(is_array($id))
			{
				$this->db->where_in('shift_entries.entry_id',$id);
			}else{
				$this->db->where('shift_entries.entry_id',$id);
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
		$query = $this->db->get();
		return $query->result();
	}
	public function get_user_shift($shift_id=null,$args=array()){
		$this->db->select('*');
		$this->db->from('shifts');
		if (!is_null($shift_id)){
			if (is_array($shift_id))
				$this->db->where_in('shifts.shift_id',$shift_id);
			else
				$this->db->where('shifts.shift_id',$shift_id);
		}
		if(!empty($args)){
			foreach ($args as $col => $val) {
				if(is_array($val)){
					if(!isset($val['use'])){
						if(count($val) > 0 )
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
		$query = $this->db->get();
		return $query->result();
	}
	public function get_cashout_details($id=null,$args=array()){
		$this->db->select('cashout_details.*');
		$this->db->from('cashout_details');
		if($id != null)
			if(is_array($id))
			{
				$this->db->where_in('cashout_details.id',$id);
			}else{
				$this->db->where('cashout_details.id',$id);
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
		$query = $this->db->get();
		return $query->result();
	}
	public function get_cashout_entries($id=null,$args=array()){
		$this->db->select('cashout_entries.*');
		$this->db->from('cashout_entries');
		if($id != null)
			if(is_array($id))
			{
				$this->db->where_in('cashout_entries.cashout_id',$id);
			}else{
				$this->db->where('cashout_entries.cashout_id',$id);
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
		$query = $this->db->get();
		return $query->result();
	}
	public function get_just_shift_entries($id=null,$args=array()){
		$this->db->select('shift_entries.*');
		$this->db->from('shift_entries');
		if($id != null)
			if(is_array($id))
			{
				$this->db->where_in('shift_entries.entry_id',$id);
			}else{
				$this->db->where('shift_entries.entry_id',$id);
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
		$query = $this->db->get();
		return $query->result();
	}
	public function insert_cashin($items){
		// $this->db->set('datetime','NOW()',FALSE);
		$this->db->insert('shift_entries',$items);
		return $this->db->insert_id();
	}
	public function delete_shift_entries($entry_id){
		$this->db->where('shift_entries.entry_id', $entry_id);
		$this->db->delete('shift_entries');
	}
	public function get_user_details($user_id=null){
		$this->db->select('*');
		$this->db->from('users');
		if($user_id != null){
			if(is_array($user_id)){
				$this->db->where_in('id',$user_id);
			}
			else{
				$this->db->where('id',$user_id);
			}
		}

		$query = $this->db->get();
		return $query->result();
	}

	public function get_user_dtrs($user_id=null,$date1=null,$date2=null){
		$this->db->select('*');
		$this->db->from('shifts');
		$this->db->where("DATE_FORMAT(check_in,'%Y-%m-%d') >=",$date1);
		$this->db->where("DATE_FORMAT(check_in,'%Y-%m-%d') <=",$date2);
		$this->db->where("user_id",$user_id);
		$this->db->order_by('check_in desc');
		$query = $this->db->get();
		return $query->result();
	}
	public function update_clockout($items,$shift_id)
	{
		// $this->db->set('update_date','NOW()',FALSE);
		$this->db->where('shift_id',$shift_id);
		$this->db->update('shifts',$items);
	}
	public function get_user_today_in($user_id)
	{
		$this->db->select('*');
		$this->db->from('shifts');
		$this->db->where("DATE_FORMAT(check_in,'%Y-%m-%d')",date('Y-m-d'));
		$this->db->where('user_id',$user_id);

		$query = $this->db->get();
		return $query->result();
	}
	public function get_xread_and_shift($user_id=null,$read_type=X_READ,$date=null)
	{

		// $qry = "
		// 	SELECT
		// 		*
		// 	FROM
		// 		`shifts`
		// 	JOIN `read_details` ON `shifts`.`user_id` = `read_details`.`user_id`
		// 	AND (`read_details`.`reg_date` >= `shifts`.`check_in` AND `read_details`.`reg_date` <= `shifts`.`check_out`)
		// 	WHERE
		// 		`read_details`.`read_type` = ".$read_type;

		$qry = "
			SELECT
				*
			FROM
				`shifts`
			LEFT JOIN `read_details` ON `shifts`.`user_id` = `read_details`.`user_id`
			AND `read_details`.`read_type` = ".$read_type."
			WHERE
				(`shifts`.`xread_id` IS NULL OR `shifts`.`cashout_id` IS NULL)";

		if (!is_null($user_id))
			$qry .= " AND shifts.user_id = ".$user_id;

		if (is_null($date))
			$qry .= " AND date(check_in) = '".date('Y-m-d')."'";

		$query = $this->db->query($qry);
		return $query->result();
	}
	public function get_shifts($constraints=null)
	{
		$this->db->select('*');
		$this->db->from('shifts');

		if (!is_null($constraints)) $this->db->where($constraints);

		$query = $this->db->get();

		return $query->result();
	}
}