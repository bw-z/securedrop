

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SecureDrop</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">

          <form class="form-signin" role="form" action="create" method="post">
			  <h2 class="form-signin-heading">Create a new account</h2>
			  <? if (isset($_GET['match'])) { ?>
			  		<div class="alert alert-danger"><b>Passwords don't match</b> Please re-enter your password and confirm it.</div> 	
		      <? } ?>
		      <? if (isset($_GET['strength'])) { ?>
		        	<div class="alert alert-danger"><b>Password too short</b> Password must be at least 6 characters.</div>
		      <? } ?>
		      <? if (isset($_GET['email'])) { ?>
		        	<div class="alert alert-danger"><b>Invalid email</b> The email address you provided is invalid.</div>
		      <? } ?>
		      <? if (isset($_GET['exists'])) { ?>
		        	<div class="alert alert-danger"><b>Email Exists</b> An account with your email address is already registered.</div>
		      <? } ?>
		      
		      
			  <input name="email" type="email" class="form-control" placeholder="Email address" required autofocus>
			  <input name="password" type="password" class="form-control" placeholder="Password" required>
			  <input name="confirm" type="password" class="form-control" placeholder="Confirm Password" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Create an account</button>
      </form>


    </div> <!-- /container -->
    
    


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
  </body>
</html>







