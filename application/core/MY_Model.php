<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {

	protected $table;

	function __construct(){
		parent::__construct();
	}

	/**
	 * get metrics between two times
	 * @method getMetrics
	 * @param  datetime     $from
	 * @param  datetime     $to
	 * @return array
	 */
	public function getMetrics($from, $to)
	{
		$q = sprintf('SELECT created_at FROM %s WHERE created_at BETWEEN "%s" AND "%s"', $this->table, $from, $to);
		$data = $this->db->query($q)->result_array();
		return $data;
	}

	/**
	 * analyse data between last month and current month
	 * @method analyse
	 * @return [int]
	 */
	public function analyse()
	{
		$month = date('m');
		$q = sprintf('SELECT created_at FROM %s WHERE MONTH(created_at) = ?', $this->table);
		$currMon = $this->db->query($q, $month)->num_rows();
		$lastMon = $this->db->query($q, $month - 1)->num_rows();
		return $lastMon != 0 ? round( 100 * ($currMon / $lastMon - 1) ) . '%' : 'Đang tăng';
	}

	/**
	 * get data with custom SELECT and WHERE clause
	 * @method get
	 * @param  mixed   $conditions	[if $condition = 1 then get all data (default)]
	 * @param  mixed   $selections	[get all on default]
	 * @param  int     $limit
	 * @param  int     $offset
	 *
	 * e.g:
	 * $condition = ['key' => 'value', ...] then WHERE clause like "'key' = 'value' AND 'key' = 'value'"
	 * $condition = "'key1' = 'value1' AND/OR/... 'key2' like '%value2%'" then WHERE is same
	 * you can do this with $selections
	 * $selections = ['field1', 'field2', ...]
	 * $selections = 'field1, field2, ...'
	 */
	public function get($conditions = 1, $selections = '*', $order = '', $limit = null, $offset = 0)
	{
		$q = $this->db->select($selections);
		if($conditions != 1) $q->where($conditions);
		$rs = $q->order_by($order, 'desc')->get($this->table, $limit, $offset);
		return $rs->result_array();
	}
	
	/**
	 * delete ONE data, using "=" or "LIKE" operator
	 * delete MULTIPLE data, using "IN" operator
	 * @method delete
	 * @param  array $conditions ['condition' => array(value1, value2, ...)]
	 * @param string $operator [= (default), LIKE, IN,]
	 */
	public function delete($conditions, $operator = '')
	{
		switch ($operator) {
			case '':
				$this->db->where($conditions);
				break;
			case 'like':
				$this->db->like($conditions);
				break;
			case 'in':
				extract($conditions);
				$col = key($conditions);
				$this->db->where_in($col, $conditions[$col]);
				break;
		}

		$this->db->delete($this->table);
		return $this->db->affected_rows();
	}

	public function insert($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	/**
	 * update data
	 * @method update
	 * @param  mixed    $conditions default is true, otherwise array or string
	 * @param  array    $data
	 * @return number of affected rows
	 */
	public function update($conditions = 1, $data)
	{
		$this->db->trans_start();
		$this->db->where($conditions)->update($this->table, $data);
		$this->db->trans_complete();
		return $this->db->trans_status();
	}
}