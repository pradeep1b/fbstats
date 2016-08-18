<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Keen_model extends CI_Model {

	private $baseUrl;

	private $apiKey;

	private $proxy;

	private $pageSize = 20;
	
	private $timeout_count;

	function __construct()
	{
		parent::__construct();
		if (!function_exists('curl_init')){
			die('Sorry cURL is not installed!');
		}

		$this->baseUrl = $this->environment_model->getSetting('keen_url');
		$this->apiKey = $this->environment_model->getSetting('keen_apikey');
		//$this->proxy = $this->environment_model->getSetting('keen_proxy');
		
		$timeout_count = 0;
	}

	public function extractComments($analysisType, $eventCollection, $startDate, $endDate)
	{
		//echo ("In extractComments");
		$fromDate = $startDate . "T00:00:00.000 00:00";
		$toDate = $endDate . "T00:00:00.000 00:00";
		//echo("startdate " . $fromDate . " endDate: " . $toDate );
		$timeframe = array("start" => $fromDate,
							"end" => $toDate
						);
		
		$filters = array();
		
		$url = $this->baseUrl . "/" . $analysisType;

		$fields = array(
				"api_key" => $this->apiKey,
				"event_collection" => $eventCollection,
				"timeframe" =>  json_encode($timeframe),
				"latest" => 100,
				"filters" => $filters
		);
		
		$url = $url . "?" .  http_build_query($fields);
		//print_r($url);
		$results = $this->getJson($url, http_build_query($fields));
		return (!isset($results) && (sizeof($results) == 0)) ? array() : $results->result; 	
	}
	
	public function getRecentEventCount($analysisType, $eventCollection, $startDate, $endDate)
	{
		//echo ("In extractComments");
		$fromDate = $startDate . "T00:00:00.000 00:00";
		$toDate = $endDate . "T00:00:00.000 00:00";
		//echo("startdate " . $fromDate . " endDate: " . $toDate );
		$timeframe = array("start" => $fromDate,
							"end" => $toDate
						);
		
		$filters = array();
		
		$url = $this->baseUrl . "/" . $analysisType;

		$fields = array(
				"api_key" => $this->apiKey,
				"event_collection" => $eventCollection,
				"timeframe" =>  json_encode($timeframe),
				"filters" => $filters
		);
		
		$url = $url . "?" .  http_build_query($fields);
		$results = $this->getJson($url, http_build_query($fields));
		//print_r ($results);
		return $results->result; 	
	}
	
	private function getJson($url, $fields, $timeout = 5)
  	{
		
		$ch = curl_init();
		
		// Allow for gzip encoding
		curl_setopt($ch, CURLOPT_ENCODING , "gzip");
		
		// User agent
		curl_setopt($ch, CURLOPT_USERAGENT, "firstbuild/php");
		
		// Timeout in seconds
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		
		// Include the query
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		
		// Get the content of the URL
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		

		$data = curl_exec($ch); // execute curl request
		
		if(curl_errno($ch))
		{
		  $error_message = curl_error($ch);
		  if(ENVIRONMENT == 'development'){
		    echo '<div class="alert alert-danger"><strong>Error:</strong> ' . $error_message . '</div>';
		  }
		
		  // Count the number of timeouts
		  if($this->_startsWith($error_message, "Operation timed out"))
		  {
		    $this->timeout_count++;
		  }
		
		  curl_close($ch);
		  return FALSE;
		}
		else
		{
		  $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		  curl_close($ch);
		  if($http_status != 200)
		  {
		    return FALSE;
		  }
		  else
		  {
		  	//echo $data;
		    return json_decode($data);
		  }
		}
 	}

  private function _startsWith($haystack, $needle)
  {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
  }
}
