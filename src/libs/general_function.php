<?php

/*
* use crypto for php
* $encrypted = cryptoJsAesEncrypt("my passphrase", "value to encrypt");
* $decrypted = cryptoJsAesDecrypt("my passphrase", $encrypted);
*/

/**
* Decrypt data from a CryptoJS json encoding string
*
* @param mixed $passphrase
* @param mixed $jsonString
* @return mixed
*/

function cryptoJsAesDecrypt($passphrase, $jsonString){
    $jsondata = json_decode($jsonString, true);
    $salt = hex2bin($jsondata["s"]);
    $ct = base64_decode($jsondata["ct"]);
    $iv  = hex2bin($jsondata["iv"]);
    $concatedPassphrase = $passphrase.$salt;
    $md5 = array();
    $md5[0] = md5($concatedPassphrase, true);
    $result = $md5[0];
    for ($i = 1; $i < 3; $i++) {
        $md5[$i] = md5($md5[$i - 1].$concatedPassphrase, true);
        $result .= $md5[$i];
    }
    $key = substr($result, 0, 32);
    $data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);
    return json_decode($data, true);
}

/**
* Encrypt value to a cryptojs compatiable json encoding string
*
* @param mixed $passphrase
* @param mixed $value
* @return string
*/
function cryptoJsAesEncrypt($passphrase, $value){
    $salt = openssl_random_pseudo_bytes(8);
    $salted = '';
    $dx = '';
    while (strlen($salted) < 48) {
        $dx = md5($dx.$passphrase.$salt, true);
        $salted .= $dx;
    }
    $key = substr($salted, 0, 32);
    $iv  = substr($salted, 32,16);
    $encrypted_data = openssl_encrypt(json_encode($value), 'aes-256-cbc', $key, true, $iv);
    $data = array("ct" => base64_encode($encrypted_data), "iv" => bin2hex($iv), "s" => bin2hex($salt));
    return json_encode($data);
}

function baseUrl($slug=false) {
	if($slug){
		if(strpos($slug, "backoffice")){
		return route("backoffice");
		}
	}
}
function _post($name, $default=null){
	return isset($_POST[$name]) && $_POST[$name] ? $_POST[$name] : $default;
}
function _get($name, $default=null){
	return isset($_GET[$name]) && $_GET[$name] ? $_GET[$name] : $default;
}
function _server($name, $default=null){
	return isset($_SERVER[$name]) && $_SERVER[$name] ? $_SERVER[$name] : $default;
}
function _session($name, $default=null){
	return isset($_SESSION[$name]) && $_SESSION[$name] ? $_SESSION[$name] : $default;
}
function _cookie($name, $default=null){
	return isset($_COOKIE[$name]) && $_COOKIE[$name] ? $_COOKIE[$name] : $default;
}

function fileDimensions($file) {
	return list($width, $height) = getimagesize("$file");
}

function removeExt($string) {
	return preg_replace('/\\.[^.\\s]{3,4}$/', '', $string);
}

function getClientIp() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = '0';
    return $ipaddress;
}


function cleanCharsAndSpace($string) {
	$search = array('/[^A-Za-z0-9\-]/', '_');
	$replace = array(' ', ' ');
	$subject = $string;
	return str_replace($search, $replace, $subject); 
}

function generateToken($length = null)
	{
	if(empty($length)){
    $length = 20;
    }else{
    $length = $length;
    }
	$buf = '';
    for ($i = 0; $i < $length; ++$i) {
        $buf .= chr(mt_rand(0, 255));
    }
    return bin2hex($buf);
}

function generateRandomNumeric($length = null) {
    if(empty($length)){
    $length = 10;
    }else{
    $length = $length;
    }
    $numeric = '0123456789';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $numeric[rand(0, strlen($numeric) - 1)];
    }
    return $randomString;
}

function base64Image($path) { 
	$type = pathinfo($path, PATHINFO_EXTENSION);
	$data = file_get_contents($path);
	return 'data:image/' . $type . ';base64,' . base64_encode($data);
}
 
function generateRandomString($length = null) {
    if(empty($length)){
    $length = 10;
    }else{
    $length = $length;
    }
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function generateRandomCode($length = null) {
    if(empty($length)){
    $length = 6;
    }else{
    $length = $length;
    }
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function removesSpecialChars($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

   return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
}


function createHash($stored)
{
	$hash = password_hash(base64_encode(hash('sha256', $stored, true)), PASSWORD_DEFAULT);
	return $hash;
}
	
function validateHash($stored, $hash_bcrypt)
{
	$hash = $hash_bcrypt;
		if (password_verify(base64_encode(hash('sha256', $stored, true)), $hash_bcrypt))
		return true;
		
}

//** Format Tanggal YYYY-MM-DD **//
function dateToEn($date){
	$date_en 	 = date("Y-m-d H:i:s", $date);
	$date_i 	 = date('Y-m-d', strtotime($date_en));
	$month_array = date('m', strtotime($date_en));
	$dateObj   	 = DateTime::createFromFormat('!m', $month_array);
	$month 		 = $dateObj->format('F');
	$date 		 = date('d', strtotime($date_en));
	$year 		 = substr($date_en, 0 , 4);
	$monthName 	 = substr($month, 0 , 3);
	if($date_i == '1970-01-01'){
		return '';
	} else {
		return $monthName." ".$date.", ".$year;
	}
}//function_date_to_en

//** Format Tanggal YYYY-MM-DD **//
function dateToTime($date){
	$time = date('Y-m-d H:i:s', $date);
	return $time;
}//function_date_to_en

//** Format Tanggal DD-MM-YYYY **//
function dateToId($date){
	$date_id 	 = date("Y-m-d H:i:s", $date);
	$date_i 	 = date('Y-m-d', strtotime($date_id));
	$month_array = date('m', strtotime($date_id));
	$bulan 		 = array(
					"", 
					"Januari",
					"Februari",
					"Maret",
					"April",
					"Mei",
					"Juni",
					"Juli",
					"Agustus",
					"September",
					"Oktober",
					"November",
					"Desember"
			);
	$date 		 = date('d', strtotime($date_id));
	$year 		 = substr($date_id, 0 , 4);
	$month_array = $month_array < 10 ? substr($month_array, 1, 1) : $month_array;
	if($date_i == '1970-01-01'){
		return '';
	} else {
		return $date." ".$bulan[$month_array]." ".$year;
	}
}//function_date_to_id

function timeId() {
	date_default_timezone_set('Asia/Jakarta');
	return time();
}

//** Format Tanggal DD-MM-YYYY **//
function dateTimeId($date){
	$date_id 		= date("Y-m-d H:i:s", $date);
	$dates 			= new DateTime($date_id, new DateTimeZone('Asia/Jakarta') );
	$date_i			= $dates->format('Y-m-d H:i:s');
	if($date_i == '1970-01-01'){
		return '';
	} else {
		return $date_i;
	}
}//function_date_to_id

//** Format Tanggal DD-MM-YYYY **//
function dateTimeEn($date){
	$date_id 		= date("Y-m-d H:i:s", $date);
	$month_array 	= date('m', strtotime($date_id));
	$dateObj   		= DateTime::createFromFormat('!m', $month_array);
	$month 			= $dateObj->format('F');
	$monthName 		= substr($month, 0 , 3);
	$date 			= date('d', strtotime($date_id));
	$newDateTime 	= date('h:i A', strtotime($date_id));
	$year 			= substr($date_id, 0 , 4);
    $date_i 		= $monthName." ".$date.", ".$year." at ".$newDateTime."";
	if($date_i == '1970-01-01'){
		return '';
	} else {
		return $date_i;
	}
}//function_date_to_id

function controller($module = '', $class = '', $object=true) {
	$str = "\Modules\\$module\Http\Controllers\\$class";
	if($object) return new $str(true);
	return $str;
}

function entity($module = '', $entities = '') {
	$src = "\Modules\\$module\Entities\\$entities";

	if(!class_exists($src))
		return 'not found';

	return $src;
}

function module($module = ''){
	$str = "\Modules\\$module";

	if(!class_exists($src))
		return 'not found';

	return new $str;
}

function explodeSearch($keywords = []) {
	$explode = explode(',',$keywords);
	foreach ($explode as $key => $value) {
		# code...
		if(isset($value[$key])) {
			$explode[$key] = '%'.$value.'%';
		}
	}
	return $explode;
}

function removeHttp($url) {
   $disallowed = array('http://', 'https://');
   foreach($disallowed as $d) {
      if(strpos($url, $d) === 0) {
         return str_replace($d, '', $url);
      }
   }
   return $url;
}

function n2lbrMtp($data = []) {

	if(isset($data)) {
	
	$data['mtp_caption_en'] = nl2br(e($data['mtp_caption_en']));
    $data['mtp_caption_id'] = nl2br(e($data['mtp_caption_id']));
    $data['mtp_content_en'] = nl2br(e($data['mtp_content_en']));
    $data['mtp_content_id'] = nl2br(e($data['mtp_content_id']));

    return $data;

	}else {
		return false;
	}
}
