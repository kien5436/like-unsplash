<?php defined('BASEPATH') OR exit('No direct script access allowed');

class TagsModel extends MY_Model {
	protected $table = 'tags';

	function __construct()
	{
		parent::__construct();
	}

	public function saveTag($data)
	{
		$q = sprintf('INSERT into %s(tag_name) values', $this->table);
		for ($i = 0; $i < count($data); ++$i) {
			$data[$i] = trim( preg_replace('/\s+/', ' ', $data[$i]) );
			$q = sprintf('%s(%s),', $q, '?');
		}
		$q = rtrim($q, ',');
		$q = sprintf('%s on duplicate key update tag_name = values(tag_name)', $q);

		return $this->db->query($q, $data);
	}

	public function randomTags()
	{
		do {
			$tags = $this->db->query('SELECT tag_name from tags as r1 
				join(SELECT CEIL(RAND() *
				(SELECT MAX(tag_id) FROM tags)
				) AS id) as r2
				where r1.tag_id >= r2.id
				order by r1.tag_id asc limit 20')->result_array();
		} while (count($tags) < 20);

		return $tags;
	}
}