<?php
class Settings_model extends CI_Model{

	public function __construct()
	{
		parent::__construct();
	}

	public function add_promo_details($items){
		$this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->set('update_date', 'NOW()', FALSE);
		$this->db->insert('promo_discounts',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_promo_details($items,$id){
		$this->db->set('update_date', 'NOW()', FALSE);
		$this->db->where('promo_id', $id);
		$this->db->update('promo_discounts', $items);
	}
	public function add_promo_discount_schedules($items){
		$this->db->insert('promo_discount_schedule',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function get_promo_discount_schedules($id=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('promo_discount_schedule');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('promo_discount_schedule.promo_id',$id);
				}else{
					$this->db->where('promo_discount_schedule.promo_id',$id);
				}
			$this->db->order_by('promo_id desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function validate_discount_schedules($promo_id=null,$day=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('promo_discount_schedule');
			if($promo_id != null)
				$this->db->where('promo_discount_schedule.promo_id',$promo_id);
			if($day != null)
				$this->db->where('promo_discount_schedule.day',$day);

			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function get_promo_discounts($id=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('promo_discounts');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('promo_discounts.promo_id',$id);
				}else{
					$this->db->where('promo_discounts.promo_id',$id);
				}
			$this->db->order_by('promo_id desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function get_promo_discount_items($id=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('promo_discount_items');
			$this->db->join('menus', 'menus.menu_id = promo_discount_items.item_id');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('promo_discount_items.promo_id',$id);
				}else{
					$this->db->where('promo_discount_items.promo_id',$id);
				}
			$this->db->order_by('promo_id desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function validate_promo_discount_items($promo_id=null,$item=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('promo_discount_items');
			if($promo_id != null)
				$this->db->where('promo_discount_items.promo_id',$promo_id);
			if($item != null)
				$this->db->where('promo_discount_items.item_id',$item);

			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_promo_item($items){
		$this->db->insert('promo_discount_items',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function get_uom($id=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('uom');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('uom.code',$id);
				}else{
					$this->db->where('uom.code',$id);
				}
			$this->db->order_by('code desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_uom($items){
		// $this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('uom',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_uom($items,$id){
		$this->db->where('code', $id);
		$this->db->update('uom', $items);
	}
	//-----------Categories-----start-----allyn
	public function get_category($id=null,$dontShowInactive=false){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('categories');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('categories.cat_id',$id);
				}else{
					$this->db->where('categories.cat_id',$id);
				}
			if($dontShowInactive){
				$this->db->where('categories.inactive',0);
			}
			$this->db->order_by('cat_id desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_category($items){
		// $this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('categories',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_category($items,$id){
		// $this->db->where('code', $id);
		$this->db->where('cat_id', $id);
		$this->db->update('categories', $items);
	}
	//-----------Categories-----end-----allyn
	//-----------Sub Categories-----start-----allyn
	public function get_subcategory($id=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('subcategories');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('subcategories.sub_cat_id',$id);
				}else{
					$this->db->where('subcategories.sub_cat_id',$id);
				}
			$this->db->order_by('sub_cat_id desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_subcategory($items){
		// $this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('subcategories',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_subcategory($items,$id){
		// $this->db->where('code', $id);
		$this->db->where('sub_cat_id', $id);
		$this->db->update('subcategories', $items);
	}
	//-----------Sub Categories-----end-----allyn
	//-----------Suppliers-----start-----allyn
	public function get_supplier($id=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('suppliers');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('suppliers.supplier_id',$id);
				}else{
					$this->db->where('suppliers.supplier_id',$id);
				}
			$this->db->order_by('supplier_id desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_supplier($items){
		$this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('suppliers',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_supplier($items,$id){
		// $this->db->where('code', $id);
		$this->db->where('supplier_id', $id);
		$this->db->update('suppliers', $items);
	}
	//-----------Suppliers-----end-----allyn
	//-----------Tax Rates-----start-----allyn
	public function get_tax_rates($id=null,$inactive=0){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('tax_rates');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('tax_rates.tax_id',$id);
				}else{
					$this->db->where('tax_rates.tax_id',$id);
				}
			$this->db->where('tax_rates.inactive',$inactive);
			$this->db->order_by('tax_id desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_tax_rates($items){
		// $this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('tax_rates',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_tax_rates($items,$id){
		$this->db->where('tax_id', $id);
		$this->db->update('tax_rates', $items);
	}
	//-----------Tax Rates-----end-----allyn
	public function get_table_layout($id=null){
		$this->db->trans_start();
			$this->db->select('image');
			$this->db->from('branch_details');
			$this->db->where('branch_details.branch_id',$id);
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function update_table_layout($items,$id){
		$this->db->where('branch_id', $id);
		$this->db->update('branch_details', $items);
	}
	public function get_tables($id=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('tables');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('tables.tbl_id',$id);
				}else{
					$this->db->where('tables.tbl_id',$id);
				}
			$this->db->where('tables.inactive',0);
			$this->db->order_by('tbl_id desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_tables($items){
		// $this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('tables',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_tables($items,$id){
		$this->db->where('tbl_id', $id);
		$this->db->update('tables', $items);
	}
	public function delete_tables($id){
		$this->db->where('tbl_id', $id);
		$this->db->delete('tables');
	}
	public function delete_all_tables(){
		// $this->db->where('tbl_id', $id);
		// $this->db->empty_table('tables');
		$this->db->update('tables', array('inactive'=>1));
	}
	//-----------Terminals-----start-----allyn
	public function get_terminal($id=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('terminals');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('terminals.terminal_id',$id);
				}else{
					$this->db->where('terminals.terminal_id',$id);
				}
			$this->db->order_by('terminal_id desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_terminal($items){
		$this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('terminals',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_terminal($items,$id){
		// $this->db->where('code', $id);
		$this->db->where('terminal_id', $id);
		$this->db->update('terminals', $items);
	}
	//-----------Terminals-----end-----allyn
	//-----------Currencies-----start-----allyn
	public function get_currency($id=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('currencies');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('currencies.id',$id);
				}else{
					$this->db->where('currencies.id',$id);
				}
			$this->db->order_by('id desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_currency($items){
		// $this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('currencies',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_currency($items,$id){
		// $this->db->where('code', $id);
		$this->db->where('id', $id);
		$this->db->update('currencies', $items);
	}
	//-----------Currencies-----end-----allyn
	//-----------References-----start-----allyn
	public function get_references(){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('trans_types');
			// $this->db->where('trans_types.type_id',$id);
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function update_references($items,$id){
		// $this->db->where('code', $id);
		$this->db->where('type_id', $id);
		$this->db->update('trans_types', $items);
	}
	//-----------References-----end-----allyn
	//-----------Locations-----start-----allyn
	public function get_location($id=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('locations');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('locations.loc_id',$id);
				}else{
					$this->db->where('locations.loc_id',$id);
				}
			$this->db->order_by('loc_id desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_location($items){
		// $this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('locations',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_location($items,$id){
		// $this->db->where('code', $id);
		$this->db->where('loc_id', $id);
		$this->db->update('locations', $items);
	}
	//-----------Locations-----end-----allyn
	// ------------- Receipt Discounts ------------- //
	public function get_receipt_discounts($id = null)
	{
		$this->db->from('receipt_discounts');
		if (!is_null($id)) {
			if (is_array($id))
				$this->db->where_in('disc_id',$id);
			else
				$this->db->where('disc_id',$id);
		}
		$query = $this->db->get();
		return $query->result();
	}
	public function add_receipt_discount($items)
	{
		$this->db->trans_start();
		$this->db->insert('receipt_discounts',$items);
		$id = $this->db->insert_id();
		$this->db->trans_complete();
		return $id;
	}
	public function update_receipt_discount($items,$id)
	{
		$this->db->trans_start();
		$this->db->where('disc_id',$id);
		$this->db->update('receipt_discounts',$items);
		$this->db->trans_complete();
	}
	// --------- End of Receipt Discounts ---------- //

	//////////////////////jed start
	public function delete_promo_item($ref)
	{
		$this->db->where('id', $ref);
		$this->db->delete('promo_discount_items');
	}
	public function delete_promo_schedule($ref)
	{
		$this->db->where('id', $ref);
		$this->db->delete('promo_discount_schedule');
	}


	////////////////////////jed end
	public function get_charges($id=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('charges');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('charges.charge_id',$id);
				}else{
					$this->db->where('charges.charge_id',$id);
				}
			$this->db->order_by('charge_id desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_charges($items){
		// $this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('charges',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_charges($items,$id){
		$this->db->where('charge_id', $id);
		$this->db->update('charges', $items);
	}
	public function get_denominations($id=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('denominations');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('denominations.id',$id);
				}else{
					$this->db->where('denominations.id',$id);
				}
			$this->db->order_by('value desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_denominations($items){
		// $this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('denominations',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_denominations($items,$id){
		$this->db->where('id', $id);
		$this->db->update('denominations', $items);
	}
	//-----------deno---jed
	public function get_denomination($id=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('denominations');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('id',$id);
				}else{
					$this->db->where('id',$id);
				}
			$this->db->order_by('id desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
	public function add_denomination($items){
		// $this->db->set('reg_date', 'NOW()', FALSE);
		$this->db->insert('denominations',$items);
		$x=$this->db->insert_id();
		return $x;
	}
	public function update_denomination($items,$id){
		// $this->db->where('code', $id);
		$this->db->where('id', $id);
		$this->db->update('denominations', $items);
	}
public function get_cashier($id=null){
		$this->db->trans_start();
			$this->db->select('*');
			$this->db->from('users');
			if($id != null)
				if(is_array($id))
				{
					$this->db->where_in('id',$id);
				}else{
					$this->db->where('id',$id);
				}
			$this->db->order_by('id desc');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}

	public function get_gross_sales($sales_menu_id=null,$args=array(),$date_from=null,$date_to=null,$btw_date_range=false){
		$this->db->select('SUM(total_paid) as "paid_total",SUM(total_amount) as "gross_sales",COUNT(sales_id) as "qty"');
		// $this->db->from('trans_sales_menus');
		// $this->db->join('menus','trans_sales_menus.menu_id=menus.menu_id');
		$this->db->from('trans_sales');
		$this->db->join('users','trans_sales.user_id = users.id');
		$this->db->join('users as waiter','trans_sales.waiter_id = waiter.id','left');
		$this->db->join('terminals','trans_sales.terminal_id = terminals.terminal_id');
		$this->db->join('tables','trans_sales.table_id = tables.tbl_id','left');
		if (!is_null($sales_menu_id)){
			if (is_array($sales_menu_id))
				$this->db->where_in('trans_sales.sales_id',$sales_menu_id);
			else
				$this->db->where('trans_sales.sales_id',$sales_menu_id);
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
		if($btw_date_range){
			$this->db->where("trans_sales.datetime BETWEEN '".$date_from."' AND '".$date_to."'",null,false);
		}else{
			$this->db->where("trans_sales.datetime < '".$date_from."'",null,false);
		}
		$this->db->where("trans_sales.type_id = 10");
		// $this->db->where("trans_sales.inactive = 0");
		// $this->db->order_by('trans_sales.datetime',$order);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_category_list($category_id=null,$args=array(),$date_from=null,$date_to=null,$btw_date_range=false){
		$this->db->select('menu_categories.menu_cat_id,menu_categories.menu_cat_name, COUNT(menus.menu_id) as cat_qty,SUM(trans_sales_menus.price * qty) as trans_total_amnt');
		$this->db->from('menu_categories');
		$this->db->join('menus','menus.menu_cat_id = menu_categories.menu_cat_id','right');
		$this->db->join('trans_sales_menus','trans_sales_menus.menu_id = menus.menu_id');
		$this->db->join('trans_sales','trans_sales_menus.sales_id = trans_sales.sales_id');
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
		if($btw_date_range){
			$this->db->where("trans_sales.datetime BETWEEN '".$date_from."' AND '".$date_to."'",null,false);
		}else{
			$this->db->where("trans_sales.datetime < '".$date_from."'",null,false);
		}
		$this->db->group_by('menus.menu_cat_id');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_category_listings($category_id=null,$args=array(),$date_from=null,$date_to=null,$btw_date_range=false){
		$this->db->select('menu_categories.menu_cat_name, COUNT(menus.menu_id) as cat_qty,SUM(trans_sales_menus.price * qty) as trans_total_amnt');
		$this->db->from('trans_sales_menus');
		$this->db->join('menus','menus.menu_id = trans_sales_menus.menu_id','right');
		$this->db->join('menu_categories','menus.menu_cat_id = menu_categories.menu_cat_name','right');
		$this->db->join('trans_sales','trans_sales_menus.sales_id = trans_sales.sales_id');
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
		if($btw_date_range){
			$this->db->where("trans_sales.datetime BETWEEN '".$date_from."' AND '".$date_to."'",null,false);
		}else{
			$this->db->where("trans_sales.datetime < '".$date_from."'",null,false);
		}
		$this->db->group_by('menu_categories.menu_cat_id');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_store_hours()
	{
		$this->db->select('store_open,store_close');
		$this->db->from('branch_details');
		$this->db->where('branch_code',BRANCH_CODE);

		$query = $this->db->get();
		return $query->row();
	}
	public function update_store_hours($store_open,$store_close)
	{
		$this->db->where('branch_code',BRANCH_CODE);
		$this->db->set('store_open',$store_open);
		$this->db->set('store_close',$store_close);
		$this->db->update('branch_details');
	}
	public function get_store_proper_datetime($date=null)
	{
		$settings = $this->get_store_hours();
		if (is_null($date))
			$date = date('Y-m-d');

		$return_dates = array();
		if ($settings->store_open >= $settings->store_close) {
			$return_dates['from'] = $date.' '.$settings->store_open;
			$return_dates['to'] = date('Y-m-d H:i:s',strtotime($date.' '.$settings->store_close.'+1 day'));
		} else {
			$return_dates['from'] = $date.' '.$settings->store_open;
			$return_dates['to'] = $date.' '.$settings->store_close;
		}
		return $return_dates;
	}
	public function get_discount_list($discount_id=null,$args=array()){
		// $this->db->select('receipt_discounts.disc_name,count(trans_sales.sales_id) as "discount_count",sum(trans_sales.total_amount * ((receipt_discounts.disc_rate)/100)) as "discount_value_total"');
		$this->db->select('receipt_discounts.disc_name,count(trans_sales.sales_id) as "discount_count",sum() as "discount_value_total"');
		$this->db->from('receipt_discounts');
		$this->db->join('trans_sales_discounts','trans_sales_discounts.disc_id = receipt_discounts.disc_id');
		$this->db->join('trans_sales','trans_sales.sales_id = trans_sales_discounts.sales_id');
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
		if($btw_date_range){
			$this->db->where("trans_sales.datetime BETWEEN '".$date_from."' AND '".$date_to."'",null,false);
		}else{
			$this->db->where("trans_sales.datetime < '".$date_from."'",null,false);
		}
		$query = $this->db->get();
		return $query->result();
	}
	public function get_menu_item_sales($sales_id=null,$args=array()){
		$this->db->select('menus.menu_code,menus.menu_name,menus.cost,SUM(trans_sales_menus.qty) AS menu_item_count');
		$this->db->from('menu_categories');
		$this->db->join('menus','menus.menu_cat_id = menu_categories.menu_cat_id');
		$this->db->join('trans_sales_menus','trans_sales_menus.menu_id = menus.menu_id');
		$this->db->join('trans_sales','trans_sales_menus.sales_id = trans_sales.sales_id');
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
		$this->db->group_by('menus.menu_id');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_hourly_sales($sales_id=null,$time_from=null,$time_to=null,$date_from=null,$date_to=null){
		$this->db->select(
			'SUM(IF(!inactive,1,0)) AS "sales_check",
			SUM(IF(ISNULL(guest)||guest = 0, 1, guest)) AS "sales_cover",
		 	SUM(IF(!inactive,total_amount,-total_amount)) "sales_total"
			',false);
		$this->db->from('trans_sales');
		$where = "type_id = ".SALES_TRANS." AND (!inactive OR inactive AND !ISNULL(void_ref))";
		$where .= " AND datetime BETWEEN '$date_from $time_from:00' AND '"
			.($time_from >= $time_to ? date('Y-m-d G:i:s',strtotime($date_from." ".$time_to.":00")) : $date_to." ".$time_to.":00")."'";
		// $this->db->where_between('datetime', $time_from, $time_to);
		$this->db->where($where);

		$query = $this->db->get();
		return $query->row();
	}
	public function get_payment_details($payment_id=null,$args=array(),$date_from=null,$date_to=null,$btw_date_range=false){
		// $this->db->select('payment_type,count(payment_id) as qty, sum(amount) as amount, sum(to_pay) as to_pay');
		$this->db->select('*');
		$this->db->from('trans_sales_payments');
		$this->db->join('trans_sales','trans_sales_payments.sales_id = trans_sales.sales_id');
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
		if($btw_date_range){
			$this->db->where("trans_sales.datetime BETWEEN '".$date_from."' AND '".$date_to."'",null,false);
		}else{
			$this->db->where("trans_sales.datetime < '".$date_from."'",null,false);
		}
		// $this->db->group_by('trans_sales_payments.sales_id');

		$query = $this->db->get();
		return $query->result();
	}

	public function get_discount_details($payment_id=null,$args=array(),$date_from=null,$date_to=null,$btw_date_range=false){
		// $this->db->select('disc_code,COUNT(sales_disc_id) as sdisc_count,SUM(trans_sales.total_amount-(trans_sales_discounts.disc_rate/100)) as sdisc_total');
		$this->db->select('disc_code,COUNT(sales_disc_id) as sdisc_count,SUM(trans_sales_discounts.amount) as sdisc_total');
		$this->db->from('trans_sales_discounts');
		$this->db->join('trans_sales','trans_sales_discounts.sales_id = trans_sales.sales_id');
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
		if($btw_date_range){
			$this->db->where("trans_sales.datetime BETWEEN '".$date_from."' AND '".$date_to."'");
		}else{
			$this->db->where("trans_sales.datetime < '".$date_from."'");
		}
		// $this->db->group_by('payment_type');

		$query = $this->db->get();
		return $query->result();
	}
	public function get_voided_transactions($sales_id=null,$date_from=null,$date_to=null,$btw_date_range=false){
		$this->db->select('COUNT(sales_id) as void_count,SUM(total_amount) as void_amnt');
		$this->db->from('trans_sales');
		if($btw_date_range){
			$this->db->where("trans_sales.datetime BETWEEN '".$date_from."' AND '".$date_to."'",null,false);
		}else{
			$this->db->where("trans_sales.datetime < '".$date_from."'",null,false);
		}
		$this->db->where("type_id = '11'");

		$query = $this->db->get();
		return $query->result();

	}

	public function get_trans_types($type_id=null,$date_from=null,$date_to=null,$btw_date_range=false){
		$this->db->select('trans_sales.type,count(trans_sales.sales_id) as type_count, SUM(total_amount) as type_total');
		$this->db->from('trans_sales');
		$this->db->group_by('trans_sales.type');
		if($btw_date_range){
			$this->db->where("trans_sales.datetime BETWEEN '".$date_from."' AND '".$date_to."'",null,false);
		}else{
			$this->db->where("trans_sales.datetime < '".$date_from."'",null,false);
		}
		$this->db->where("type_id = '10'");
		$this->db->where("inactive = '0'");
		$query = $this->db->get();
		return $query->result();
	}
}
?>