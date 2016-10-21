<?php

include dirname(__FILE__)."/accountFunctions.php";

$token = $_GET['token'];
if(verifyToken($token)){
  echo "**OK**";
}

 ?>
