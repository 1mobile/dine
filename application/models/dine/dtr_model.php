<?php
class Dtr_model extends CI_Model{

	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_dtr_schedules($id=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('dtr_schedules');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('dtr_schedules.dtr_sched_id',$id);
				}else{
					$this->db->where('dtr_schedules.dtr_sched_id',$id);
				}
			$this->db->order_by('dtr_sched_id desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_dtr_schedules($items){
		$this->db->insert('dtr_schedules',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_dtr_schedules($item,$id){
		$this->db->where('dtr_sched_id', $id);
		$this->db->update('dtr_schedules', $item);

		return $this->db->last_query();
	}
	public function add_dtr_schedule_details($items){
		$this->db->insert('dtr_schedule_details',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	
	public function get_dtr_schedule_details($id){
		$this->db->from('dtr_schedule_details');
		// if($id != '')
			$this->db->where('dtr_sched_id',$id);
		$query = $this->db->get();
		$result = $query->result();

		return $result;
	}
	public function validate_dtr_schedule_details($id,$day){
		$this->db->from('dtr_schedule_details');
		$this->db->where('dtr_sched_id',$id);
		$this->db->where('day',$day);

		// $query = $this->db->get();
		// $result = $query->result();
		return $this->db->count_all_results();
	}
	public function delete_dtr_schedule_details($id){
		$this->db->where('id', $id);
		$this->db->delete('dtr_schedule_details');
	}
	///////////////////////////////////////////////////shifts
	//////////////////////////////////////////////////////////

	public function get_shifts($id=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('dtr_shifts');
			if($id != null){
				$this->db->where('id',$id);
			}

			$this->db->order_by('id');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}

	public function get_datetime_difference($firstTime,$lastTime){
		/* Returns date difference in HOURS */
		
		/*SELECT TIME_TO_SEC(TIMEDIFF('2007-01-09 10:24:46','2007-01-09 10:23:46'));*/
		/*$sql = "SELECT TIMEDIFF('$start','$end')"; //will return negative total number of hours*/
		/*
		$sql = "SELECT TIMEDIFF('$end','$start') as thishour"; //will return positive total number of hours
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
		*/
		$firstTime_ = explode(':', $firstTime);
		$lastTime_ = explode(':', $lastTime);
		$ft = ($firstTime_[0]*60*60)+($firstTime_[1]*60); //+$firstTime_[2];
		$lt = ($lastTime_[0]*60*60)+($lastTime_[1]*60); //+$lastTime_[2];

		// return ($lt-$ft);
		//if lumagpas ng 1 day, add 24 hrs sa total ng ($lt-$ft)/3600;
		return ($lt-$ft)/3600;
	}

	public function add_shift($items){
		$this->db->insert('dtr_shifts',$items);
		$x=$this->db->insert_id();
		return $x;
	}

	public function update_shift($items,$id){
		$this->db->where('id', $id);
		$this->db->update('dtr_shifts', $items);

		return $this->db->last_query();
	}

	///////////////////////////////////////scheduler//////////////
	//////////////////////////////////////////////////////////////

	public function get_users($id=null){
		$this->db->select('*');
		$this->db->from('users');
		if($id != null){
			$this->db->where('id',$id);
		}

		$query = $this->db->get();
		$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}

	public function get_schedules($from=null,$to=null,$id=null){
		$this->db->select('*');
		$this->db->from('dtr_scheduler');
		if($id != null){
			$this->db->where('user_id',$id);
		}
		if($from != null){
				$this->db->where('date >=',date2Sql($from));
		}
		if($to != null){
			$this->db->where('date <=',date2Sql($to));
		}

		$query = $this->db->get();
		$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}

	public function getSchedule($date=null,$id=null){
		$this->db->select('*');
		$this->db->from('dtr_scheduler');
		if($id != null){
			$this->db->where('user_id',$id);
		}
		if($date != null){
			$this->db->where('date',date2Sql($date));
		}

		$query = $this->db->get();
		$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}

	public function del_sched($user_id,$date){
		$this->db->where('user_id', $user_id);
		$this->db->where('date', $date);
		$this->db->delete('dtr_scheduler');
	}

	public function insert_sched($items){
		$this->db->insert('dtr_scheduler',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	
}
?>