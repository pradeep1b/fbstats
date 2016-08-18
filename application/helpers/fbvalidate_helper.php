<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function myformat_mac($mac)
{
  $mac = trim($mac);
  $mac = preg_replace("/[^a-zA-Z0-9]+/", "", $mac);
  $mac = strtoupper($mac);

  return $mac;
}

