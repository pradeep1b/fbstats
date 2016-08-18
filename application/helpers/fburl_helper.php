<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function fb_env()
{
  $env = "prd";
  if(isset($_GET["env"]))
  {
    $env = $_GET["env"];
    $env = preg_replace("/[^A-Za-z]/", '', $env);
    $env = substr($env, 0, 3);
    $env = strtolower($env);
  }

  return $env;
}

function fb_url($params = array(), $query = array())
{
  $url = site_url($params);
  $url .= "?env=" . fb_env();
  if(count($query) > 0)
  {
    foreach ($query as $key => $value)
    {
      $url .= "&" . $key . "=" . $value;
    }
  }
  return $url;
}

function fb_url_env($env)
{
  $env = preg_replace("/[^A-Za-z]/", '', $env);
  $env = substr($env, 0, 3);
  $env = strtolower($env);

  $url = current_url();
  $url .= "?env=" . $env;
  foreach ($_GET as $key => $value)
  {
    if($key == "env")
    {
      continue;
    }

    $url .= "&" . urlencode($key) . "=" . urlencode($value);
  }

  return htmlentities($url);
}
