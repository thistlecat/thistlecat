 <?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


	//if (isset($_GET['lcclass'])){ $lcclass = $_GET['lcclass']; }
	//else {	$lcclass = "";}
	
	//if ($lcclass == ""){exit();}
	

	
include "includes/dbconnect.php";


//first get all possible classifications in this query
$sql0 = 'SELECT LEFT(class,1) as label FROM LCclasses GROUP BY LEFT(class,1)';
$stmt = $pdo->prepare($sql0);
$stmt->execute();
$allclasses = $stmt->fetchAll();
$classesonly = array();
foreach ($allclasses as $v2){
$classesonly[] = 	$v2['label'];
}



$sql = 'SELECT LEFT(itemcallnumber,1) as label, count(*) as value FROM ' . THIS_TABLE . ' GROUP BY LEFT(itemcallnumber,1)';
$stmt = $pdo->prepare($sql);
$stmt->execute();
$recordresults = $stmt->fetchAll();


//insert link in each one to make chart clickable
foreach  ($recordresults as $k4 => $v4){
	if ($v4['value'] <> ""){
		//insert correspreond lc class to make link
		$recordresults[$k4]['link'] = "subclassbreakout.php?lcclass=" . $recordresults[$k4]['label'];
	}
}

$jsonresults = json_encode($recordresults);


//set page title here
$pagetitle = "Total Items by LC Class";



include "includes/header.php";



?>

<script type="text/javascript">

function getresults(year,series,lcclass){
	$("#itemsgohere").load("results.php?year=" + year + "&series=" + series + "&lcclass=" + lcclass );
	
}



		 
		 
</script>



    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
  
  
<?php include "includes/navbar.php";?>


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


      
            <h2>Total Items by LC Class</h2>
<div id="chartholder">
<canvas id="mainchart"></canvas>
</div>
<div id="itemsgohere"></div>




       <?php include "includes/chart_overview.php"; ?>





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

//if (curclass == -1){
//var findclass = "false";	
//}
//else {var findclass = curclass-1;}

  $( "#accordion" ).accordion({
  heightStyle: "content",
  collapsible: true,
  active:false,
  animate:{
	duration:200  
  }
});



});



    </script>
  </body>
</html>
