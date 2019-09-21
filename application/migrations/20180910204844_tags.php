<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Tags extends CI_Migration {

	public function up() {
		$this->dbforge->add_field([
			'tag_id' => [
				'type' => 'serial',
			],
			'tag_name' => [
				'type' => 'varchar',
				'constraint' => 30,
				'unique' => TRUE,
			],
			'created_at' => [
				'type' => 'timestamp default CURRENT_TIMESTAMP'
			]
		]);
		$this->dbforge->add_key('tag_id', true);
		$this->dbforge->create_table('tags');
	}

	public function down() {
		$this->dbforge->drop_table('tags');
	}
}