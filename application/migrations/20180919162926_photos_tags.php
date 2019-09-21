<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Photos_tags extends CI_Migration {

	public function up() {
		$this->dbforge->add_field([
			'pid' => [
				'type' => 'integer',
			],
			'tag_id' => [
				'type' => 'integer'
			],
		]);
		$this->dbforge->add_key(['pid', 'tag_id'], true);
		$this->dbforge->create_table('photos_tags');
	}

	public function down() {
		$this->dbforge->drop_table('photos_tags');
	}
}