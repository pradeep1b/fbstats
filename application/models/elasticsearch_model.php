<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Elasticsearch_model extends CI_Model {

  private $baseurl;

  private $timeout_count;

  private $searchUrl;

  private $perPage;

  function __construct()
  {
    parent::__construct();
    if (!function_exists('curl_init')){
      die('Sorry cURL is not installed!');
    }

    $this->baseurl = $this->config->item('elasticsearch');
    $this->timeout_count = 0;

    $indexes = array();
    for($i = -1; $i < 33; $i++) {
      $indexes[] = "logstash-" .  date("Y.m.d", strtotime('-'. $i .' days'));
    }
	print_r($indexes);
    $this->searchUrl = $this->baseurl . '/' . 'content_index/_search?ignore_unavailable=true';

	echo ( $this->searchUrl);
    $this->perPage = 50;
  }

  public function getLastWeekComments(){
  	
  	
  }  

  private function buildPageRequest($terms, $perpage, $page, $fqueries = array())
  {
    // TODO: Clean up this very messy way of creating the JSON search request
    $data = array();
    $q["query_string"]["query"] = "*";
    $data["query"]["filtered"]["query"]["bool"]["should"] = array($q);

    foreach($terms as $key => $value)
    {
      $bar = array();
      $bar["terms"][$key] = array($value);
      $data["query"]["filtered"]["filter"]["bool"]["must"][] = $bar;
    }

    foreach($fqueries as $fquery)
    {
    	$bar = array();
    	$bar["fquery"]["query"]["query_string"]["query"] = $fquery;
    	$bar["fquery"]["_cache"] = 'true';
    	$data["query"]["filtered"]["filter"]["bool"]["must"][] = $bar;
    }

    $s["@timestamp"]["order"] = "desc";
    $s["@timestamp"]["ignore_unmapped"] = true;
    $data["sort"] = array($s);

    $data['size'] = $perpage;
    $data['from'] = $perpage * $page;

    return json_encode($data);
  }

  private function buildTermsRequest($terms, $field, $fqueries=array(), $limit = 1000, $time=NULL, $order="term")
  {
    // TODO: Clean up this very messy way of creating the JSON search request
    $data = array();
    $data["facets"]["terms"]["terms"]["field"] = $field;
    $data["facets"]["terms"]["terms"]["size"] = $limit;
    $data["facets"]["terms"]["terms"]["order"] = $order;

    $q["query_string"]["query"] = "*";
    $data["facets"]["terms"]["facet_filter"]["fquery"]["query"]["filtered"]["query"]["bool"]["should"] = array($q);

    foreach($fqueries as $fquery)
    {
    	$bar = array();
    	$bar["fquery"]["query"]["query_string"]["query"] = $fquery;
    	$bar["fquery"]["_cache"] = 'true';
    	$data["facets"]["terms"]["facet_filter"]["fquery"]["query"]["filtered"]["filter"]["bool"]["must"][] = $bar;
    }

    foreach($terms as $key => $value)
    {
      $bar = array();
      $bar["terms"][$key] = array($value);
      $data["facets"]["terms"]["facet_filter"]["fquery"]["query"]["filtered"]["filter"]["bool"]["must"][] = $bar;
    }

    if($time != NULL)
    {
    	$t["range"]["@timestamp"]["from"] = $time;
    	$data["facets"]["terms"]["facet_filter"]["fquery"]["query"]["filtered"]["filter"]["bool"]["must"][] = $t;
    }

    $data['size'] = 0;

    return json_encode($data);
  }

  private function buildFQueryPageRequest($terms, $optFilter, $fqueries, $perpage, $page,  $time=NULL)
  {
  	// TODO: Clean up this very messy way of creating the JSON search request
  	$data = array();
  	$q["query_string"]["query"] = "*";
  	$data["query"]["filtered"]["query"]["bool"]["should"] = array($q);

  	foreach($terms as $key => $value)
  	{
  		$bar = array();
  		$bar["terms"][$key] = array($value);
  		$data["query"]["filtered"]["filter"]["bool"]["must"][] = $bar;
  	}

  	foreach($fqueries as $fquery)
  	{
  		$bar = array();
  		$bar["fquery"]["query"]["query_string"]["query"] = $fquery;
  		$bar["fquery"]["_cache"] = 'true';
  		$data["query"]["filtered"]["filter"]["bool"]["must"][] = $bar;

  	}

  	foreach($optFilter as $key => $value)
  	{
  		foreach($value as $val)
  		{
  			$bar = array();
  			$bar["terms"][$key] = array($val);
  			$data["query"]["filtered"]["filter"]["bool"]["should"][] = $bar;
  		}
  	}

  	if($time != NULL)
  	{
  		$t["range"]["@timestamp"]["from"] = $time;
  		$data["query"]["filtered"]["filter"]["bool"]["must"][] = $t;
  	}


  	$s["@timestamp"]["order"] = "desc";
  	$s["@timestamp"]["ignore_unmapped"] = true;
  	$data["sort"] = array($s);

  	$data['size'] = $perpage;
  	$data['from'] = $perpage * $page;

  	return json_encode($data);
  }

  private function buildCountRequest($terms, $field, $fieldvalue, $time)
  {
    // TODO: Clean up this very messy way of creating the JSON search request
    $data = array();
    $data["facets"]["terms"]["terms"]["field"] = $field;
    $data["facets"]["terms"]["terms"]["size"] = 1;
    $data["facets"]["terms"]["terms"]["order"] = "count";

    $q["query_string"]["query"] = "*";
    $data["facets"]["terms"]["facet_filter"]["fquery"]["query"]["filtered"]["query"]["bool"]["should"] = array($q);

    $t["range"]["@timestamp"]["from"] = $time;
    $data["facets"]["terms"]["facet_filter"]["fquery"]["query"]["filtered"]["filter"]["bool"]["must"] = array($t);

    $terms[$field] = $fieldvalue;
    foreach($terms as $key => $value)
    {
      $bar = array();
      $bar["terms"][$key] = array($value);
      $data["facets"]["terms"]["facet_filter"]["fquery"]["query"]["filtered"]["filter"]["bool"]["must"][] = $bar;
    }

    $data['size'] = 0;

    return json_encode($data);
  }

  private function getJson($url, $json, $timeout = 1, $fail_quick = true)
  {
    if($fail_quick && $this->timeout_count >= 1)
    {
      // If we have already timed out requests, give up on future requests
      // Elasticsearch should respond quickly, or not at all
      return FALSE;
    }

    $ch = curl_init();

    // Allow for gzip encoding
    curl_setopt($ch, CURLOPT_ENCODING , "gzip");

    // User agent
    curl_setopt($ch, CURLOPT_USERAGENT, "wcaadmin/php");

    // Timeout in seconds
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

    // Include the query
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

    // Get the content of the URL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
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
