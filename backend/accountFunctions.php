<?php
include dirname(__FILE__)."/globalFunctions.php";

function validateAccount($username, $phone){
	$username = SQLValue($username);
	$phone = SQLValue($phone);
    if(!is_numeric($phone)){
        return false;
    }
if(mysqli_num_rows(query("SELECT username FROM users WHERE username='".$username."' OR phone='".$phone."'")) > 0){
	return false;
} else {
	return true;
}
}

function getPrettyUsername($username){
    $user = getUserRow($username);
    if(!empty($user))
        return $user["username"];
    return $username;
}

function getUserRow($username){
    $username = SQLValue($username);
    $res = query("SELECT username FROM users WHERE username='".$username."'");
    if(mysqli_num_rows($res) > 0){
        $row = mysqli_fetch_assoc($res);
        return $row;
    }
    return array();
}

function doRegister($username, $first, $last, $password, $phone, $carrier){
	$username = SQLValue($username);
	$first = SQLValue($first);
	$last = SQLValue($last);
    $phone = SQLValue($phone);
    $carrier = SQLValue($carrier);
	$password = hash("sha256", $password);
	query("INSERT INTO users (username, password, first, last, phone, carrier) VALUES ('".$username."','".$password."','".$first."','".$last."','".$phone."','".$carrier."')");
}

function doLogin($username, $password){
    $res = doLoginBackend($username, $password);
    if(!$res) {
        return false;
    }
    $res = explode("|", $res);
    $_SESSION["username"] = $res[0];
    $_SESSION["publictok"] = $res[1];
    $_SESSION["privatetok"] = $res[2];
    $_SESSION["firstname"] = $res[3];
    $_SESSION["lastname"] = $res[4];
    return true;
}

function doLogout(){
    unset($_SESSION["username"]);
    unset($_SESSION["publictok"]);
    unset($_SESSION["privatetok"]);
    unset($_SESSION["firstname"]);
    unset($_SESSION["lastname"]);
}

function doLoginBackend($username, $password){
	$username = SQLValue($username);
	$password = hash("sha256", $password);
	$raw = query("SELECT username, first, last FROM users WHERE username='".$username."' AND password='".$password."'");
	if(mysqli_num_rows($raw) > 0){
		$username = getPrettyUsername($username);
		$tokens = generateNewTokens($username);
		$res = mysqli_fetch_assoc($raw);
		return $username.'|'.$tokens[0].'|'.$tokens[1].'|'.$res["first"].'|'.$res["last"];
	} else {
		return false;
	}
}

function getTargetRaw($token){
	//havnt implemented yet
	return "No Target Detected|--|--|--";
}

function getName($username){
    $user = getUserRow($username);
    if(!empty($user))
        return array($user["first"], $user["last"]);
    return array("NOT", "DEFINED");
}

function verifyToken($private){
	$private = SQLValue($private);
	if(mysqli_num_rows(query("SELECT username FROM logintokens WHERE private='".$private."'")) > 0){
		return true;
	} else {
		return false;
	}
}

function verifyTokens($public, $private){
	$private = SQLValue($private);
	$public = SQLValue($public);
	if(mysqli_num_rows(query("SELECT username FROM logintokens WHERE private='".$private."' OR public='".$public."'")) > 0){
		return true;
	} else {
		return false;
	}
}

function verifyVerification($token){
	$token = SQLValue($token);
	if(mysqli_num_rows(query("SELECT username FROM verification WHERE token='{$token}'")) > 0){
		return true;
	} else {
		return false;
	}
}

function generateNewTokens($username){
	$private = generateString(20);
	$public = generateString(20);
	query("DELETE FROM logintokens WHERE username='".$username."'");
	if(!verifyTokens($public, $private)){
		query("INSERT INTO logintokens (username, public, private) VALUES ('".$username."','".$public."','".$private."')");
	} else {
		return generateNewTokens($username);
	}
	return array($public, $private);
}

function generateNewVerification($username){
	$token = SQLValue(generateNum(5));
	query("DELETE FROM verification WHERE username='".$username."'");
	if(!verifyVerification($token)){
		query("INSERT INTO verification (username, address, token) VALUES ('".$username."','".getCommString($username)."','".$token."')");
	} else {
		return generateNewVerification($username);
	}
	return $token;
}

function generateString($length)
{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
	    }
	    return $randomString;
}

function generateNum($length)
{
	$characters = '0123456789';
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, strlen($characters) - 1)];
	}
	return $randomString;
}
