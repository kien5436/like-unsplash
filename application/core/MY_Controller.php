<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	protected $layout = [];
	protected $error = ['errorCode' => 0, 'errorVal' => ''];

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Ho_Chi_Minh');
	}

	/**
	 * combine multiple files into one file
	 * @method combineJS
	 * @param  array   $modules files
	 * @param  string  $des     destination file
	 * @return real path of the imported file
	 */
	protected function combineJS($modules, $des, $update = 0)
	{
		$content = '';
		$desRef = realpath($des);

		if ( !( file_exists($desRef) && file_get_contents($desRef) ) || $update === 1 ) {
			for ($i = 0; $i < count($modules); $i++) { 
				if ($file = realpath($modules[$i]) )
					$content .= file_get_contents($file) . "\n\n";
			}

			if ( file_exists($desRef) && decoct(fileperms($desRef) & 0777) !== '666' ) chmod($desRef, 0666);
			file_put_contents($des, trim($content));
			$desRef = realpath($des); // update file's real path after creating
		}
		
		return $desRef;
	}

	/**
	 * set cookies
	 * @method setCookie
	 * @param  array  $cookie ['name' => ['value', 'expire', 'secure', 'httponly']]
	 */
	protected function setCookie($cookie)
	{
		$domain = $_SERVER['HTTP_HOST'];
		foreach ($cookie as $name => &$config) {
			$config['secure'] = isset($config['secure']) ?: false;
			$config['httponly'] = isset($config['httponly']) ? $config['httponly'] : true;
			setcookie($name, $config['value'], $config['expire'], '/', $domain, $config['secure'], $config['httponly']);
		}		
	}

	public function resizePhoto($photo, $w, $relPath)
	{
		$this->load->library('image_lib');
		$thumb = [];

		for ($i = 0; $i < count($w); ++$i) {
			$config = [
				'image_library'  => 'gd2',
				'maintain_ratio' => TRUE,
				'master_dim'     => 'width',
				'new_image'      => preg_replace("#.*\/(\w+).(jpe?g|png|gif)$#i", "$1_{$w[$i]}.$2", $photo),
				'source_image'   => $photo,
				'width'          => $w[$i],
			];

			$this->image_lib->clear();
			$this->image_lib->initialize($config);

			if ($this->image_lib->resize() === true)
				$thumb[$i] = sprintf('%s%s', $relPath, $config['new_image']);
			else {
				var_dump($this->image_lib->display_errors());
				return false;
			}
		}
		return $thumb;
	}

	/**
	 * delete all photos (origin and resized) when upload failed
	 * @method delPhotos
	 * @param  mixed  $uploaded [array or string contains file uploaded's path]
	 * $uploaded = 'absolute/file/path/on/disk'
	 * $uploaded = [
	 * 	'file_path' => 'path of folder',
	 * 	'raw_name' => 'name of file',
	 * 	'file_ext' => 'file extension'
	 * ]
	 */
	public function delPhotos($uploaded)
	{
		if (is_array($uploaded))
			$files = glob(sprintf('%s%s%s%s', $uploaded['file_path'], $uploaded['raw_name'], '*', $uploaded['file_ext']));
		else $files = glob($uploaded);
		unlink($uploaded['full_path']);
		foreach ($files as $file) {
			(file_exists($file) === true) && unlink($file);
		}
	}

	public function genNotif($msg = 'Đã xảy ra lỗi, vui lòng thử lại sau')
	{
		return sprintf('<div class="notif">
								<i class="fa fa-times notif-x" onclick="this.parentNode.remove()"></i>
								<p class="notif-content">%s</p>
							</div>', $msg);
	}

	/** generate contents for admin pages */
	protected function genContentAdmin($content)
	{
		$menu = [
			[
				'url' => base_url('quan-tri/thong-ke'),
				'text' => 'Báo cáo thống kê',
				'icon' => 'fa fa-pie-chart',
				'active' => 'active'
			],
			[
				'url' => base_url('quan-tri/quan-li-nguoi-dung'),
				'text' => 'Quản lí người dùng',
				'icon' => 'fa fa-user-o',
				'active' => false
			],
			[
				'url' => base_url('quan-tri/quan-li-anh'),
				'text' => 'Quản lí ảnh',
				'icon' => 'fa fa-picture-o',
				'active' => false
			],
			[
				'url' => base_url('quan-tri/quan-li-the'),
				'text' => 'Quản lí thẻ',
				'icon' => 'fa fa-tag',
				'active' => false
			],
			[
				'url' => base_url('quan-tri/thong-bao'),
				'text' => 'Thông báo',
				'icon' => 'fa fa-bell',
				'active' => false
			],
		];

		$sidebar = $this->load->view('admin/sidebar', ['menu' => $menu], true);
		$modal = $this->load->view('template/modal', '', true);

		return sprintf('<div class="container">%s<div class="main">%s</div></div>%s', $sidebar, $content, $modal);
	}

	protected function genHeaderAdmin()
	{
		$header['pp'] = $this->user->get(['uid' => $_COOKIE['uid']], 'picture_profile')[0]['picture_profile'];
		if ($header['pp'] !== null) $header['pp'] = base_url( explode(',', $header['pp'])[1] );
		else $header['pp'] = base_url('upload/picture_profiles/default_50.png');

		return $this->load->view('admin/header', $header, true);
	}
}