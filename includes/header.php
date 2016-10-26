<?php

$sitename = "ThistleCAT";


//append page title if it's been set
$showtitle = $sitename . ($pagetitle <> "" ? ": " . $pagetitle : "")

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $showtitle; ?></title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
  
    <link href="css/jquery-ui.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">
    

 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/jquery.tablesorter.min.js"></script> 
    <script type="text/javascript" src="js/excellentexport.js"></script> 

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.bundle.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<script>
$(document).ready(function(e) {
	// get current URL path and assign 'active' class 			
	var pagename =  decodeURI(window.location.href.split("/").slice(-1)); 	
	console.log(pagename);		
	$('#navbar a[href="'+pagename+'"]').parent().addClass('active'); 
	
});
</script>