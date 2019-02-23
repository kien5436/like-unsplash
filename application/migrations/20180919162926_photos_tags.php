<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Photos_tags extends CI_Migration {

	public function up() {
		$this->dbforge->add_field([
			'pid' => [
				'type' => 'int'
			],
			'tag_id' => [
				'type' => 'int'
			],
		]);
		$this->dbforge->add_key(['pid', 'tag_id'], true);
		$this->dbforge->create_table('photos_tags');
		$this->db->query('ALTER TABLE photos_tags ADD FOREIGN KEY(pid) REFERENCES photos(pid) ON DELETE CASCADE ON UPDATE CASCADE');
		$this->db->query('ALTER TABLE photos_tags ADD FOREIGN KEY(tag_id) REFERENCES tags(tag_id) ON DELETE CASCADE ON UPDATE CASCADE');
	}

	public function down() {
		$this->dbforge->drop_table('photos_tags');
	}
}