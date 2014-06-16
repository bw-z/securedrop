<?php

include_once("config.php");
include_once("core.php");

validateHTTPS($config);

?>



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
    <link href="css/dropzone.css" type="text/css" rel="stylesheet" />


    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">SecureDrop</a>
        </div>
        <!--
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
        </div><!--/.nav-collapse -->
		
		<!--
		<form class="navbar-form navbar-right" role="search" action="login" method="post">
			<div class="form-group">
			  <input type="text" class="form-control" placeholder="Username">
			  <input type="password" class="form-control" placeholder="Password">
			</div>
			<button type="submit" class="btn btn-default">Login</button>
		  </form>
		  -->
		  
      </div>
    </div>

    <div class="container">

      <div class="starter-template">
       	
       	<br><br><br><br>
       
        <p class="lead">Use this service to securely share files. Files are wiped 
        	<?php if ($config['file_expiry'] > 24) {
	        		echo intval($config['file_expiry'] / 24) . " days ";
	        	} else {
		        	echo $config['file_expiry'] . " hours ";
	        	}
        	?> 
        	after they are uploaded.</p>
        	
        <? if (isset($_GET['badfile'])) { ?>
        	<div class="alert alert-danger"><b>Invalid Link</b> The download link you have followed is invalid or expired. </div>
        <? } ?>
        <? if (isset($_GET['baddelete'])) { ?>
        	<div class="alert alert-danger"><b>Error</b> You cannot delete this file. </div>
        <? } ?>
        <? if (isset($_GET['deleted'])) { ?>
        	<div class="alert alert-success"><b>File Deleted</b> <?=htmlspecialchars($_GET['file'])?> has been deleted. </div>
        <? } ?>
        
        <form action="_interfaces/upload.php" class="dropzone"></form>
		
		<h2>My Files</h2>
		<div id="files"></div>


      </div>

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="dropzone.js"></script>
    <script>
    
    	function refreshdiv() {
    	
    		jQuery.getJSON('_interfaces/getFileList.php', function(data) {
    			
    			var stuff = "<table class=\"table\"> \
						    	<tr> \
						    		<th>Filename</th> \
									<th></th> \
						    		<th>Uploaded</th> \
									<th>Expires</th> \
									<th>Download Count</th> \
						    		<th>Sharelink</th> \
						    	</tr> ";
				    	
    			for (var i = 0; i < data.length; i++) {
	    			stuff += " \
	    			<tr> \
						<td><a target=\"_blank\" href=\"d/" + data[i].accesskey + "\">" + data[i].filename + "</a></td> \
						<td><a href=\"delete/" + data[i].fileid + "\">Delete</a></td> \
						<td>" + data[i].textDate + "</a></td> \
						<td>" + data[i].expires + "</a></td> \
						<td></a></td> \
						<td><a target=\"_blank\" href=\"d/" + data[i].accesskey + "\"><?=$config['securedrop_home']?>/d/" + data[i].accesskey + "</a></td> \
					</tr>";
	    			
    			}
    			$('#files').html(stuff + "</table>");
    			
    		});
			
		    setTimeout('refreshdiv()', 5000);
		}
		
		refreshdiv();
    
    </script>
    
  </body>
</html>






