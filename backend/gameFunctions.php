<?php

include("./globalFunctions.php");

function getTag($privatetoken){
  $privatetoken = SQLValue($privatetoken);
  $raw = query("SELECT username FROM logintokens WHERE private='".$privatetoken."'");
  if(mysqli_num_rows($raw) > 0){
      $res = mysqli_fetch_assoc($raw);
    return $res["username"];
  } else {
    return "N/A";
  }
}

function getAllGroupMembers($id){
	$people = array();
	$raw = query("SELECT leadertag, membertags FROM groups WHERE id='".$id."'");
    if(mysqli_num_rows($raw) > 0) {
        while($row = mysqli_fetch_assoc($raw)){
            array_push($people, $row["id"]);
        }
    }
	return $people;

}

function createGame($count, $test){
    $roster = getRoster(getSomePeople($count));
    $total = count($roster);
    foreach($roster as $person => $target){
        sendMessage($person, $target, $total, $test);
    }
    return $roster;
}

function getRoster($people){
    $targets = $people;
    shuffle($targets);
    $fixed = false;
    while($fixed == false){
        shuffle($targets);
        echo '*shuffled*';
        for($i = 0; $i < count($people); $i++){
            $personid = $people[$i];
            $targetid = $targets[$i];
            if(!seeIfTargetOk($personid, $targetid, $people, $targets)){
                echo '*'.$personid.' not ok with '.$targetid.'*';
                $fixed = false;
                break;
            } else {
                $fixed = true;
            }
        }
    }

    return array_combine($people, $targets);
}

function seeIfTargetOk($personid, $targetid, $people, $targets){
    //echo 'personid: '.$personid.' | targetid: '.$targetid;
    //echo 'targetid: '.$targetid.' | targettargetid: '.$targets[array_search($targetid, $people)];
    if($personid == $targetid || $targets[array_search($targetid, $people)] == $personid){
        //	echo '*rejected*';
        return false;
    } else {
        //	echo '*accepted*';
        return true;
    }
}



function getPersonInfo($id){
    $personreturn = array();
    $res = MySQLCommand("SELECT * FROM players WHERE id=".$id);
    $arr = $res[0];
    $personreturn = array("first" => $arr["first"], "last" => $arr["last"], "carrier" => $arr["carrier"], "number" => $arr["number"], "id" => $arr["id"]);
    return $personreturn;

}