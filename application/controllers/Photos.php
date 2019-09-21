<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Photos extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('TagsModel', 'tag');
		$this->load->model('PhotosModel', 'photo');
	}

	public function delPhoto($pid)
	{
		$this->load->model('SecureModel', 'secure');

		$uid = $this->photo->get(['pid' => $pid], 'uid');
		$uid = $uid[0]['uid'] ?? null;
		// is that right user or admin?
		if ( ( $this->secure->granted() || ($this->secure->isValidCookie() == true && $uid == $_COOKIE['uid']) )
			&& $this->photo->delete(['pid' => $pid]) > 0) {
			$res['notif'] = $this->genNotif('Xóa thành công');
			$res['success'] = true;
		}
		else {
			log_message('error', sprintf('%s, pid: %s', __METHOD__, $pid));
			$res['notif'] = $this->genNotif();
			$res['success'] = false;
		}
		echo json_encode($res);
	}

	/** load more photos by keyword or everything */
	public function loadMore()
	{
		$this->load->model('SecureModel', 'secure');

		$offset = $this->input->get('offset');
		$kw = $this->input->get('kw');
		$kw = json_decode($kw, true) ?: $kw;

		$photos = $this->photo->getPhoto($kw, $offset);

		if ( !empty($photos) ) {
			foreach ($photos as &$photo) {
				$photo['thumbnail'] = explode(',', $photo['thumbnail']);
				array_walk($photo['thumbnail'], function(&$p) {
					$p = base_url($p);
				});
				$photo['content'] = base_url($photo['content']);
					// compare whether current user loved their photos by thmeselves
				$photo['loved_people'] = json_decode($photo['loved_people'], true)['uid'];
				$photo['selfLoved'] = ($this->secure->isValidCookie() === true && null !== $photo['loved_people']) ? array_search($_COOKIE['uid'], $photo['loved_people']) : false;
					// handle user's picture profile
				$photo['picture_profile_50'] = ($photo['picture_profile'] !== null) ? base_url( explode(',', $photo['picture_profile'])[1] ) : base_url('upload/picture_profiles/default_50.png');
				unset($photo['loved_people'], $photo['picture_profile']);
			}
			$photos['next'] = $offset + 12;
			$photos['isValidCookie'] = $this->secure->isValidCookie();
			$photos['granted'] = $this->secure->granted();
		}

		$photos = $this->load->view('template/masonry', ['photos' => $photos], true);
		$photos = substr(substr($photos, 26), 0, -8);
		echo $photos;
	}

	/**
	 * update the database bases on action
	 * @method seeAndLove
	 * @param  string  $action love, view, download
	 * @param  int     $pid
	 */
	public function seeAndLove($action, $pid)
	{
		switch ($action) {
			case 'love':
			$this->load->model('SecureModel', 'secure');
			if ($this->secure->isValidCookie() === false) {
				echo '/dang-nhap';
				return;
			}

			$photo = $this->photo->get(['pid' => $pid], 'loved_people, loved');

			if (!empty($photo)) {
				$lovedPeople = json_decode($photo[0]['loved_people'], true);
				$tmp = $lovedPeople === null ? false : array_search($_COOKIE['uid'], $lovedPeople['uid']);

				if ($tmp === false) {
					$loved = $photo[0]['loved'] + 1;
					$lovedPeople['uid'][] = $_COOKIE['uid'];
					$lovedPeople['time'] = time();
				}
				else {
					$loved = $photo[0]['loved'] - 1;
					unset($lovedPeople['uid'][$tmp]);
				}

				$photo = [
					'loved' => $loved,
					'loved_people' => json_encode($lovedPeople)
				];
				if ($this->photo->update(['pid' => $pid], $photo) == 0)
					log_message( 'error', sprintf('%s error: %s', __METHOD__, $this->db->last_query()) );
			}
			else log_message('error', sprintf('%s: action: %s, pid: %s', __METHOD__, $action, $pid));

			echo 'javascript:void(0)';
			break;
			case 'view':
			$photo = $this->photo->get(['pid' => $pid], 'views');

			if (!empty($photo)) {
				$photo['views'] = $photo[0]['views'] + 1;
				unset($photo[0]);

				if ($this->photo->update(['pid' => $pid], $photo) == 0)
					log_message( 'error', sprintf('%s error: %s', __METHOD__, $this->db->last_query()) );
			}
			else log_message('error', sprintf('%s: action: %s, pid: %s', __METHOD__, $action, $pid));
			break;
			case 'download':
			$photo = $this->photo->get(['pid' => $pid], 'downloaded');

			if (!empty($photo)) {
				$photo['downloaded'] = $photo[0]['downloaded'] + 1;
				unset($photo[0]);

				if ($this->photo->update(['pid' => $pid], $photo) == 0)
					log_message( 'error', sprintf('%s error: %s', __METHOD__, $this->db->last_query()) );
			}
			else log_message('error', sprintf('%s: action: %s, pid: %s', __METHOD__, $action, $pid));
			break;
			default:
			echo 'don\'t deface me!';
			break;
		}
	}

	public function viewLarge($pid)
	{
		if ($this->photo->isExists($pid) > 0) {
			$this->seeAndLove('view', $pid);

			$photo = $this->photo->getLargePhoto($pid);

			$photo['created_at'] = ( new DateTime($photo['created_at']) )->format('d/m/Y');
			$photo['content'] = base_url($photo['content']);
			$photo['picture_profile'] = ($photo['picture_profile'] !== null) ? base_url( explode(',', $photo['picture_profile'])[1] ) : base_url('upload/picture_profiles/default_50.png');
			$photo['w'] = preg_replace('/x\d+/', '', $photo['dim']);
			// compare whether current user loved their photos by thmeselves
			$photo['loved_people'] = json_decode($photo['loved_people'], true)['uid'];
			$photo['selfLoved'] = (null !== $photo['loved_people']) ? array_search($_COOKIE['uid'], $photo['loved_people']) : false;

			echo $this->load->view('template/larger-img', $photo, true);
		}
		else show_error('Ảnh này không có hoặc đã bị xóa');
	}

	public function loadFormUpdate($pid)
	{
		if ($this->input->is_ajax_request()) {

			$photo = $this->photo->getLargePhoto($pid);

			if (!empty($photo)) {

				$this->load->model('SecureModel', 'secure');

				if ( !$this->secure->isValidCookie() || $photo['uid'] != $_COOKIE['uid'] ) {
					echo $this->genNotif('Bạn không có quyền sửa ảnh này');
					return;
				}

				$photo['content'] = base_url( $photo['content'] );
				foreach ($photo as $k => $v) {
					if ( !in_array($k, ['content', 'title', 'tags', 'pid']) )
						unset($photo[$k]);
				}

				echo $this->load->view('user/upload', compact('photo'), true);
			}
			else echo $this->genNotif('Ảnh này không tồn tại');
		}
	}

	/**
	 * @method submitPhoto
	 * @param  int  $pid [if $pid = null, upload a new photo, else update a photo]
	 */
	public function submitPhoto()
	{
		if ($this->validate() === true) {

			$data = $this->input->post();
			$pid = $data['pid'];

			foreach ($data as &$value) $value = strip_tags($value);
			$data['tags'] = array_map('trim', explode(',', preg_replace('/\s{2,}/', ' ', $data['tags']))); // trim all spaces
			$data['tags'] = array_filter($data['tags']);

			if ($pid === '') $pid = $this->upload($data);
			elseif ( $this->tag->saveTag($data['tags']) && $this->photo->update(['pid' => $pid], ['title' => $data['title']]) ) {
				// update existed photo
				$_SESSION['notif'] = $this->genNotif('Sửa thành công');
				redirect($_SERVER['HTTP_REFERER']);
				return;
			}
			else {
				log_message('error', sprintf('%s: %s', __METHOD__, $this->db->last_query()) );
				$_SESSION['notif'] = $this->genNotif();
				redirect($_SERVER['HTTP_REFERER']);
				return;
			}

			// at here the photo is uploaded
			if ($pid === false) {
				$_SESSION['notif'] = $this->genNotif();
				redirect($_SERVER['HTTP_REFERER']);
				return;
			}
			// else {
			// 	$_SESSION['notif'] = $this->genNotif('Đăng thành công');
			// 	redirect($_SERVER['HTTP_REFERER']);
			// }

			// prepare tags id for inserting into photos_tags table
			// handle tag names
			for ($i = 0; $i < count($data['tags']); $i++) {
				$data['tags'][$i] = sprintf("'%s'", $data['tags'][$i]);
			}
			$data['tags'] = implode(',', $data['tags']);
			$tagIds = $this->tag->get(sprintf('tag_name in (%s)', $data['tags']), 'tag_id');

			if ( !empty($tagIds) ) {
				$this->load->model('PhotosTagsModel', 'pt');

				$data = $tagIds;
				for ($i = count($data) - 1; $i >= 0; --$i)
					$data[$i]['pid'] = $pid;

				if ( $this->pt->savePhotoTags($data) == true ) {
					$_SESSION['notif'] = $this->genNotif('Đăng thành công');
					redirect($_SERVER['HTTP_REFERER']);
				}
				else {
					$_SESSION['notif'] = $this->genNotif();
					redirect($_SERVER['HTTP_REFERER']);
				}

				// if ( $this->pt->savePhotoTags($data) == false ) {
				// 	log_message('error', sprintf('%s: %s', __METHOD__, $this->db->last_query()) );
					// $_SESSION['notif'] = $this->genNotif();
					// redirect($_SERVER['HTTP_REFERER']);
				// }
				// else {
				// 	$_SESSION['notif'] = $this->genNotif('Đăng thành công');
				// 	redirect($_SERVER['HTTP_REFERER']);
				// }
			}
			else {// if user input harmful tags, then db cannot select them
				log_message('error', sprintf('%s: %s', __METHOD__, $this->db->last_query()) );
				redirect($_SERVER['HTTP_REFERER']);
			}
		}
		else {
			redirect($_SERVER['HTTP_REFERER']); // temporary work around
			// NEED TO CREATE ERROR FORM UPLOAD PAGE
		}
	}

	/** upload photo */
	private function upload($extData)
	{
		$config = [
			'upload_path' 	 => './upload/photos',
			'allowed_types' => 'gif|jpg|png',
			'max_size'      => 10240,
			'min_width'     => 400,
			'min_height'    => 100,
			'file_name' 	 => uniqid(),
		];

		$this->load->library('upload', $config);

		if ($this->upload->do_upload('image')) {

			$upload = $this->upload->data();
			$thumbnail = $this->resizePhoto($upload['full_path'], [260, 315, 410], 'upload/photos/');

			if ($thumbnail == true) {
				$data = [
					'uid' => $_COOKIE['uid'], // see who posts this photo
					'size' => $upload['file_size'],
					'dim' => sprintf('%ux%u', $upload['image_width'], $upload['image_height']),
					'content' => sprintf('%s%s', 'upload/photos/', $upload['file_name']),
					'thumbnail' => implode(',', $thumbnail),
					'title' => $extData['title'],
				];

				if ($this->tag->saveTag($extData['tags']) == false) {
					log_message('error', sprintf('%s: %s', __METHOD__, $this->db->last_query()));
					$this->delPhotos($upload);
					return false;
					// die($this->db->last_query());
					// redirect($_SERVER['HTTP_REFERER']);
				}

				if ($pid = $this->photo->insert($data)) {
					// redirect($_SERVER['HTTP_REFERER']);
					return $pid;
				}
				else { // add photo failure
					log_message('error', sprintf('SQL: add photo failed: %s', $this->db->last_query()));
					$this->delPhotos($upload);
					return false;
					// show_error('Đã xảy ra lỗi gì đó, vui lòng thử lại sau', 500, 'Đã xảy ra lỗi');
				}
			}
			else { // resize failure
				$this->delPhotos($upload);
				return false;
				// redirect($_SERVER['HTTP_REFERER']);
			}
		}
		else {
			log_message('error', $this->upload->display_errors());
			return false;
			// echo $this->upload->display_errors();
			// sleep(1);
			// redirect($_SERVER['HTTP_REFERER']); // temporary work around
		}
	}

	public function loadFormUpload()
	{
		if ($this->input->is_ajax_request())
			echo $this->load->view('user/upload', '', true);
	}

	private function validate()
	{
		$this->load->helper(['form', 'url']);
		$this->load->library('form_validation');

		$config = [
			[
				'field' => 'title',
				'label' => 'tiêu đề',
				'rules' => "required|max_length[255]|trim",
				'errors' => [
					'required' => 'Phải có %s của ảnh',
					'max_length' => '%s quá dài'
				]
			],
			[
				'field' => 'tags',
				'label' => 'thẻ',
				'rules' => [
					'required',
					[
						'tags_length_callable',
						function($val) {
							$val = explode(',', $val);
							return count($val) > 5 ? false : true;
						}
					],
					'trim'
				],
				'errors' => [
					'required' => 'Phải gắn %s cho ảnh',
					'tags_length_callable' => 'Tối đa 5 %s'
				]
			]
		];

		$this->form_validation->set_rules($config);

		return $this->form_validation->run();
	}
}