<?php defined('BASEPATH') OR exit('No direct script access allowed');

class PhotosModel extends MY_Model {

	protected $table = 'photos';
	
	function __construct()
	{
		parent::__construct();
	}

	public function getLargePhoto($pid)
	{
		return $this->db->select('pid, content, title, views, dim, size, loved, loved_people, downloaded, photos.created_at, GROUP_CONCAT(tag_name) AS tags, uid, slug_name, picture_profile, CONCAT_WS(" ", lname, fname) AS username')
		->from($this->table)->join('user', 'uid')->join('photos_tags', 'pid')->join('tags', 'tag_id')
		->where('pid', $pid)->get()->result_array()[0];
	}

	/**
	 * get specific photos
	 * @method getPhoto
	 * @param  mixed   $keyword array('uid' => uid) or string
	 * @param  int     $offset
	 * @return array
	 */
	public function getPhoto($keyword = '', $offset = 0)
	{
		$q = $this->db->select('title, thumbnail, content, loved, loved_people, downloaded, pid, uid, slug_name, picture_profile, CONCAT_WS(" ", lname, fname) AS username')
		->from($this->table)->join('user', 'uid')->join('photos_tags', 'pid')->join('tags', 'tag_id');
		
		if (is_array($keyword)) $q->where($keyword);
		else if ($keyword !== '*' && $keyword !== '') $q->like('title', $keyword)->or_like('tag_name', $keyword);
		
		return $q->group_by('title')->order_by('pid', 'desc')->limit(12, $offset)->get()->result_array();
	}

	public function isExists($pid)
	{
		return $this->db->where('pid', $pid)->from($this->table)->count_all_results();
	}
}