<?php defined('BASEPATH') OR exit('No direct script access allowed');

class UserModel extends MY_Model {
	protected $table = 'users';

	function __construct()
	{
		parent::__construct();
	}

	public function listUsers($field = '', $value = '', $order = 'desc', $offset = 0)
	{
		$q = $this->db->select('uid, CONCAT_WS(" ", lname, fname) AS `username`, slug_name, picture_profile AS pp, role, COUNT(pid) AS `photos`, IFNULL(SUM(loved), 0) AS `loved`')
		->from($this->table)->join('photos', 'uid', 'left');
		if ($field !== '' && $value !== '') {
			switch ($field) {
				case 'slug_name':
					$q->where( sprintf('MATCH(slug_name) AGAINST(%s IN NATURAL LANGUAGE MODE)', $this->db->escape($value) ) );
					break;
				default:
					$q->like($field, $value);
					break;
			}
		}
		else $field = 'uid';
		return $q->group_by('uid')->order_by($field, $order)->limit(12, $offset)->get()->result_array();
	}
}