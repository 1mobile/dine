<?php
class Customers_model extends CI_Model{

	public function __construct()
	{
		parent::__construct();
	}
	public function get_customer($id=null)
	{
		$this->db->select('*');
		$this->db->from('customers');
		// $this->db->join('categories','items.cat_id = categories.cat_id');
		// $this->db->join('subcategories','items.subcat_id = subcategories.sub_cat_id');
		// $this->db->join('item_types','items.type = item_types.id');
		// $this->db->join('suppliers','items.supplier_id = suppliers.supplier_id');
		if (!is_null($id)) {
			if (is_array($id))
				$this->db->where_in('customers.cust_id',$id);
			else
				$this->db->where('customers.cust_id',$id);
		}
		$this->db->order_by('customers.lname ASC');
		$query = $this->db->get();
		// echo $this->db->last_query();
		return $query->result();
	}
	public function get_customer_info($telno=null)
	{
		// SELECT * FROM customers WHERE '4560987' = replace(`phone`, '-', '');
		$sql = "SELECT * FROM `customers` WHERE '$telno' = replace(phone,'-','') ORDER BY customers.lname ASC";
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}
	public function get_all_customer_count($telno=null){
		// $this->db->select('COUNT(*) as total_count');
		// $this->db->from('customers');
		// if(!empty($telno)){
			// // $this->db->where('customers.phone', $telno);
			// $this->db->where("'$telno' = replace(customers.phone, '-', '')");
		// }
		// $query = $this->db->get();
		
		$sql = "SELECT COUNT(*) as total_count FROM `customers` WHERE '$telno' = replace(phone,'-','')";
		$query = $this->db->query($sql);
		// // echo $this->db->last_query();
		// // $total=$this->db->count_all_results();
		return $query->result();
	}
	public function add_customer($items)
	{
		// $this->db->set('reg_date','NOW()',FALSE);
		$this->db->insert('customers',$items);
		return $this->db->insert_id();
	}
	public function update_customer($items,$id)
	{
		// $this->db->set('update_date','NOW()',FALSE);
		$this->db->where('cust_id',$id);
		$this->db->update('customers',$items);
	}
	public function search_customers($search=""){
		$this->db->trans_start();
			$this->db->select('cust_id,fname,lname,mname,suffix,phone');
			$this->db->from('customers');
			if($search != ""){
				$this->db->like('customers.phone', $search);
				$this->db->or_like('customers.fname', $search);
				$this->db->or_like('customers.mname', $search);
				$this->db->or_like('customers.lname', $search);
				$this->db->or_like('customers.suffix', $search);
			}
			$this->db->order_by('customers.fname,customers.lname');
			$query = $this->db->get();
			$result = $query->result();
		$this->db->trans_complete();
		return $result;
	}
}