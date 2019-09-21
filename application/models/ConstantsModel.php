<?php defined('BASEPATH') OR exit('No direct script access allowed');

class ConstantsModel extends MY_Model {
	protected $table = 'constants';
	
	function __construct()
	{
		parent::__construct();
	}

	public function getRole()
	{
		$roles =  $this->get(['cname' => 'role'], 'cvalue');
		return json_decode($roles[0]['cvalue']);
	}
}