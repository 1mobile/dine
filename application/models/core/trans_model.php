<?php
class Trans_model extends CI_Model{

	public function __construct(){
		parent::__construct();
	}
	public function get_trans_types($id=null,$ref=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('trans_types');
			if($id != null){
				$this->db->where('trans_types.type_id',$id);
			}
			if($ref != null){
				$this->db->where('trans_types.reference',$ref);
			}
			$this->db->order_by('type_id desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function get_next_ref($type=null){
		$this->db->trans_start();
			$this->db->select('next_ref');
			$this->db->from('trans_types');
			if($type != null){
				$this->db->where('trans_types.type_id',$type);
			}
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();

		$ref = $result[0]->next_ref;
		if(!$this->ref_unused($type,$ref)){
			$nr = $this->get_right_ref($type,$ref);
			$unused = $nr['unused'];
			$next_re = $nr['nr'];
			while ($unused == false) {
				$arr = $this->get_right_ref($type,$next_re);
				$unused = $arr['unused'];
				$next_re = $arr['nr'];
			}
			$ref = $next_re;
		}
		return $ref;
	}
	function ref_unused($trans_type,$ref){
		$this->db->from('trans_refs');
		$this->db->where('type_id',$trans_type);
		$this->db->where('trans_ref',$ref);
		$query=$this->db->get();
		return ($query->num_rows()>0)?false:true;		
	}
	public function save_ref($type=null,$ref=null){
		if($this->ref_unused($type,$ref)){
			$user = $this->session->userdata('user');
			$refs=$this->write_ref($type,$ref,$user['id']);
			$this->update_next_ref($type,$refs['ref']);
			// echo "here";
		}
		else{
			$nr = $this->get_right_ref($type,$ref);
			$unused = $nr['unused'];
			$next_re = $nr['nr'];
			while ($unused == false) {
				$arr = $this->get_right_ref($type,$next_re);
				$unused = $arr['unused'];
				$next_re = $arr['nr'];
			}
			$user = $this->session->userdata('user');
			$refs=$this->write_ref($type,$next_re,$user['id']);
			$this->update_next_ref($type,$refs['ref']);
		}
	}
	public function get_right_ref($type=null,$ref=null){
		$nr = $this->on_next_ref($type,$ref);
		$unused = $this->ref_unused($type,$nr);
		return array('unused'=>$unused,'nr'=>$nr);
	}
	public function on_next_ref($trans_type,$ref){
	    if (preg_match('/^(\D*?)(\d+)(.*)/', $ref, $result) == 1) 
	    {
	        list($all, $prefix, $number, $postfix) = $result;
	        $dig_count = strlen($number); // How many digits? eg. 0003 = 4
	        $fmt = '%0' . $dig_count . 'd'; // Make a format string - leading zeroes
	        $nextval =  sprintf($fmt, intval($number + 1)); // Add one on, and put prefix back on

	        $new_ref=$prefix.$nextval.$postfix;
	    }
	    else 
	        $new_ref=$ref;
	    return $new_ref;
	}
	public function write_ref($trans_type,$ref=null,$user_id=null){
		$this->db->trans_start();			
			if($ref==null)
				$ref=$this->get_next_ref($trans_type);
			$items = array(
				'type_id'=>$trans_type,
				'trans_ref'=>$ref,
				'user_id'=>$user_id
			);
			$this->db->insert('trans_refs',$items);
		$this->db->trans_complete();
		return array('ref'=>$ref);		
	}
	public function update_next_ref($trans_type,$ref){
        if (preg_match('/^(\D*?)(\d+)(.*)/', $ref, $result) == 1) 
        {
			list($all, $prefix, $number, $postfix) = $result;
			$dig_count = strlen($number); // How many digits? eg. 0003 = 4
			$fmt = '%0' . $dig_count . 'd'; // Make a format string - leading zeroes
			$nextval =  sprintf($fmt, intval($number + 1)); // Add one on, and put prefix back on

			$new_ref=$prefix.$nextval.$postfix;
        }
        else 
            $new_ref=$ref;		
		$this->db->update('trans_types',array('next_ref'=>$new_ref),array('type_id'=>$trans_type));
	}
	public function finish_trans($sales_id=null,$move=false,$void=false){
        $this->load->model('dine/cashier_model');
        $this->load->model('dine/items_model');
        $this->load->model('core/trans_model');
        $loc_id = 2;
        $trans_type = SALES_TRANS;
        if($void)
            $trans_type = SALES_VOID_TRANS;
        $ref = $this->trans_model->get_next_ref($trans_type);
        $this->trans_model->db->trans_start();
            $this->trans_model->save_ref($trans_type,$ref);
            $this->cashier_model->update_trans_sales(array('trans_ref'=>$ref,'paid'=>1),$sales_id);
        $this->trans_model->db->trans_complete();
    }
    /////////////////////////////////////////////
    public function write_ref_string($trans_type,$ref=null,$user_id=null){
		// $this->db->trans_start();			
			if($ref==null)
				$ref=$this->get_next_ref($trans_type);
			$items = array(
				'type_id'=>$trans_type,
				'trans_ref'=>$ref,
				'user_id'=>$user_id
			);
			$str = $this->db->insert_string('trans_refs',$items);
		// $this->db->trans_complete();
		return $str;		
	}
    public function update_trans_ref_string($items,$sales_id){
		// $this->db->set('trans_sales.update_date','NOW()',FALSE);
		// $this->db->where('trans_sales.sales_id',$sales_id);
		$str = $this->db->update_string('trans_sales',$items,array('trans_sales.sales_id'=>$sales_id));
		return $str;
	}
    public function get_trans_sales($sales_id=null,$args=array(),$order='desc',$joinTables=null){
		$this->db->select('
			trans_sales.*,
			users.username,
			terminals.terminal_name,
			terminals.terminal_code,
			tables.name as table_name,
			waiter.username as waiterusername,
			waiter.fname as waiterfname,
			waiter.mname as waitermname,
			waiter.lname as waiterlname,
			waiter.suffix as waitersuffix
			');
			// trans_sales_payments.payment_type pay_type,
			// trans_sales_payments.amount pay_amount,
			// trans_sales_payments.reference pay_ref,
			// trans_sales_payments.card_type pay_card,
		$this->db->from('trans_sales');
		$this->db->join('users','trans_sales.user_id = users.id');
		$this->db->join('users as waiter','trans_sales.waiter_id = waiter.id','left');
		$this->db->join('terminals','trans_sales.terminal_id = terminals.terminal_id');
		// $this->db->join('shifts','trans_sales.shift_id = shifts.shift_id');
		$this->db->join('tables','trans_sales.table_id = tables.tbl_id','left');

		if (!is_null($joinTables) && is_array($joinTables)) {
			foreach ($joinTables as $k => $v) {
				$this->db->join($k,$v['content'],(!empty($v['mode']) ? $v['mode'] : 'inner'));
			}
		}

		// $this->db->join('trans_sales_payments','trans_sales.sales_id = trans_sales_payments.sales_id','left');
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
						if(isset($val['third']))
							$this->db->$func($col,$val['val'],$val['third']);
						else
							$this->db->$func($col,$val['val']);
					}
				}
				else
					$this->db->where($col,$val);
			}
		}
		$this->db->order_by('trans_sales.sales_id',$order);
		// echo $this->db->last_query();
		$query = $this->db->get();
		return $query->result();
	}
}
?>