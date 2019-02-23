<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Tags extends CI_Migration {

	public function up() {
		$this->dbforge->add_field([
			'tag_id' => [
				'type' => 'int',
				'auto_increment' => true
			],
			'tag_name' => [
				'type' => 'varchar',
				'constraint' => 30
			]
		]);
		$this->dbforge->add_key('tag_id', true);
		$this->dbforge->create_table('tags');
		$this->db->query('ALTER TABLE tags ADD UNIQUE(tag_name)');
	}

	public function down() {
		$this->dbforge->drop_table('tags');
	}
}