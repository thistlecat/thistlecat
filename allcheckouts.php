<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

 <?php




	//if (isset($_GET['lcclass'])){ $lcclass = $_GET['lcclass']; }
	//else {	$lcclass = "";}
	
	//if ($lcclass == ""){exit();}
	

	
include "config.php";
include "includes/dbconnect.php";

$pdo = new PDO($dsn,COLL_USER,COLL_PASS, $opt);



$sql = 'SELECT issues as label, count(*) as value FROM ' . THIS_TABLE . ' WHERE issues > 0 GROUP BY issues';
$stmt = $pdo->prepare($sql);
$stmt->execute();
$allyears = $stmt->fetchAll();



$jsonresults = json_encode($allyears);



//set page title here
$pagetitle = "Number of Items by Total Checkouts";


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

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <img style="float:left;" src="milkwhite.png" width="50"><a class="navbar-brand" href="index.php">ThistleCAT <?php echo $libraryname; ?></a>
        </div>
       
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
    
      <form name="filteroptions" method="get" action="issues.php">
      
<div id="accordion">
<?php
//generate LC class menu
$sql1 = 'SELECT * FROM LCclasses';

$stmt1 = $pdo->prepare($sql1);
$stmt1->execute();

$recordresults1 = $stmt1->fetchAll();
$prev = null;
foreach ($recordresults1 as $val){
if (strlen($val['class']) == 1){
//check if last one was a last child that needs to be closed	
	if (strlen($prev) > 1){echo "</div>";}
	echo '<h3 id="' . $val['class']. 'class" class="parent"><input id="' .  $val['class'] . '" type="radio" name="lcclass" value="' .  $val['class'] . '" onclick="this.form.submit();"> <label for="' .  $val['class'] . '">' .  $val['class'] . ' <small>' . $val['description'] . '</small></label></h3>';
}
else{
	//check if last element was the parent or not
	if (strlen($prev) == 1){echo "<div>";}
	
	echo '<input id="' .  $val['class'] . '" type="radio" name="lcclass" value="' .  $val['class'] . '" onclick="this.form.submit();"> <label for="' .  $val['class'] . '"><strong>' .  $val['class'] . '</strong> <small>' . $val['description'] . '</small></label><br />';
}

$prev = $val['class'];
}
?>
</div>
</div>
</form>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                        <h2>Number of Items by Total Checkouts</h2>

        <div id="chartholder">
<canvas id="mainchart"></canvas>
</div>


<?php include "includes/chart_general.php"; 
display_chart("Total Checkouts");
?>

<div id="itemsgohere">Plus ~100,000 items with 0 checkouts</div>
       
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
