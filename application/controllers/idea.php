<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Idea extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if (!function_exists('curl_init')){
			die('Sorry cURL is not installed!');
		}

		$this->load->helper(array('form'));
		$this->load->library('form_validation');
	}

	public function index()
	{
		$this->load->view('datepicker');
	}
	
	public function commentsearch()
	{
		if($_SERVER['REQUEST_METHOD'] === 'GET')
		{
			redirect(fb_url(array('idea', 'comment')), 'refresh');
			return;
		}
		$this->form_validation->set_rules('datefilter', 'DateRange', 'required|min_length[7]|max_length[50]');
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('datepicker', array('error' => true, 'results' => array()));
		}
		else
		{
			$data = array();
			$datefilter = $this->input->post('datefilter');
			$data['datefilter'] = $datefilter;
			$daterange = explode("to", $datefilter);
			$data['wasSearch'] = true;
			
			$this->load->model('keen_model');
			$data['results'] = $this->keen_model->extractComments("extraction", "Activity Stream: Reply to Post", trim($daterange[0]) , trim($daterange[1]) );
			//print_r( $data['results']); 
			$this->load->view('datepicker',$data);
		}
	}

	private function _getPage()
	{
		$page = 1;
		if(isset($_GET['page'])) {
			$page = intval($this->input->get('page'));
		}

		if($page < 1)
		{
			$page = 1;
		}

		return $page;
	}
}
