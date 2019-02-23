<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Photos extends CI_Migration {

	public function up() {
		$this->dbforge->add_field([
			'pid' => [
				'type' => 'int',
				'auto_increment' => true
			],
			'title' => [
				'type' => 'varchar',
				'constraint' => 255
			],
			'content' => [
				'type' => 'varchar',
				'constraint' => 255,
				'comment' => 'maybe a link or blob content'
			],
			'thumbnail' => [
				'type' => 'varchar',
				'constraint' => 255,
				'comment' => 'maybe a link or blob content'
			],
			'size' => [
				'type' => 'mediumint',
				'constraint' => 8,
				'unsigned' => true,
				'comment' => 'kiB'
			],
			'dim' => [
				'type' => 'varchar',
				'constraint' => 9,
				'unsigned' => true
			],
			'views' => [
				'type' => 'int',
				'default' => '0'
			],
			'downloaded' => [
				'type' => 'int',
				'default' => '0'
			],
			'uid' => [
				'type' => 'int',
				'comment' => 'user who posted this photo'
			],
			'loved' => [
				'type' => 'int',
				'default' => '0'
			],
			'loved_people' => [
				'type' => 'varchar',
				'constraint' => 255,
				'comment' => '{"uid":[1,2,3,4],"time":"1\\/1\\/2018"}',
				'default' => null
			],
			'created_at' => [
				'type' => 'timestamp default CURRENT_TIMESTAMP',
			],
			'updated_at' => [
				'type' => 'timestamp',
				'default' => '0000-00-00 00:00:00'
			],
		]);
		$this->dbforge->add_key('pid', true);
		$this->dbforge->create_table('photos');
		$this->db->query('ALTER TABLE photos ADD FOREIGN KEY(uid) REFERENCES user(uid) ON DELETE CASCADE ON UPDATE CASCADE');
	}

	public function down() {
		$this->dbforge->drop_table('photos');
	}
}