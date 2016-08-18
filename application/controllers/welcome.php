<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$data = array();
		$this->load->model('keen_model');
		
		$stats = array(
			"Signup: User Created" => "Users Created",
			"Post: Published" => "Posts Published",
			"Activity Stream: Reply to Post" => "Activity - Replied to the Posts",
			"Content: Published" => "Published the content",
			"Challenge: Requirements Viewed" => "Ciewed challenge requirements",
			"Vote" => "Voted the ideas",
			"Vote up" => "Voted up",
			"Login: Success" => "Logged in their accounts"
		);

		$currDate = date('Y-m-j');
		$lastweek=date('Y-m-j', strtotime("-6 days"));
		
		$data['stats'] = array();
		foreach($stats as $event => $text)
		{
			$stat = array();
			$stat['event'] = $event;
			$stat['text'] = $text;
			$stat['number'] = $this->keen_model->getRecentEventCount("count", $event, $lastweek, $currDate);
			
			if($stat['number'] != FALSE)
			{
				$data['stats'][] = $stat;
			}
		}

		$this->load->view('welcome_message', $data);
	}
}
