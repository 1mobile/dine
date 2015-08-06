<?php
class Items_model extends CI_Model{

	public function __construct()
	{
		parent::__construct();
	}
	public function get_item($item_id=null,$args=array())
	{
		$this->db->select('
			items.*,
			categories.name as category,
			subcategories.name as subcategory,
			item_types.type as item_type,
			suppliers.name as supplier
			');
		$this->db->from('items');
		$this->db->join('categories','items.cat_id = categories.cat_id');
		$this->db->join('subcategories','subcategories.sub_cat_id = items.subcat_id','left');
		$this->db->join('item_types','items.type = item_types.id');
		$this->db->join('suppliers','items.supplier_id = suppliers.supplier_id','left');
		if (!is_null($item_id)) {
			if (is_array($item_id))
				$this->db->where_in('items.item_id',$item_id);
			else
				$this->db->where('items.item_id',$item_id);
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
		$this->db->order_by('items.name ASC');
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
	public function get_latest_item_move($constraints=array())
	{
		$this->db->select('*');
		$this->db->from('item_moves');
		if (!empty($constraints))
			$this->db->where($constraints);
		$this->db->order_by('reg_date DESC, move_id DESC');
		$query = $this->db->get();
		$row = $query->row();
		$query->free_result();
		return $row;
	}
	public function get_last_item_qty($loc_id=null,$item_id=null){
		$this->db->select('curr_item_qty,item_id,loc_id');
		$this->db->from('item_moves');
		if($loc_id != null){
			$this->db->where('item_moves.loc_id',$loc_id);
		}
		if (!is_null($item_id)) {
			$this->db->where('item_moves.item_id',$item_id);
		}
		$this->db->order_by('reg_date DESC, move_id DESC');
		$this->db->limit(1);
		$query = $this->db->get();
		$row = $query->row();
		$query->free_result();
		return $row;
	}
	public function move_items($loc_id,$items,$opts=array()){
		#items must be an array with qty and UOM
		$batch = array();
		foreach ($items as $item_id => $opt) {
			$last = $this->get_last_item_qty($loc_id,$item_id);
			$curr_qty = 0;
			if(count($last) > 0){
				$curr_qty = $last->curr_item_qty;
			}
			$opts['item_id'] = $item_id;
			$opts['qty'] = $opt['qty'];
			if(isset($opt['case_qty']))
				$opts['case_qty'] = $opt['case_qty'];
			if(isset($opt['pack_qty']))
				$opts['pack_qty'] = $opt['pack_qty'];

			$opts['uom'] = $opt['uom'];
			$opts['loc_id'] = $loc_id;
			$opts['curr_item_qty'] = $curr_qty + $opt['qty'];
			$datetime = date('Y-m-d H:i:s');
			$opts['reg_date'] = $datetime;
			$batch[] = $opts;
		}
		$this->add_item_moves_batch($batch);
		// echo var_dump($batch);
	}
	public function add_item_moves_batch($items)
	{
		$this->db->trans_start();
		$this->db->insert_batch('item_moves',$items);
		$this->db->trans_complete();
	}
	public function get_curr_item_inv_and_locs()
	{
		$prepare = '
			SELECT
				GROUP_CONCAT(
					\'SUM(IF(item_moves.loc_id =\',
					locations.loc_id,
					\', curr_item_qty, NULL)) as "!!Loc-\',
					locations.loc_name, \'"\'
				) as msql
			FROM locations;
			';
		$query = $this->db->query($prepare);
		$query = $query->result();

		$prepped = $query[0]->msql;

		if (empty($prepped))
			return null;

		$sql = '
			SELECT
				items.code,
				items.name,
				items.uom,
				'.$prepped.'
			FROM item_moves
			JOIN items ON item_moves.item_id = items.item_id
			JOIN locations ON item_moves.loc_id = locations.loc_id
			JOIN (
				SELECT
					item_id,
					loc_id,
					MAX(reg_date) xdate
				FROM
					item_moves
				GROUP BY item_id, loc_id
			) a ON item_moves.item_id = a.item_id AND item_moves.loc_id = a.loc_id AND item_moves.reg_date = a.xdate
			GROUP BY item_moves.item_id;
		';
		$r_query = $this->db->query($sql);

		return $r_query;
	}

	public function get_inventory_moves($date=null)
	{
		$prepare ='
			SELECT
				GROUP_CONCAT(
					\'SUM(IF(item_moves.type_id =\',
					trans_types.type_id,
					\',qty,NULL)) as "!!Trans-\',
					trans_types.name,
				\'"\') as msql
			FROM trans_types
		';

		$query = $this->db->query($prepare);
		$query = $query->result();

		$prepped = $query[0]->msql;

		if (empty($prepped))
			return null;

		$sql = '
			SELECT
				items.code,
				items.name,
				items.uom,
				'.$prepped.'
			FROM
				item_moves
			JOIN items ON item_moves.item_id = items.item_id
			JOIN trans_types ON item_moves.type_id = trans_types.type_id
			GROUP BY item_moves.item_id
		';
			// WHERE DATE(item_moves.reg_date) = \''.date('Y-m-d',strtotime($date)).'\'

		$r_query = $this->db->query($sql);
		return $r_query;
	}
}