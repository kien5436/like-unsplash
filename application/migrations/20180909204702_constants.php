<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Constants extends CI_Migration {

	public function up() {
		$this->dbforge->add_field([
			'cid' => [
				'type' => 'int',
				'auto_increment' => true
			],
			'cname' => [
				'type' => 'varchar',
				'constraint' => 50
			],
			'cvalue' => [
				'type' => 'varchar',
				'constraint' => 255
			]
		]);
		$this->dbforge->add_key('cid', true);
		$this->dbforge->create_table('constants');
	}

	public function down() {
		$this->dbforge->drop_table('constants');
	}
}