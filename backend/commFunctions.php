<?php
/**
 * Created by PhpStorm.
 * User: evanlanglais
 * Date: 10/21/16
 * Time: 10:19 AM
 */

function sendMessage($personid, $targetid, $total, $test){
    $personinfo = getPersonInfo($personid);
    $targetinfo = getPersonInfo($targetid);
    if($test){
        $text = "*TEST*\r\n\r\nWelcome Back, ".$personinfo["first"]."\r\n\r\nYour Target is ".$targetinfo["first"]." ".$targetinfo["last"]."\r\n\r\nThere are ".$total."\r\n\r\nGrab Your Spoon\r\n\r\nGood Luck";
    } else {
        $text = "Welcome Back, ".$personinfo["first"]."\r\n\r\nYour Target is ".$targetinfo["first"]." ".$targetinfo["last"]."\r\n\r\nThere are ".$total."\r\n\r\nGrab Your Spoon\r\n\r\nGood Luck";
    }

    //echo $text;

    sendIDText($personid, $text);
    return "done";
}
function sendAnnouncement($text, $excluded){
    $people = getAllPeople();
    foreach($people as $person){
        if(!in_array($person, $excluded)){
            sendIDText($person, $text);
        }
    }
}

function getAllPeople(){
    $people = array();
    $res = MySQLCommand("SELECT id FROM players");
    foreach($res as $arr){
        array_push($people, $arr["id"]);
    }


    return $people;

}

function getSomePeople($count){
    $all = getAllPeople();
    $some = array();
    $list = array_rand($all, $count);
    foreach($list as $key){
        array_push($some, $all[$key]);
    }
    return $some;
}

function sendIDText($id, $text){
    $info = getPersonInfo($id);
    sendRawText($info["number"], $info["carrier"], $text);
}

function sendRawText($number, $carrier, $text){
    //gmail($number.getCarrierString($carrier), $text);
}

function getCommString($username){
    $user = getUserRow($username);
    if(!empty($user))
        return $user["phone"].getCarrierString($user["carrier"]);
    return "";
}

function getCarrierString($carrier){
    switch ($carrier){
        case "AT&T":
            return "@txt.att.net";
            break;
        case "Verizon":
            return "@vtext.com";
            break;
        case "T-Mobile":
            return "@tmomail.net";
            break;
        case "Sprint":
            return "@messaging.sprintpcs.com";
            break;
    }
    return "";
}
