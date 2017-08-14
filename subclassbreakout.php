 <?php




	if (isset($_GET['lcclass'])){ 
	
	
	$lcclass = $_GET['lcclass']; 
	if (strlen($lcclass) > 1){
		  header( 'Location: overviewbreakout.php?lcclass=' . $lcclass ) ; 
		 
	 }
	
	}
	else {	
	
 header( 'Location: overview.php' ) ;
	$lcclass = "";
	
	}
	
	

	
include "includes/dbconnect.php";


//first get all possible subclasses in this query
$sql0 = 'SELECT LEFT(cn_sort,2) as label FROM ' . THIS_TABLE . ' WHERE itemcallnumber LIKE :lcclass GROUP BY LEFT(cn_sort,2)';
$stmt = $pdo->prepare($sql0);
$stmt->execute( array(':lcclass' => $lcclass . "%") );
$alldates = $stmt->fetchAll();
$datesonly = array();
foreach ($alldates as $v2){
$datesonly[] = 	$v2['label'];
}
//combine all results with a single character sublcass into one (e.g., B1000 and B2000 show up as B1 and B2, make them all B0 instead)
foreach ($datesonly as &$v3){
if (is_numeric(substr($v3,1,1)))	{ 
$v3 = substr($v3,0,1) . "0";
}
	
}

$datesonly = array_unique($datesonly);


$sql = 'SELECT LEFT(cn_sort,2) as label,  count(*) as value FROM ' . THIS_TABLE . ' WHERE itemcallnumber LIKE :lcclass GROUP BY LEFT(cn_sort,2)';
$stmt = $pdo->prepare($sql);
$stmt->execute( array(':lcclass' => $lcclass . "%") );
$recordresults = $stmt->fetchAll();


//combine all results with a single character sublcass into one (e.g., B1000 and B2000 show up as B1 and B2, make them all B0 instead)
$singlecharlabel = $lcclass;
$singlecharvalue = 0;
foreach ($recordresults as $k4=>$v4){
if (is_numeric(substr($v4['label'],1,1)))	{ 
 $singlecharvalue+= $v4['value'];
 //remove it from the master array, we will add total later
   unset($recordresults[$k4]);
}
}

$letteronlyarray = array("label" => $singlecharlabel . "0" ,"value" => $singlecharvalue);
$recordresults[] = $letteronlyarray;
//sort array to force the new one at the beginning
asort($recordresults);
//reset array keys
$recordresults = array_values($recordresults);

//insert link in each one to make chart clickable
foreach  ($recordresults as $k4 => $v4){
	if ($v4['value'] <> ""){
		//insert correspreond lc class to make link
		$recordresults[$k4]['link'] = "overviewbreakout.php?lcclass=" . $v4['label'];
	}
}

$jsonresults = json_encode($recordresults);




//set page title here
$pagetitle = "Total Items by Subclass";



include "includes/header.php";


?>



<script type="text/javascript">

function getresults(year,series,lcclass){
	$("#itemsgohere").load("results.php?year=" + year + "&series=" + series + "&lcclass=" + lcclass );
	
}

$(document).ready(function(e) {

var url = window.location.href;
var matchvar = url.match(/\?lcclass=[A-Z]/gi);
if(url.indexOf(matchvar) != -1){
     $('input:radio[id=<?php echo $lcclass ?>]').prop('checked', true);
}
});


		 
		 
</script>



    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

   <?php include "includes/navbar_subclass.php";?>


    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
    
      <form name="filteroptions" method="get" action="subclassbreakout.php">
      
<div id="accordion">
<?php include "includes/classmenu.php";?>

</div>
</div>
</form>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                 <?php include "includes/collapseleft.php" ?>  
    <h2>Total Items by Subclass</h2>
                        <h3><?php if ($lcclass == ""){echo "All Classes";} else {echo "LC Class: " . $lcclass;} ?></h3>        

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
<?php include "includes/loadaccordian.php"; ?>
  </body>
</html>
