<?php

session_start();


function getMySQL(){
	$db = parse_ini_file("settings.ini", true)["database"];
	$hd = mysqli_connect($db["server"], $db["username"], $db["password"], $db["database"]);
	if($hd->connect_error){
		trigger_error('Database connection failed: '  . $hd->connect_error, E_USER_ERROR);
		return false;
	} else {
		return $hd;
	}
}

function query($post){
	$hd = getMySQL();
$result = mysqli_query($hd, $post);
if($result === false) {
  trigger_error('Wrong SQL: ' . $post . ' Error: ' . $hd->error, E_USER_ERROR);
}
return $result;
}

function SQLValue($value){
	$hd = getMySQL();
	return mysqli_real_escape_string($hd, $value);
}

function getReCaptcha(){
    $ini = parse_ini_file("settings.ini", true)["captcha"];
    $secret = $ini["secret"];
    return new \ReCaptcha\ReCaptcha($secret);
}