<?php
include dirname(__FILE__)."/accountFunctions.php";

if(isset($_GET['username']) && isset($_GET['password'])){
  $res = doLoginBackend($_GET['username'], $_GET['password']);
  if ($res == false){
    //some anti bruteforce thing here
    echo "**NOPE**";
  } else {
    echo $res;
  }
}

 ?>
