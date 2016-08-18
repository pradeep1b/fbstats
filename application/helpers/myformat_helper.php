<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function myformat_date($date)
{
	$outputFormat = "M j, Y g:i:s A T";
	$outputTimezone = "America/Louisville";

	if (preg_match("/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/",$date))
	{
		// Local (Louisville) Oracle Date Format
		$datetime = DateTime::createFromFormat('Y-m-d H:i:s', $date, new DateTimeZone("America/Louisville"));
		$datetime->setTimezone(new DateTimeZone($outputTimezone));
		return $datetime->format($outputFormat);
	}
	else if(preg_match("/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})\.(\d{3})Z$/",$date))
	{
		// ISO 8601 Format
		$datetime = DateTime::createFromFormat('Y-m-d\TH:i:s', substr($date, 0, 19), new DateTimeZone("UTC"));
		$datetime->setTimezone(new DateTimeZone($outputTimezone));
		return $datetime->format($outputFormat);
	}
	else if(preg_match("/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})\.(\d{3})\+0000$/",$date))
	{
		// Format used by OpenFire interceptor
		$datetime = DateTime::createFromFormat('Y-m-d\TH:i:s', substr($date, 0, 19), new DateTimeZone("UTC"));
		$datetime->setTimezone(new DateTimeZone($outputTimezone));
		return $datetime->format($outputFormat);
	}
	else if(preg_match("/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})Z/",$date))
	{
		// Format used by OpenFire interceptor
		$datetime = DateTime::createFromFormat('Y-m-d\TH:i:s', substr($date, 0, 19), new DateTimeZone("UTC"));
		$datetime->setTimezone(new DateTimeZone($outputTimezone));
		return $datetime->format($outputFormat);
	}
	else
	{
		echo $date . " (NO FORMAT)";
	}
}
