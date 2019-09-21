<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_User extends CI_Migration {

	public function up() {
		$this->dbforge->add_field([
			'uid' => [
				'type' => 'serial'
			],
			'email' => [
				'type' => 'varchar',
				'constraint' => 255,

			],
			'password' => [
				'type' => 'varchar',
				'constraint' => 255,
			],
			'role' => [
				'type' => 'smallint',
				'default' => '0'
			],
			'fname' => [
				'type' => 'varchar',
				'constraint' => 40,
			],
			'lname' => [
				'type' => 'varchar',
				'constraint' => 10,
			],
			'slug_name' => [
				'type' => 'varchar',
				'constraint' => 100,
			],
			'picture_profile' => [
				'type' => 'varchar',
				'constraint' => 255,
				'comment' => 'maybe a link or blob content',
				'default' => null
			],
			'sex' => [
				'type' => 'smallint',
				'default' => 2
			],
			'location' => [
				'type' => 'varchar',
				'constraint' => 255,
				'default' => null
			],
			'interests' => [
				'type' => 'varchar',
				'constraint' => 100,
				'default' => null
			],
			'created_at' => [
				'type' => 'timestamp default CURRENT_TIMESTAMP'
			]
		]);
		$this->dbforge->add_key('uid', true);
		$this->dbforge->create_table('users');
	}

	public function down() {
		$this->dbforge->drop_table('users');
	}
}