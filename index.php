<?php
include "backend/globalFunctions.php";
?>
<!DOCTYPE html>
<html lang="en">
  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Welcome</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/thing.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="site-wrapper">

      <div class="site-wrapper-inner">

        <div class="cover-container">
          <div class="inner cover">
              <?php
              if(!isset($_SESSION["username"])){

              echo '
              <h1 class="cover-heading">Welcome</h1>
              <p class="lead">Grab Your Spoons</p>
              <p class="lead">
              <div class="row">
                  <a href="login" class="btn btn-lg btn-default">Login</a>
                  <a href="register" class="btn btn-lg btn-default">Register</a>
                  </div>
              </p>
          ';

            } else {
                echo '
                <h1 class="cover-heading">Welcome back, '.$_SESSION["firstname"].'</h1>
                <hr />
                <p class="lead">You have 1 target(s)</p>
                <!--put targets here-->
                <table width="100%" height="200px" border="1px" bordercolor="#00cc00"><tr><td><h2>Test Person</h2><p>From "CHS" squad</p></td><td>
                <p>To mark as deceased,<br /> write private code and press "Deceased"</p>
                <div class="row">
                <input type="text" name="code" length="15" />
                <a href="tag" class="btn btn-lg btn-default">Deceased</a>
                </div>
                </td></tr></table>
                <hr />
                <p class="lead">You are in 1 squad(s)</p>
                <p class="lead">
              <div class="row">
                  <a href="join" class="btn btn-lg btn-default">Join Squad</a>
                  <a href="register" class="btn btn-lg btn-default">Ranks</a>
              </div>
              </p>
                
                ';
            }
            ?>
              </div>

            </div>

          </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
