 <?php

include "config.php";
include "includes/dbconnect.php";

//first get all possible classifications in this query
$sql0 = 'SELECT LEFT(class,1) as label FROM LCclasses GROUP BY LEFT(class,1)';
$stmt = $pdo->prepare($sql0);
$stmt->execute();
$alldates    = $stmt->fetchAll();
$classesonly = array();
foreach ($alldates as $v2) {
    $classesonly[] = $v2['label'];
}


//get group x
$sql  = 'SELECT LEFT(itemcallnumber,1) as label, count(*) as value FROM ' . THIS_TABLE . ' WHERE language = "eng" GROUP BY LEFT(itemcallnumber,1)';
$stmt = $pdo->prepare($sql);
$stmt->execute();
//use fetch group/unique to bring back years as keys---don't know how this works
$groupxresults = $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_UNIQUE);
//make sure all years show up; if not, add missing ones
foreach ($classesonly as $thisclass) {
    if (!array_key_exists($thisclass, $groupxresults)) {
        $newdatax                  = array(
            'value' => ''
        );
        $groupxresults[$thisclass] = $newdatax;
    }
}

//sort to include the new years in the right spots
ksort($groupxresults);
//end getting group x

//get group 0
$sql  = 'SELECT LEFT(itemcallnumber,1) as label, count(*) as value FROM ' . THIS_TABLE . ' WHERE language = "ger" GROUP BY LEFT(itemcallnumber,1)';
$stmt = $pdo->prepare($sql);
$stmt->execute();
//use fetch group/unique to bring back years as keys---don't know how this works
$group0results = $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_UNIQUE);
//make sure all years show up; if not, add missing ones
foreach ($classesonly as $thisclass) {
    if (!array_key_exists($thisclass, $group0results)) {
        $newdata1                  = array(
            'value' => ''
        );
        $group0results[$thisclass] = $newdata1;
    }
}
//sort to include the new years in the right spots
ksort($group0results);
//end getting group 0


//get group 1
$sql  = 'SELECT LEFT(itemcallnumber,1) as label,  count(*) as value FROM ' . THIS_TABLE . ' WHERE language = "fre" GROUP BY LEFT(itemcallnumber,1)';
$stmt = $pdo->prepare($sql);
$stmt->execute();
//use fetch group/unique to bring back years as keys---don't know how this works
$group1results = $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_UNIQUE);
//make sure all classes show up; if not, add missing ones
foreach ($classesonly as $thisclass) {
    if (!array_key_exists($thisclass, $group1results)) {
        $newdata1                  = array(
            'value' => ''
        );
        $group1results[$thisclass] = $newdata1;
    }
}
//sort to include the new years in the right spots
ksort($group1results);
//end getting group 1

//get group 2
$sql  = 'SELECT LEFT(itemcallnumber,1) as label,  count(*) as value FROM ' . THIS_TABLE . ' WHERE language = "spa" GROUP BY LEFT(itemcallnumber,1)';
$stmt = $pdo->prepare($sql);
$stmt->execute();
//use fetch group/unique to bring back years as keys---don't know how this works
$group2results = $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_UNIQUE);
//make sure all years show up; if not, add missing ones
foreach ($classesonly as $thisclass) {
    if (!array_key_exists($thisclass, $group2results)) {
        $newdata1                  = array(
            'value' => ''
        );
        $group2results[$thisclass] = $newdata1;
    }
}
//sort to include the new years in the right spots
ksort($group2results);
//end getting group 2

//get group 3
$sql  = 'SELECT LEFT(itemcallnumber,1) as label,  count(*) as value FROM ' . THIS_TABLE . ' WHERE language <> "eng" and language <> "ger" and language <> "fre" and language <> "spa" and language is not null GROUP BY LEFT(itemcallnumber,1)';
$stmt = $pdo->prepare($sql);
$stmt->execute();
//use fetch group/unique to bring back years as keys---don't know how this works
$group3results = $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_UNIQUE);
//make sure all years show up; if not, add missing ones
foreach ($classesonly as $thisclass) {
    if (!array_key_exists($thisclass, $group3results)) {
        $newdata1                  = array(
            'value' => ''
        );
        $group3results[$thisclass] = $newdata1;
    }
}
//sort to include the new years in the right spots
ksort($group3results);
//end getting group 3

$jsoncategories = json_encode($alldates);
$jsongroup0     = json_encode(array_values($group0results));
$jsongroup1     = json_encode(array_values($group1results));
$jsongroup2     = json_encode(array_values($group2results));
$jsongroup3     = json_encode(array_values($group3results));

//set page title here
$pagetitle = "All Foreign Language Items by LC Class";

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

   <?php
include "includes/navbar.php";
?>


    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
    
      <form name="filteroptions" method="get" action="languagesbreakout.php">
      
<div id="accordion">
<?php
include "includes/classmenu.php";
?>

</div>
</div>
</form>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                 <?php
include "includes/collapseleft.php";
?>  
               <div class="row">
<div class="col-md-12">
<h2>All Foreign Language Items by <a href="#" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">LC Class</a> <a href="languagesbreakout.php" class="btn btn-secondary btn-lg active" role="button" aria-pressed="true">By Language</a></h2>     
          
</div>
</div> 
   
   
<div id="chartholder">
<canvas id="mainchart"></canvas>
</div>
<div id="itemsgohere"></div>


<?php
include "includes/chart_lang.php";
display_chart("LC Class");
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
//get position of chosen lc class among accordion headers so we know which one to expand



// get current URL path and assign 'active' class
var pagename =  window.location.href.split("/").slice(-1);
$('#navbar a[href="'+pagename+'"]').parent().addClass('active');
 
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
