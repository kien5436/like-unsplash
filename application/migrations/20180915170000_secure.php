<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Secure extends CI_Migration {

	public function up() {
		$this->dbforge->add_field([
			'sid' => [
				'type' => 'serial',
			],
			'uid' => [
				'type' => 'integer',
				'unique' => true
			],
			'salt' => [
				'type' => 'varchar',
				'constraint' => '84'
			]
		]);
		$this->dbforge->add_key('sid', true);
		$this->dbforge->create_table('secure');
	}

	public function down() {
		$this->dbforge->drop_table('secure');
	}
}