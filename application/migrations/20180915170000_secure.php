<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Secure extends CI_Migration {

	public function up() {
		$this->dbforge->add_field([
			'sid' => [
				'type' => 'int',
				'auto_increment' => true
			],
			'uid' => [
				'type' => 'int',
				'unique' => true
			],
			'salt' => [
				'type' => 'varchar',
				'constraint' => '84'
			]
		]);
		$this->dbforge->add_key('sid', true);
		$this->dbforge->create_table('secure');
		$this->db->query('ALTER TABLE secure ADD FOREIGN KEY(uid) REFERENCES user(uid) ON DELETE CASCADE ON UPDATE CASCADE');
	}

	public function down() {
		$this->dbforge->drop_table('secure');
	}
}