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
    <title>Register</title>

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
                  <h3 class="cover-heading">You want to join?</h3>
                  <p class="lead">Alright, fill out your assassin application below.</p>
                  <form name="reg" action="register" data-toggle="validator" method="post">
					<div class="input-group">
						<span class="input-group-addon" id="username" name="username">@</span>
						<input type="text" class="form-control" maxlength="20" required placeholder="Asset Tag" name="username" id="username" aria-describedby="username">
					</div>
					<input type="text" class="form-control" id="first" name="first" placeholder="First Name" required maxlength="50">
					<input type="text" class="form-control" id="last" name="last" placeholder="Last Name" required maxlength="50">
					<input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
					<input type="text" class="form-control" id="phone" name="phone" placeholder="5555555555" required maxlength="10">
            <select class="form-control" name="carrier">
                <option value="AT&T">AT&T</option>
                <option value="Verizon">Verizon</option>
                <option value="Sprint">Sprint</option>
                <option value="T-Mobile">T-Mobile</option>
            </select>
					<center><div class="g-recaptcha" data-sitekey="6LcnoQ0TAAAAAGWoOB8lbdMxh8FzDlB_mXCbF4xZ"></div></center>
					<button class="btn btn-lg btn-default btn-block" type="submit">Apply</button>
				  </form>';
			} else {
				$recaptcha = new \ReCaptcha\ReCaptcha($secret);
				$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
				if ($resp->isSuccess()) {
					// good its not a bot and stuff now we go ahead and register them
					//register();
					if(!validateAccount($_POST['username'], $_POST['phone'])){

						echo 'Username or phone number already taken!<br /><a href="register" class="btn btn-lg btn-default">Try Again</a>';
					} else {
						echo '...';
						doRegister($_POST['username'], $_POST['first'], $_POST['last'], $_POST['password'], $_POST['phone'], $_POST['carrier']);
                        doLogin($_POST['username'], $_POST['password']);
						echo '<br />Done!<br />...<br />Now loading Assassin Terminals...<meta http-equiv="refresh" content="3; url=http://spoonassassin.com/" />';
					}




				} else {
					$errors = $resp->getErrorCodes();
					echo '<a href="register" class="btn btn-lg btn-default">Try Again</a>';
				}
			}
			?>
                </div>
              </div>

            </div>

          </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
