<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller {

	private $doing = 'info';

	function __construct()
	{
		parent::__construct();
		$this->load->model('UserModel', 'user');
	}

	public function index()
	{
		$this->loadViewProfile();
	}

	public function changePassword()
	{
		$data = $this->input->post();
		foreach ($data as $k => &$v) $v = strip_tags($v);

		$this->load->helper(['form', 'url']);
		$this->load->library('form_validation');

		$config = [
			[
				'field' => 'old',
				'label' => 'Mật khẩu cũ',
				'rules' => [
					'required',
					'min_length[6]',
					[
						'valid_pwd_callable',
						function ($val) {
							$oldPwd = $this->user->get(['uid' => $_COOKIE['uid']], 'password')[0]['password'];
							return password_verify($val, $oldPwd);
						}
					],
					'trim'
				],
				'errors' => [
					'required' => 'Không được để trống %s',
					'min_length' => 'Mật khẩu quá ngắn',
					'valid_pwd_callable' => '%s không đúng'
				]
			],
			[
				'field' => 'new',
				'label' => 'Mật khẩu mới',
				'rules' => 'required|min_length[6]|trim',
				'errors' => [
					'required' => 'Không được để trống %s',
					'min_length' => 'Mật khẩu quá ngắn'
				]
			],
			[
				'field' => 'verify',
				'label' => 'Xác nhận mật khẩu',
				'rules' => 'required|matches[new]|trim',
				'errors' => [
					'required' => 'Không được để trống %s',
					'matches' => '%s không khớp với mật khẩu mới'
				]
			],
		];
		
		$this->form_validation->set_rules($config);

		if ($this->form_validation->run() == true) {
			 
			$data['new'] = password_hash($data['new'], PASSWORD_DEFAULT, ['cost' => 10]);
			
			if ($this->user->update(['uid' => $_COOKIE['uid']], ['password' => $data['new']]) == true) {
				$_SESSION['notif'] = $this->genNotif('Đổi mật khẩu thành công');
				$cookie = ['pwd' => ['value' => $data['new'], 'expire' => strtotime('+30 days')]];
				$this->setCookie($cookie);

				redirect($_SERVER['HTTP_REFERER']);
			}
			else {
				log_message('error','failed to change user\'s password');
				show_error('Đã xảy ra lỗi gì đó, vui lòng thử lại sau', 500, 'Đã xảy ra lỗi');
			}
		}
		else {
			$this->doing = 'change-password';
			$this->loadViewSettings($_COOKIE['username'], $_COOKIE['uid']);
		}
	}

	public function updateProfile()
	{
		$this->load->helper(['form', 'url']);
		$this->load->library('form_validation');

		$config = [
			[
				'field' => 'lname',
				'label' => 'Họ',
				'rules' => 'required|max_length[40]|trim',
				'errors' => [
					'required' => 'Không được để trống %s',
					'max_length' => '%s quá dài'
				]
			],
			[
				'field' => 'fname',
				'label' => 'Tên',
				'rules' => 'required|max_length[10]|trim',
				'errors' => [
					'required' => 'Không được để trống %s',
					'max_length' => '%s quá dài'
				]
			],
			[
				'field' => 'email',
				'label' => 'Email',
				'rules' => "required|regex_match[/[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/]|trim",
				'errors' => [
					'required' => 'Không được để trống %s',
					'regex_match' => '%s không hợp lệ'
				]
			],
			[
				'field' => 'interests',
				'label' => 'Sở thích',
				'rules' => "max_length[100]|trim",
				'errors' => [
					'max_length' => 'Có vẻ như bạn có quá nhiều %s',
				]
			],
			[
				'field' => 'sex',
				'label' => 'Giới tính',
				'rules' => "in_list[0,1,2]|trim",
				'errors' => [
					'in_list' => '%s không phù hợp',
				]
			],
			[
				'field' => 'location',
				'label' => 'Địa điểm',
				'rules' => "max_length[255]|trim",
				'errors' => [
					'max_length' => '%s quá dài',
				]
			],
		];

		$this->form_validation->set_rules($config);

		if ($this->form_validation->run() === true) {			
			$data = $this->input->post();
			foreach ($data as &$value) $value = strip_tags($value);
			$oldPP = null;

			if ( is_uploaded_file($_FILES['pp']['tmp_name']) ) {
				unset($config);
				$config = [
					'upload_path' 	 => './upload/picture_profiles',
					'allowed_types' => 'gif|jpg|png',
					'max_width'     => 260,
					'file_name' 	 => uniqid(),
				];

				$this->load->library('upload', $config);

				if ($this->upload->do_upload('pp')) {
					$upload = $this->upload->data();
					$thumbnail = $this->resizePhoto($upload['full_path'], [50, 120], 'upload/picture_profiles/');

					if ($thumbnail == true) {
						$data['picture_profile'] = sprintf( '%s%s,%s', 'upload/picture_profiles/', $upload['file_name'], implode(',', $thumbnail) );
						$oldPP = $this->user->get(['uid' => $_COOKIE['uid']], 'picture_profile');
					}
					else { // resize failure
						log_message('error','resize avatar failed');
						$this->delPhotos($upload);
						redirect($_SERVER['HTTP_REFERER']);
					}
				}
				else {
					log_message('error','upload avatar failed');
					echo $this->upload->display_errors();
					// header('refresh:1;url='.$_SERVER['HTTP_REFERER']); // temporary work around
				}
			}

			if ($this->user->update(['uid' => $_COOKIE['uid']], $data) == true) {
				if ($oldPP !== null) {
					$oldPP = explode(',', $oldPP[0]['picture_profile'])[0];
					$rootdir = str_replace('upload/picture_profiles/', '', $upload['file_path']);
					$this->delPhotos( sprintf('%s%s', $rootdir, str_replace('.', '*.', $oldPP)) );
				}
				redirect($_SERVER['HTTP_REFERER']);
			}
			else { // update user's info failure
				log_message('error','failed to update user\'s info');
				show_error('Đã xảy ra lỗi gì đó, vui lòng thử lại sau', 500, 'Đã xảy ra lỗi');
				// redirect($_SERVER['HTTP_REFERER']);
			}
		}
		else {
			$this->doing = 'settings';
			$this->loadViewSettings($_COOKIE['username'], $_COOKIE['uid']);
		}
	}

	public function loadViewSettings($username, $uid)
	{
		$modules = [
			VENDOR.'js/global/helper.js',
			VENDOR.'js/global/form-validate.js',
			VENDOR.'js/user/header.js',
			VENDOR.'js/global/tab.js',
			VENDOR.'js/user/settings.js'
		];
		$this->combineJS($modules, VENDOR.'js/user/settings.js', 0);
		
		$this->load->library('form_validation');
		$this->load->model('SecureModel', 'secure');
		$this->load->model('ConstantsModel', 'constant');
		$this->load->model('TagsModel', 'tag');

		$header['tags'] = $this->tag->randomTags();
		$header['isValidCookie'] = $this->secure->isValidCookie();
		$header['granted'] = $this->secure->granted();
		$user = $this->user->get(['uid' => $uid, 'slug_name' => $username], 'email, lname, fname, slug_name, picture_profile, sex, location, interests');
		
		if ($user == true && isset($_COOKIE['uid']) && $uid == $_COOKIE['uid']) {
			$data = $user[0];
			$data['doing'] = $this->doing;
			$data['asex'] = json_decode( $this->constant->get(['cname' => 'sex'], 'cvalue')[0]['cvalue'] );

			for ($i = 0; $i < count($data['asex']); $i++) {
				if ($data['sex'] !== null && $data['sex'] == $i) {
					$data['sex'] = $data['asex'][$i];
					break;
				}
			}

			if ($data['picture_profile'] !== null) {
				$pp = explode(',', $data['picture_profile']);
				$data['picture_profile'] = base_url($pp[2]);
				$header['pp'] = base_url($pp[1]);
			}
			else {
				$data['picture_profile'] = base_url('upload/picture_profiles/default_120.png');
				$header['pp'] = base_url('upload/picture_profiles/default_50.png');
			}

			$this->layout = [
				'title' => 'Tài khoản - Like-unsplash',
				'header' => $this->load->view('user/header', $header, true),
				'contents' => $this->load->view('user/settings', $data, true),
				'footer' => '',
				'css' => [
					'/vendor/css/font-awesome.min.css',
					'/vendor/css/user/settings.css'
				],
				'js' => [
					'/vendor/js/jquery.min.js',
					'/vendor/js/user/settings.js'
				]
			];
			$this->load->view('template/layout', $this->layout);
		}
		else show_404();		
	}

	public function loadViewProfile($username, $uid)
	{
		$modules = [
			VENDOR.'js/global/helper.js',
			VENDOR.'js/global/form-validate.js',
			VENDOR.'js/global/infinite-scroll.js',
			VENDOR.'js/user/header.js',
			VENDOR.'js/global/tab.js',
			VENDOR.'js/global/image.js',
			VENDOR.'js/user/profile.js'
		];
		$this->combineJS($modules, VENDOR.'js/user/profile.js', 0);

		$user = $this->user->get(['uid' => $uid, 'slug_name' => $username], 'uid, slug_name, concat_ws(" ", lname, fname) as username, picture_profile, location, interests');
		isset($_COOKIE['uid']) && $currentUser = $this->user->get(['uid' => $_COOKIE['uid']], 'picture_profile');
		
		$this->load->model('PhotosModel', 'photo');
		$photos = $this->photo->getPhoto(['uid' => $uid]);

		if ( !empty($user) ) {
			$this->load->model('SecureModel', 'secure');
			$this->load->model('TagsModel', 'tag');

			$header['tags'] = $this->tag->randomTags();
			$header['isValidCookie'] = $this->secure->isValidCookie();
			$header['granted'] = $this->secure->granted();

			$data['user'] = $user[0];

			if ($data['user']['picture_profile'] !== null) {
				$pp = explode(',', $data['user']['picture_profile']);
				$data['user']['picture_profile'] = base_url($pp[0]);
				$data['user']['picture_profile_50'] = base_url($pp[1]);
			}
			else {
				$data['user']['picture_profile'] = base_url('upload/picture_profiles/default_260.png');
				$data['user']['picture_profile_50'] = base_url('upload/picture_profiles/default_50.png');
			}

			if (!empty($currentUser) && $currentUser[0]['picture_profile'] !== null)
				$header['pp'] = base_url( explode(',', $currentUser['0']['picture_profile'])[1] );
			else $header['pp'] = base_url('upload/picture_profiles/default_50.png');

			if ( !empty($photos) ) {
				foreach ($photos as &$photo) {
					$photo['thumbnail'] = explode(',', $photo['thumbnail']);
					array_walk($photo['thumbnail'], function(&$p) {
						$p = base_url($p);
					});
					$photo['content'] = base_url($photo['content']);
					// compare whether current user loved their photos by thmeselves
					$photo['loved_people'] = json_decode($photo['loved_people'], true)['uid'];
					$photo['selfLoved'] = ($header['isValidCookie'] === true) ? array_search($_COOKIE['uid'], $photo['loved_people']) : false;
					// handle user's picture profile
					$photo['picture_profile_50'] = ($photo['picture_profile'] !== null) ? base_url( explode(',', $photo['picture_profile'])[1] ) : base_url('upload/picture_profiles/default_50.png');
					unset($photo['loved_people'], $photo['picture_profile']);
				}
				$photos['next'] = 12;
				$photos['isValidCookie'] = $header['isValidCookie'];
				$photos['granted'] = $this->secure->granted();
				
				$data['masonry'] = $this->load->view('template/masonry', ['photos' => $photos], true);
				$data['modal'] = $this->load->view('template/modal', '', true);
			}

			$this->layout = [
				'title' => 'Trang cá nhân - Like-unsplash',
				'header' => $this->load->view('user/header', $header, true),
				'contents' => $this->load->view('user/profile', $data, true),
				'footer' => '',
				'css' => [
					'/vendor/css/font-awesome.min.css',
					'/vendor/css/user/profile.css'
				],
				'js' => [
					'/vendor/js/jquery.min.js',
					'/vendor/js/user/profile.js'
				]
			];
			$this->load->view('template/layout', $this->layout);
		}
		else show_404();
	}
}