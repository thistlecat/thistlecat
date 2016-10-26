 <?php
//
//


	if (isset($_GET['lcclass'])){ $lcclass = $_GET['lcclass']; }
	else {	$lcclass = "";}
	
	
//check if it's a single letter only (i.e., distinguish between all of B, and B1-B9999; the latter will be represneted as B0
if ((strlen($lcclass) == 2) and ($lcclass[1] == '0'))
{
$lcclass = $lcclass[0] . "[0-9]";
}

	
include "includes/dbconnect.php";

include "includes/preparedates_single.php";
	

//insert link in each one to make chart clickable
foreach  ($allyears as $k4 => $v4){
	if ($v4['value'] <> ""){
		//insert correspreond lc class to make link
		$allyears[$k4]['link'] = "JavaScript:getresults('" . $v4['label'] . "','','" . $lcclass . "')";
	}
}

$jsonresults = json_encode($allyears);



//set page title here
$pagetitle = "Total Items by Copyright Date";



include "includes/header.php";




?>

<script type="text/javascript">

function getresults(year,series,lcclass){
	//check if second character is lowercase d - if so, it's a first letter only class (B0)

	$("#itemsgohere").load("results.php?year=" + year + "&series=" + series + "&lcclass=" + lcclass );
	
}

$(document).ready(function(e) {

var url = window.location.href;
var matchvar = url.match(/\?lcclass=[A-Z]/gi);
if(url.indexOf(matchvar) != -1){
     $('input:radio[id=<?php echo $_GET['lcclass']; ?>]').prop('checked', true);
}
});






		 
		 
</script>

<style type="text/css">
#lclist li {
    list-style:none;
	margin-left:-20px;
   
}#accordion label{
font-size:0.85em;display: inline !important;
font-weight:normal;
}

#chartContainer{
/* min-width:1000px; */	
}
</style>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

<?php include 'includes/navbar_class.php';?>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
    
      <form name="filteroptions" method="get" action="overviewbreakout.php">
      
<div id="accordion">

<?php include "includes/classmenu.php";?>

</div>
</div>
</form>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                 <?php include "includes/collapseleft.php" ?>  
    <h2>Total Items by Copyright Date <?php include "includes/expanded.php" ?></h2>
    
   <?php
   if ($lcclass == "") {$lcdisplay = "All Classes";}
   elseif((strlen($lcclass) > 1) and ($lcclass[1] == '[')) {$lcdisplay = "LC Class: " . $lcclass[0] . "1 - " . $lcclass[0] . "9999";  }
   else  {$lcdisplay = "LC Class: " . $lcclass;}
   ?> 
                        <h3><?php echo $lcdisplay; ?></h3>        




<div id="chartholder">
<canvas id="mainchart"></canvas>
</div>
<div id="itemsgohere"></div>



<?php include "includes/chart_general.php"; 
display_chart("Copyright Date");

?>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/bootstrap.min.js"></script>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
    
    <script type="text/javascript">

$(document).ready(function(e) {
	
	
	
// get current URL path and assign 'active' class
var pagename =  window.location.href.split("/").slice(-1);
$('#navbar a[href="'+pagename+'"]').parent().addClass('active');

//get position of chosen lc class among accordion headers so we know which one to expand
   var curclass=	$("#<?php echo substr($lcclass,0,1) ?>class").parent().children('h3').index($('#<?php echo substr($lcclass,0,1) ?>class'));
if (curclass == -1){
var findclass = "false";	
}
else {var findclass = curclass;}

  $( "#accordion" ).accordion({
  heightStyle: "content",
  collapsible: true,
  active:findclass,
  animate:{
	duration:200  
  }
});


});

    </script>
  </body>
</html>
