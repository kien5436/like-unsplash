<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->search('*');
	}

	public function search($kw = null)
	{
		// if JS is enabled, get the request uri and decode it
		// else get the input form, PHP decode its value automatically
		$kw = ($kw !== null) ? urldecode($kw) : strip_tags($this->input->get('se'));

		$this->load->model('SecureModel', 'secure');
		$this->load->model('PhotosModel', 'photo');
		$this->load->model('TagsModel', 'tag');

		$header['tags'] = $this->tag->randomTags();
		$header['se'] = $kw == '*' ? null : $kw;
		$header['isValidCookie'] = $this->secure->isValidCookie();
		$header['granted'] = $this->secure->granted();

		if ($header['isValidCookie'] == true) {
			$pp = $this->user->get(['uid' => $_COOKIE['uid']], 'picture_profile')[0]['picture_profile'];
			$header['pp'] = ($pp !== null) ? base_url( explode(',', $pp)[1] ) : base_url('upload/picture_profiles/default_50.png');
		}

		if ($kw != false) {
			$photos = $this->photo->getPhoto($kw);

			if ( !empty($photos) ) {
				foreach ($photos as &$photo) {
					$photo['thumbnail'] = explode(',', $photo['thumbnail']);
					array_walk($photo['thumbnail'], function(&$p) {
						$p = base_url($p);
					});
					$photo['content'] = base_url($photo['content']);
					// compare whether current user loved their photos by themselves
					$photo['loved_people'] = json_decode($photo['loved_people'], true)['uid'];
					$photo['selfLoved'] = ($header['isValidCookie'] === true && null !== $photo['loved_people']) ? array_search($_COOKIE['uid'], $photo['loved_people']) : false;
					// handle user's picture profile
					$photo['picture_profile_50'] = ($photo['picture_profile'] !== null) ? base_url( explode(',', $photo['picture_profile'])[1] ) : base_url('upload/picture_profiles/default_50.png');
					unset($photo['loved_people'], $photo['picture_profile']);
				}
				$photos['next'] = 12;
				$photos['isValidCookie'] = $this->secure->isValidCookie();
				$photos['granted'] = $this->secure->granted();
			}
		}
		else $photos = null;

		$header = $this->load->view('user/header', $header, true); // place header before masonry to use $se
		$masonry = $this->load->view('template/masonry', ['photos' => $photos], true);
		$modal = $this->load->view('template/modal', '', true);

		$this->layout = [
			'title' => 'Trang chủ - Like-unsplash',
			'header' => $header,
			'contents' => sprintf('%s %s', $masonry, $modal),
			'footer' => '',
			'css' => [
				'/vendor/css/font-awesome.min.css',
				'/vendor/css/user/home.css'
			],
			'js' => [
				'/vendor/js/jquery.min.js',
				'/vendor/js/user/home.js'
			]
		];
		$this->load->view('template/layout', $this->layout);
	}
}