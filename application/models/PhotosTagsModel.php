<?php defined('BASEPATH') OR exit('No direct script access allowed');

class PhotosTagsModel extends MY_Model {
	protected $table = 'photos_tags';
	
	function __construct()
	{
		parent::__construct();
	}

	public function savePhotoTags($data)
	{
		$q = sprintf('INSERT into %s(tag_id, pid) values', $this->table);
		for ($i = 0; $i < count($data); ++$i) {
			$q = sprintf('%s(%s, %s),', $q, $data[$i]['tag_id'], $data[$i]['pid']);
		}
		$q = rtrim($q, ',');

		$q = sprintf('%s on duplicate key update pid = values(pid), tag_id = values(tag_id)', $q);

		return $this->db->query($q);
	}
}