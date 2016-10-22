<?php
include dirname(__FILE__)."/backend/functions.php";
$recaptcha = getReCaptcha();
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Login</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/thing.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
<div class="site-wrapper">

    <div class="site-wrapper-inner">

        <div class="cover-container">
            <div class="inner cover">
                <?php

                if(!isset($_POST['g-recaptcha-response'])){
                    echo '
                  <h3 class="cover-heading">Login to the Assassin Terminal</h3>
                  <p class="lead">Enter your username/email and password</p>
                  <form name="login" action="login" data-toggle="validator" method="post">
					<input type="text" class="form-control" maxlength="20" required placeholder="Username" name="username" id="username" aria-describedby="username">
					<input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
					<center><div class="g-recaptcha" data-sitekey="6LcnoQ0TAAAAAGWoOB8lbdMxh8FzDlB_mXCbF4xZ"></div></center>
					<button class="btn btn-lg btn-default btn-block" type="submit">Login</button>
				  </form>';
                } else {
                    $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
                    if ($resp->isSuccess()) {
                        // good its not a bot and stuff now we go ahead and register them
                        //register();
                        if(!doLogin($_POST['username'], $_POST['password'])){
                            echo 'Username or password incorrect!<br /><a href="login" class="btn btn-lg btn-default">Try Again</a>';
                        } else {
                            echo '...';
                            echo '<br />Done!<br />...<br />Now loading Assassin Terminal...<meta http-equiv="refresh" content="3; url=terminal" />';
                        }
                    } else {
                        $errors = $resp->getErrorCodes();
                        echo '<a href="login" class="btn btn-lg btn-default">Try Again</a>';
                    }
                }
                ?>
            </div>
        </div>

    </div>

</div>
    </body>
<?php
include "backend/footer.php";
?>