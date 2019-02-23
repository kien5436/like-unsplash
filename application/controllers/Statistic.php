<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Statistic extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('UserModel', 'user');
		$this->load->model('PhotosModel', 'photo');
		$this->load->model('TagsModel', 'tag');
		$this->load->model('SecureModel', 'secure');

		if ($this->secure->granted() === false) {
			show_error('You don\'t have permission to view this page', 403, 'Forbidden');
			die;
		}
	}

	public function index()
	{
		$metrics = [
			'Người dùng' => $this->user->analyse(),
			'Ảnh' => $this->photo->analyse(),
			'Thẻ' => $this->tag->analyse()
		];

		$this->layout = [
			'title' => 'Thống kê - Like-unsplash',
			'header' => $this->genHeaderAdmin(),
			'contents' => $this->genContentAdmin($this->load->view('admin/statistics', compact('metrics'), true)),
			'footer' => $this->load->view('admin/footer.html', '', true),
			'css' => [
				'/vendor/css/font-awesome.min.css',
				'/vendor/css/admin/statistics.css',
			],
			'js' => [
				'/vendor/js/jquery.min.js',
				'/vendor/js/admin/statistics.min.js'
			]
		];
		$this->load->view('template/layout', $this->layout);
	}

	/**
	 * get frequency of metrics from specific table
	 * by passing number, hacker cannot guess table name in database
	 * @method getMetrics
	 * @param  int        $table number stand for table
	 * @return json
	 */
	public function getMetrics($table = 0)
	{
		$from = date('Y-m-d 00:00:00', strtotime('-30days')); $to = date('Y-m-d 00:00:00');
		$metrics = [];

		switch ($table) {
			case 1: // user
				$metrics[0]['data'] = $this->handleMetrics($this->user->getMetrics($from, $to));
				$metrics[0]['label'] = 'Người dùng';
				break;
			case 2: // photos
				$metrics[0]['data'] = $this->handleMetrics($this->photo->getMetrics($from, $to));
				$metrics[0]['label'] = 'Ảnh';
				break;
			case 3: // tags
				$metrics[0]['data'] = $this->handleMetrics($this->tag->getMetrics($from, $to));
				$metrics[0]['label'] = 'Thẻ';
				break;
			case 0: // all
				$labels = ['user' => 'Người dùng', 'photo' => 'Ảnh', 'tag' => 'Thẻ'];
				foreach ($labels as $k => $v) { 
					$metrics[] = [
						'data' => $this->handleMetrics($this->{$k}->getMetrics($from, $to)),
						'label' => $v
					]; 
				}
				break;
		}

		echo json_encode($metrics);
	}

	/**
	 * count frequency of metrics
	 * @method handleMetrics
	 * @param  array        $metrics
	 * @return array
	 */
	private function handleMetrics($metrics)
	{
		$count = array_fill(0, 30, 0);

		for ($i = 30; $i > 0; $i--) {
			$date = strtotime(sprintf('-%ddays', $i));

			for ($j = 0; $j < count($metrics); $j++) {
				if ( date('Y-m-d', $date) == substr($metrics[$j]['created_at'], 0, 10) ) $count[30-$i]++;
			}
		}

		return $count;
	}
}