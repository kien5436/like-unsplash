<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Photos extends CI_Migration {

	public function up() {
		$this->dbforge->add_field([
			'pid' => [
				'type' => 'serial',
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
				'type' => 'smallint',
				'constraint' => 8,
				'comment' => 'kiB'
			],
			'dim' => [
				'type' => 'varchar',
				'constraint' => 9,
				'unsigned' => true
			],
			'views' => [
				'type' => 'smallint',
				'default' => '0'
			],
			'downloaded' => [
				'type' => 'smallint',
				'default' => '0'
			],
			'uid' => [
				'type' => 'smallint',
				'comment' => 'user who posted this photo'
			],
			'loved' => [
				'type' => 'smallint',
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
				'default' => '1999-01-01 00:00:00'
			],
		]);
		$this->dbforge->add_key('pid', true);
		$this->dbforge->create_table('photos');
	}

	public function down() {
		$this->dbforge->drop_table('photos');
	}
}