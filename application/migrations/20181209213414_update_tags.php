<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_tags extends CI_Migration {

	public function up() {
		$this->dbforge->add_column('tags', [
			'created_at' => ['type' => 'timestamp default CURRENT_TIMESTAMP']
		]);
	}

	public function down() {
		$this->dbforge->drop_table('tags');
	}
}