<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Signup extends MY_Controller {

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * load signup view first
	 * @method index
	 */
	public function index()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
			$this->signup();
		else $this->loadViewSignup();
	}

	public function signup()
	{
		$this->load->helper(['form', 'url']);
		$this->load->library(['form_validation', 'slug']);
		$this->load->model('UserModel', 'user');

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
				'field' => 'password',
				'label' => 'Mật khẩu',
				'rules' => 'required|min_length[6]|trim',
				'errors' => [
					'required' => 'Không được để trống %s',
					'min_length' => '%s quá ngắn'
				]
			]
		];

		$this->form_validation->set_rules($config);

		if ($this->form_validation->run() === true) {
			$data = $this->input->post();
			$info = $this->user->get(['email' => $data['email']], 'count(email) as num');
			if ($info[0]['num'] == 0) {
				foreach ($data as &$value) $value = strip_tags($value);

				$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT, ['cost' => 10]);
				$data['slug_name'] = $this->slug->slugify( sprintf('%s %s', $data['lname'], $data['fname']) );

				if ($this->user->insert($data)) {
					$info = $this->user->get(['email' => $data['email']], 'uid, password, slug_name');
					$cookie = [
						'uid' => ['value' => $info[0]['uid'], 'expire' => strtotime('+30 days'), 'httponly' => false],
						'pwd' => ['value' => $info[0]['password'], 'expire' => strtotime('+30 days')],
						'username' => ['value' => $info[0]['slug_name'], 'expire' => strtotime('+30 days')],
					];
					$this->setCookie($cookie);

					redirect('/');
				}
				else show_error('Đã xảy ra lỗi gì đó, vui lòng thử lại sau', 500, 'Đã xảy ra lỗi');
			}
			else {
				$this->error = ['errorCode' => 1, 'errorVal' => $data['email']];
				$this->loadViewSignup();
			}
		}
		else $this->loadViewSignup();
	}

	public function loadViewSignup()
	{
		$this->load->model('SecureModel', 'secure');

		if ( !$this->secure->isValidCookie() ) {

			$this->layout = [
				'title' => 'Đăng kí - Like-unsplash',
				'header' => '',
				'contents' => $this->load->view('template/signin.php', $this->genContent(), true),
				'footer' => '',
				'css' => [
					'/assets/css/font-awesome.min.css',
					'/assets/css/global/signin.css'
				],
				'js' => [
					'/assets/js/user/signup.min.js'
				]
			];
			$this->load->view('template/layout', $this->layout);
		}
		else redirect('/');
	}

	/**
	 * generate content for signup page
	 * @method genContent
	 * @return contents
	 */
	private function genContent()
	{
		$this->load->library('form_validation');

		switch ($this->error['errorCode']) {
			case 1:
			$content['email'] = $this->error['errorVal'];
			$content['emailError'] = sprintf('<p id="text-error" class="error-input">%s</p>', 'Email này đã tồn tại');
			break;
			default:
			$content['email'] = set_value('email');
			$content['emailError'] = form_error('email', '<p class="error-input">', '</p>');
			break;
		}

		return [
			'action' => base_url('dang-ki'),
			'introText' => 'Đăng kí để trở thành người đóng góp cho cộng đồng ảnh lớn nhất thế giới',
			'redirect' => sprintf('<p>Đã có tài khoản? Đăng nhập <a href="%s">tại đây</a></p>
				<p>Đăng kí bằng</p>',
				base_url('dang-nhap')),
			'items' => sprintf('
				<div class="form-item"><input type="text" name="lname" placeholder="Họ" value="%s" autofocus>%s</div>
				<div class="form-item"><input type="text" name="fname" placeholder="Tên" value="%s">%s</div>
				<div class="form-item"><input type="text" name="email" placeholder="Email đăng kí" value="%s">%s</div>
				<div class="form-item"><input type="password" name="password" placeholder="Mật khẩu">%s</div>
				<div class="form-item"><input type="submit" value="Đăng kí"></div>',
				set_value('lname'), form_error('lname', '<p class="error-input">', '</p>'),
				set_value('fname'), form_error('fname', '<p class="error-input">', '</p>'),
				$content['email'], $content['emailError'],
				form_error('password', '<p class="error-input">', '</p>'))
		];
	}
}