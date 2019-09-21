<?php defined('BASEPATH') OR exit('No direct script access allowed');

/** this is class isn't created for securing users's password */
class SecureModel extends CI_Model {

	protected $table = 'secure';

	function __construct()
	{
		parent::__construct();
	}

	public function granted()
	{
		$this->load->model('UserModel', 'user');
		$this->load->model('ConstantsModel', 'const');

		if ( $this->isValidCookie() ) {
			$role = $this->const->get(['cname' => 'role'], 'cvalue')[0]['cvalue'];
			$role = json_decode(stripslashes($role), true);
			$userRole = $this->user->get(['uid' => $_COOKIE['uid']], 'role')[0]['role'];

			return $role[$userRole] === 'admin';
		}
		return false;
	}

	public function encCookie($cvalue, $uid)
	{
		$salt = bin2hex(random_bytes(10));
		if ($rs = $this->saveSalt(['salt' => $salt, 'uid' => $uid]) )
			return hash('sha256', sprintf('%s%s', $salt, $cvalue));
		return false;
	}

	public function isValidCookie()
	{
		$this->load->model('UserModel', 'user');

		if ( isset( $_COOKIE['uid'], $_COOKIE['pwd'], $_COOKIE['username'] ) ) {
			$info = $this->user->get(['uid' => $_COOKIE['uid']], 'password, slug_name');
			if (empty($info)) return false;
			return ( $_COOKIE['pwd'] === $info[0]['password'] && $_COOKIE['username'] === $info[0]['slug_name'] );
		}
		return false;
	}

	public function saveSalt($data)
	{
		$q = sprintf('INSERT into %s(salt, uid) values', $this->table);
		$q = sprintf('%s(%s, %s),', $q, '?', '?');
		$q = sprintf('%s on duplicate key update salt = values(salt)', $q);

		return $this->db->query($q, $data);
	}
}