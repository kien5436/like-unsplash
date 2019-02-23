<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Signin extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * load signin view first
	 * @method index
	 */
	public function index()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
			$this->signin();
		else $this->loadViewSignin();
	}

	public function signout()
	{
		$cookie = [];
		foreach ($_COOKIE as $key => $value) {
			$cookie[$key] = [
				'value' => '',
				'expire' => 1
			];
		}
		$this->setCookie($cookie);
		redirect($_SERVER['HTTP_REFERER']);
	}

	public function signin()
	{
		$this->load->helper(['form', 'url']);
		$this->load->library('form_validation');

		$config = [
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
			
			foreach ($data as &$value) $value = strip_tags($value);

			$this->load->model('UserModel', 'user');
			
			if ( $info = $this->user->get(['email' => $data['email']], 'uid, password, role, slug_name') ) {
				if (password_verify($data['password'], $info[0]['password']) === true) {
					$cookie = [
						'uid' => ['value' => $info[0]['uid'], 'expire' => strtotime('+30 days'), 'httponly' => false],
						'pwd' => ['value' => $info[0]['password'], 'expire' => strtotime('+30 days')],
						'username' => ['value' => $info[0]['slug_name'], 'expire' => strtotime('+30 days')],
					];
					$this->setCookie($cookie);
					
					if ($info[0]['role'] == 1) redirect('quan-tri');
					else redirect('/');
				}
				else { // incorrect password
					$this->error = ['errorCode' => 2, 'errorVal' => $data['email']];
					$this->loadViewSignin();
				}
			}
			else { // invalid email
				$this->error = ['errorCode' => 1, 'errorVal' => $data['email']];
				$this->loadViewSignin();
			}
		}
		else $this->loadViewSignin();
	}

	private function loadViewSignin()
	{
		$this->load->model('SecureModel', 'secure');
		
		if ( !$this->secure->isValidCookie() ) {

			$this->layout = [
				'title' => 'Đăng nhập - Like-unsplash',
				'header' => '',
				'contents' => $this->load->view('template/signin.php', $this->genContent(), true),
				'footer' => '',
				'css' => [
					'/vendor/css/font-awesome.min.css',
					'/vendor/css/global/signin.css'
				],
				'js' => [
					'/vendor/js/user/signin.min.js'
				]
			];
			$this->load->view('template/layout', $this->layout);
		}
		else redirect('/');
	}

	/**
	 * generate content for signin page
	 * @method genContent
	 * @return contents
	 */
	private function genContent()
	{
		$this->load->library('form_validation');
		
		switch ($this->error['errorCode']) {
			case 1:
			case 2:
				$content['email'] = $this->error['errorVal'];
				$content['emailError'] = sprintf('<p id="text-error" class="error-input">%s</p>', 'Tài khoản không tồn tại');
				$content['pwdError'] = '';
				break;
			default:
				$content['email'] = set_value('email');
				$content['emailError'] = form_error('email', '<p id="text-error" class="error-input">', '</p>');
				$content['pwdError'] = form_error('password', '<p id="password-error" class="error-input">', '</p>');
				break;
		}

		return [
			'action' => base_url('dang-nhap'),
			'introText' => 'Chào mừng bạn trở lại',
			'redirect' => sprintf('<p>Chưa có tài khoản? Đăng kí <a href="%s">tại đây</a></p>
				<p>Đăng nhập bằng</p>',
				base_url('dang-ki')),
			'items' => sprintf(
				'<div class="form-item"><input type="text" name="email" placeholder="Email đăng nhập" value="%s" autofocus>%s</div>
				<div class="form-item"><input type="password" name="password" placeholder="Mật khẩu">%s</div>
				<div class="form-item"><input type="submit" value="Đăng nhập"></div>',
				$content['email'], $content['emailError'], $content['pwdError']
			)
		];
	}
}