<?php

function validateAccount($username, $email, $phone){
	$username = SQLValue($username);
	$email = SQLValue($email);
    $phone = SQLValue($phone);
    if(strpos($email, "@") === false){
        return false;
    }
if(mysqli_num_rows(query("SELECT username FROM users WHERE username='{$username}' OR email='{$email}' OR phone='{$phone}'")) > 0){
	return false;
} else {
	return true;
}
}

function getPrettyUsername($userid){
    $user = getUserRow($userid);
    if(!empty($user))
        return $user["username"];
    return $userid;
}

function getUserID($token){
    $token = SQLValue($token);
    $raw = query("SELECT userid FROM logintokens WHERE token='{$token}'");
    if(mysqli_num_rows($raw) > 0){
        $row = mysqli_fetch_assoc($raw);
        return $row['userid'];
    }
    return "";
}

function getUserRow($userid){
    $userid = SQLValue($userid);
    $res = query("SELECT * FROM users WHERE id='".$userid."'");
    if(mysqli_num_rows($res) > 0){
        $row = mysqli_fetch_assoc($res);
        return $row;
    }
    return array();
}

function doRegister($username, $first, $last, $password, $email, $phone){
	$username = SQLValue($username);
	$first = SQLValue($first);
	$last = SQLValue($last);
    $email = SQLValue($email);
    $phone = SQLValue($phone);
    $salt = generateString(8);
	$password = hash("sha256", $password.$salt);
	query("INSERT INTO users (username, password, salt, email, first, last, phone, verified) VALUES ('{$username}','{$password}','{$salt}','{$email}','{$first}','{$last}','{$phone}','0')");
}

function doLogin($username, $password){
    $res = doLoginBackend($username, $password);
    if(!$res) {
        return false;
    }
    $res = explode("|", $res);
    $_SESSION["id"] = $res[0];
    $_SESSION["username"] = $res[1];
    $_SESSION["token"] = $res[2];
    $_SESSION["firstname"] = $res[3];
    $_SESSION["lastname"] = $res[4];
    return true;
}

function doLogout(){
    unset($_SESSION["id"]);
    unset($_SESSION["username"]);
    unset($_SESSION["token"]);
    unset($_SESSION["firstname"]);
    unset($_SESSION["lastname"]);
}

function doLoginBackend($username, $password){
	$username = SQLValue($username);
    $salt = getUserRow($username)["salt"];
    $password = hash("sha256", $password.$salt);

	$raw = query("SELECT id, username, first, last FROM users WHERE username='{$username}' OR email='{$username}' AND password='{$password}'");
	if(mysqli_num_rows($raw) > 0){
        $res = mysqli_fetch_assoc($raw);
        $userid = $res['id'];
		$username = $res['username'];
		$token = generateNewToken($userid);
		return $userid.'|'.$username.'|'.$token.'|'.$res["first"].'|'.$res["last"];
	} else {
		return false;
	}
}

function getTargetRaw($token){
	//havnt implemented yet
	return "No Target Detected|--|--|--";
}

function getName($userid){
    $user = getUserRow($userid);
    if(!empty($user))
        return array($user["first"], $user["last"]);
    return array("NOT", "DEFINED");
}

function verifyToken($token){
	$token = SQLValue($token);
	if(mysqli_num_rows(query("SELECT userid FROM logintokens WHERE token='".$token."'")) > 0){
		return true;
	} else {
		return false;
	}
}

function verifyVerification($userid, $token){
	$token = SQLValue($token);
	if(mysqli_num_rows(query("SELECT userid FROM verification WHERE token='{$token}' AND userid='{$userid}'")) > 0){
		return true;
	} else {
		return false;
	}
}

function generateNewToken($userid){
	$private = generateString(20);
    $userid = SQLValue($userid);
	query("DELETE FROM logintokens WHERE userid='".$userid."'");
	if(!verifyToken($private)){
		query("INSERT INTO logintokens (userid, token) VALUES ('{$userid}','{$private}')");
	} else {
		return generateNewToken($userid);
	}
	return $private;
}

function generateNewVerification($userid, $email){
	$token = SQLValue(generateNum(5));
    $userid = SQLValue($userid);
    $email = SQLValue($email);
	query("DELETE FROM verification WHERE userid='".$userid."'");
	if(!verifyVerification($userid, $token)){
		query("INSERT INTO verification (userid, email, token) VALUES ('{$userid}','{$email}','{$token}')");
	} else {
		return generateNewVerification($userid);
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
