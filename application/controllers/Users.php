<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('UserModel', 'user');
		$this->load->model('SecureModel', 'secure');

		if ($this->secure->granted() === false) {
			show_error('You don\'t have permission to view this page', 403, 'Forbidden');
			die;
		}
	}

	public function index()
	{
		$this->loadViewUsers();
	}

	public function search()
	{
		$this->load->helper(['form', 'url']);
		$this->load->library('form_validation');

		$config = [
			[
				'field' => 'field',
				'label' => 'field',
				'rules' => 'required|in_list[slug_name,email,sex,location,role]',
				'errors' => [
					'required' => 'Chưa chọn điều kiện lọc',
					'in_list' => 'Bộ lọc không hợp lệ'
				]
			],
			[
				'field' => 'value',
				'label' => 'value',
				'rules' => 'required|max_length[100]',
				'errors' => [
					'required' => 'Chưa có điều kiện lọc',
					'max_length' => 'Từ khóa quá dài'
				]
			],
			[
				'field' => 'order',
				'label' => 'order',
				'rules' => 'required|in_list[desc,asc]',
				'errors' => [
					'required' => 'Chưa chọn sắp xếp kết quả lọc',
					'in_list' => 'Bộ lọc không hợp lệ'
				]
			]
		];

		$data = $this->input->get(['field', 'value', 'order']);
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="error-input">', '</p>');

		if ($this->form_validation->run() === true) {
			$users = $this->user->listUsers($data['field'], $data['value'], $data['order']);
			$this->loadViewUsers($users);
		}
		else {
			$this->loadViewUsers();
		}
	}

	private function loadViewUsers($users = null)
	{
		$this->load->library('form_validation');

		$modules = [
			VENDOR.'js/global/helper.js',
			VENDOR.'js/global/form-validate.js',
			VENDOR.'js/global/infinite-scroll.js',
			VENDOR.'js/admin/header.js',
			VENDOR.'js/admin/menu.js',
			VENDOR.'js/admin/users.js'
		];
		$this->combineJS($modules, VENDOR.'js/admin/users.js', 0);

		$users = $users ?? $this->user->listUsers();

		$this->load->model('ConstantsModel', 'const');

		// get constants for option fields
		$consts = $this->const->get('cname = "role" or cname = "sex"', 'cvalue');
		for ($i = count($consts) - 1; $i >= 0; $i--)
			$consts[$i] = json_decode($consts[$i]['cvalue'], true);

		if ($users == true) {

			foreach ($users as &$user) {
				if ($user['pp'] !== null)
					$user['pp'] = base_url( explode(',', $user['pp'])[1] );
				else $user['pp'] = base_url('upload/picture_profiles/default_50.png');

				$user['role'] = $consts[0][$user['role']];
			}
		}
		else $users = '<p style="font-size: 2em;text-align: center;">Không tìm thấy kết quả nào</p>';
		
		$content = $this->load->view('admin/users', ['users' => $users, 'opts' => $consts], true);

		$this->layout = [
			'title' => 'Quản lí người dùng - Like-unsplash',
			'header' => $this->genHeaderAdmin(),
			'contents' => $this->genContentAdmin($content),
			'footer' => $this->load->view('admin/footer.html', '', true),
			'css' => [
				'/vendor/css/font-awesome.min.css',
				'/vendor/css/admin/users.css'
			],
			'js' => [
				'/vendor/js/jquery.min.js',
				'/vendor/js/admin/users.min.js'
			]
		];
		$this->load->view('template/layout', $this->layout);
	}

	public function delUser($uid)
	{
		$this->load->model('ConstantsModel', 'const');

		$user = $this->user->get(['uid' => $uid], 'role');
		$roles = $this->const->getRole();

		if (!empty($user) && $roles[ $user[0]['role'] ] == 'admin') {
			echo json_encode([
				'error' => 1,
				'notif' => $this->genNotif('Bạn không thể xóa quản trị viên')
			]);
			return;
		}

		if ($this->user->delete(['uid' => $uid]) == 0) {
			log_message('error', sprintf('%s: %s', __METHOD__, $this->db->last_query()));
			echo json_encode([
				'error' => 1,
				'notif' => $this->genNotif()
			]);
		}
		else echo json_encode([
			'error' => 0,
			'notif' => $this->genNotif('Đã xóa thành viên này')
		]);
	}
}