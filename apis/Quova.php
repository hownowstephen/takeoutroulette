<?php 

function getRealIP()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function GeolocateIP($ipin){
	// initiate curl and set options
	$ch = curl_init();
	$ver = 'v1/';
	$method = 'ipinfo/';
	$apikey = '100.x6jfvyw7ugd2f7db48e3';  
	$secret = 'J7TSTWpu';  
	$timestamp = gmdate('U'); // 1200603038
	// echo $timestamp;   
	$sig = md5($apikey . $secret . $timestamp);
	$service = 'http://api.quova.com/';
	curl_setopt($ch, CURLOPT_URL, $service . $ver. $method. $ipin . '?apikey=' .
	             $apikey . '&sig='.$sig);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec($ch);
	$headers = curl_getinfo($ch);
	
	// close curl
	curl_close($ch);
	
	// return XML data
	if ($headers['http_code'] != '200') {
	   echo "An error has occurred accessing this IP";
	  return false;
	} else {
	   //echo $data;
	   return($data);
	}
}

?>