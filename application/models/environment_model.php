<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Environment_model extends CI_Model {

	private $env;

	function __construct()
	{
		parent::__construct();
		$this->env = strtolower(fb_env());

		$valid = $this->config->item('env');
		if(!in_array($this->env, $valid))
		{
			redirect('', 'refresh');
			die();
		}
	}

	function getSetting($item) {
		$configArray = $this->config->item($item);
		return $configArray[$this->env];
	}

	function getENV() {
		return $this->env;
	}
}
