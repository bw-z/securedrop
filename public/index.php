<?php

include_once("../config.php");
include_once("../core.php");

validateHTTPS($config);
 
if (isset($_GET['encrypt'])) {
	$_SESSION['encrypt'] = $_GET['encrypt'];
}

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
          <a class="navbar-brand" href=".">SecureDrop<sup>beta <?=$config['version']?></sup></a>
        </div>
        
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
          	<?php if ($config['allow_accounts'] && $config['auth_type'] == "local" && !$_SESSION['loggedin']) { ?>
          		<li><a href="create">Create Account</a></li>
          	<?php } ?>
            <li><a href="mailto:<?=$config['contact_email']?>">Contact</a></li>
          </ul>

		
		<?php if ($config['allow_accounts'] && !$_SESSION['loggedin']) { ?>
			<form class="navbar-form navbar-right" action="login" method="post">
				<div class="form-group">
				  <input name="email" type="text" class="form-control" 
				  	<? if ($config['auth_type'] == "adldap") { ?> placeholder="Username" <? } ?>
				  	<? if ($config['auth_type'] == "local") { ?> placeholder="Email" <? } ?>
				  	>
				  <input name="password" type="password" class="form-control" placeholder="Password">
				</div>
				<button type="submit" class="btn btn-default">Login</button>
			  </form>
	    <?php } ?>
	    <?php if ($config['allow_accounts'] && $_SESSION['loggedin']) { ?>
				 <ul class="nav navbar-nav navbar-right">
					 <li><a href="logout">Logout</a></li>
				 </ul>
	    <?php } ?>
	    
	     </div><!--/.nav-collapse -->
		  
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
        	after they are uploaded. 
        	
        	<span class="pull-right">
	        	<? if ($config['encrypt']) { ?>
		        	File Encryption:<sup>beta</sup>
		        	
		        	&nbsp;
		        	
		        	<? if ($_SESSION['encrypt'] == "on") { ?>
			        	<a href="?encrypt=off">On</a>
		        	<? } else { ?>
		        		<a href="?encrypt=on">Off</a>
		        	<? } ?>
		        <? } ?>
        	</span>
        	
        	<br><br>
        	<small>Multiple files can be uploaded at once. Archive or zip a folder to upload/download it as one file.</small>
        	
        	
        	
        	
        	</p>
        	
        <? if (isset($_GET['badfile'])) { ?>
        	<div class="alert alert-danger"><b>Invalid Link</b> The download link you have followed is invalid or expired. </div>
        <? } ?>
        <? if (isset($_GET['baddelete'])) { ?>
        	<div class="alert alert-danger"><b>Error</b> You cannot delete this file. </div>
        <? } ?>
        <? if (isset($_GET['badextend'])) { ?>
        	<div class="alert alert-danger"><b>Error</b> You cannot extend expiry of this file. </div>
        <? } ?>
        <? if (isset($_GET['deleted'])) { ?>
        	<div class="alert alert-success"><b>File Deleted</b> <?=htmlspecialchars($_GET['file'])?> has been deleted. </div>
        <? } ?>
        <? if (isset($_GET['extended'])) { ?>
        	<div class="alert alert-success"><b>File Expiry Extended</b> Expiry date for <?=htmlspecialchars($_GET['file'])?> has been extended. </div>
        <? } ?>
        <? if (isset($_GET['usercreated'])) { ?>
        	<div class="alert alert-success"><b>Account created</b> - you can now login above. </div>
        <? } ?>
        
        
        <form action="_interfaces/upload.php" class="dropzone" id="drop"></form>
		
		<br>
		<? if ($config['allow_accounts'] && !$_SESSION['loggedin']) { ?>
        	<div class="alert alert-info"><b>Login now!</b> You can create an account or login (above) to keep track of files you have uploaded. </div>
        <? } ?>
		
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
    <script type="text/javascript" src="zeroclipboard/ZeroClipboard.js"></script>
    <script>
        Dropzone.options.drop = {
		  maxFilesize: <?=$config['max_file_size']?>, // MB
		};
	</script>	
	
    <script>
    	
    	
    	function refreshdiv() {
    	
    		jQuery.getJSON('_interfaces/getFileList.php', function(data) {
    			
    			var stuff = "<table class=\"table\"> \
						    	<tr> \
						    		<th></th> \
						    		<th>Filename</th> \
									<th></th> \
						    		<th>Uploaded</th> \
									<th>Expires</th> \
						    		<th>Shareable Link</th> \
						    	</tr> ";
				
				 
    			for (var i = 0; i < data.length; i++) {
    			
    				var enc = "";
    				if (data[i].encrypted) enc = "<span class=\"glyphicon glyphicon-lock\"></span>";
    				
	    			stuff += " \
	    			<tr> \
	    				<td>" + enc + "</td> \
						<td><a target=\"_blank\" href=\"d/" + data[i].accesskey + "\"> \
						" + data[i].filename + "</a></td> \
						<td><a href=\"delete/" + data[i].fileid + "?token=<?=$_SESSION['token']?>\">Delete</a></td> \
						<td>" + data[i].textDate + "</td> \
						<td> <a href=\"extend/" + data[i].fileid + "\?token=<?=$_SESSION['token']?>\"><button type=\"button\" class=\"btn btn-default btn-sm\">  <span class=\"glyphicon glyphicon-plus\"></span> \
						</button></a> <span style=\"font-size: 12px;\">" + data[i].expires + "</span>  </td> \
						\
						<td><button id=\"cl_"+ data[i].fileid +"\" type=\"button\" data-clipboard-text=\"<?=$config['securedrop_home']?>/d/" + data[i].accesskey + "\" class=\"btn btn-default btn-sm\">  <span class=\"glyphicon glyphicon-link\"></span> Copy \
						</button> \
						<a target=\"_blank\" href=\"d/" + data[i].accesskey + "\"><span style=\"font-size: 12px;\"><?=$config['securedrop_home']?>/d/" + data[i].accesskey + "</span></a> \
						 </td> \
					</tr>";
	    			
    			}
    			$('#files').html(stuff + "</table>");
    			
    			for (var i = 0; i < data.length; i++) {
    				var client = new ZeroClipboard( document.getElementById('cl_' + data[i].fileid) );
    			}
    			
    		});
			
		    setTimeout('refreshdiv()', 2500);
		}
		
		refreshdiv();
    
    </script>
    
  </body>
</html>






