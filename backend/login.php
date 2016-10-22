<?php
include dirname(__FILE__)."/functions.php";

if(isset($_GET['username']) && isset($_GET['password'])){
  $res = doLoginBackend($_GET['username'], $_GET['password']);
  if ($res == false){
    echo "**NOPE**";
  } else {
    echo $res;
  }
}

 ?>
