<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Ho_Chi_Minh');
		
		if ($this->input->is_cli_request() === false) {
			show_error('You don\'t have permission for this action', 403);
			return;
		}
		$this->load->library('migration');
	}

	public function version($version)
	{
		if ($this->migration->version($version) === false) {
			echo $this->migration->error_string() . PHP_EOL;
		} else {
			echo 'Migrate done' . PHP_EOL;
		}		
	}

	public function generate($name = false)
	{
		if ($name === false) {
			echo 'Please define migration name' . PHP_EOL;
			return;
		}
		if ( !preg_match('/^[a-z_]+$/i', $name) ) {
			echo 'File name must contain only a-z characters' . PHP_EOL;
			return;
		}

		$fileName = sprintf('%s_%s.php', date('YmdHis'), $name);
		try {
			$folderPath = APPPATH . 'migrations/';
			if ( !is_dir($folderPath) ) {
				try {
					mkdir($folderPath);
				} catch (Exception $e) {
					echo 'Error on create folder: ' . $e->getMessage() . PHP_EOL;
				}
			}

			$filePath = $folderPath . $fileName;
			if (file_exists($filePath)) {
				echo 'File already exists' . PHP_EOL;
				return;
			}

			$content = $this->template($name);

			try {
				if(file_put_contents($filePath, $content) !== false)
					echo 'Migration file was created successfully' . PHP_EOL;
			} catch (Exception $e) {
				echo $e->getMessage() . PHP_EOL;
			}
		} catch (Exception $e) {
			echo $e->getMessage() . PHP_EOL;
		}
	}

	private function template($className)
	{
		return sprintf("<?php defined('BASEPATH') OR exit('No direct script access allowed');\n\nclass Migration_%s extends CI_Migration {\n\n\tpublic function up() {\n\t\t\$this->dbforge->add_field([\n\t\t\t\n\t\t]);\n\t\t\$this->dbforge->add_key('primary_key', true);\n\t\t\$this->dbforge->create_table('%s');\n\t}\n\n\tpublic function down() {\n\t\t\$this->dbforge->drop_table('%s');\n\t}\n}", ucfirst($className), $className, $className);
	}
}