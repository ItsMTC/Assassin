<?php

include dirname(__FILE__)."/accountFunctions.php";

$token = $_GET['token'];
echo getTargetRaw($token);

 ?>