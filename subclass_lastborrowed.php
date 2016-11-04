 <?php
 


	if (isset($_GET['lcclass'])){ 
	
	$lcclass = $_GET['lcclass'];
	 if (strlen($lcclass) > 1){
		  header( 'Location: lastborrowedbreakout.php?lcclass=' . $lcclass ) ; 
		 
	 }
	
	 }
	else {	
	   header( 'Location: lastborrowed.php' ) ;
	$lcclass = "";
	
	}
	

	
	


 




	
include "config.php";	
include "includes/dbconnect.php";



//first get all possible subclasses in this query
$sql0 = 'SELECT LEFT(cn_sort,2) as label, count(*) as value FROM ' . THIS_TABLE . ' WHERE itemcallnumber LIKE :lcclass GROUP BY LEFT(cn_sort,2)';
$stmt = $pdo->prepare($sql0);
$stmt->execute( array(':lcclass' => $lcclass . "%") );
$alldates = $stmt->fetchAll();



$classesonly = array();
foreach ($alldates as $v2){
$classesonly[] = 	$v2['label'];
}


$singlecharlabel = $lcclass;
$singlecharvalue = '';
foreach ($alldates as $k4=>$v4){
if (is_numeric(substr($v4['label'],1,1)))	{ 
 $singlecharvalue+= $v4['value'];
 //remove it from the master array, we will add back later
   unset($alldates[$k4]);
}
}

$letteronlyarray = array("label" => $singlecharlabel . "0" ,"value" => $singlecharvalue);
$alldates[] = $letteronlyarray;

//sort by keys to force the new one at the beginning
ksort($alldates);


//combine all results with a single character sublcass into one (e.g., B1000 and B2000 show up as B1 and B2, make them all B0 instead)
foreach ($classesonly as &$v3){
if (is_numeric(substr($v3,1,1)))	{ 
$v3 = substr($v3,0,1) . "0";
}
	
}


$classesonly = array_unique($classesonly);


//set upper limits of groups here
$group0 = $last1;
$group1 = $last2;



//get group 0
$sql = 'SELECT LEFT(cn_sort,2) as label, count(*) as value FROM ' . THIS_TABLE . ' WHERE itemcallnumber LIKE :lcclass and lastborrowed is null GROUP BY LEFT(cn_sort,2)';
$stmt = $pdo->prepare($sql);
$stmt->execute( array(':lcclass' => $lcclass . "%") );
//use fetch group/unique to bring back years as keys---don't know how this works
$group0results = $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_UNIQUE);



//combine all results with a single character sublcass into one (e.g., B1000 and B2000 show up as B1 and B2, make them all B0 instead)
$singlecharlabel = $lcclass;
$singlecharvalue = '';
foreach ($group0results as $k4=>$v4){
if (is_numeric(substr($k4,1,1)))	{ 
 $singlecharvalue+= $v4['value'];
 //remove it from the master array, we will add total later
   unset($group0results[$k4]);
}
}


$letteronlyarray = array("value" => $singlecharvalue);
$group0results[$singlecharlabel . "0"] = $letteronlyarray;




//sort arraygroup0resultsto force the new one at the beginning
ksort($group0results);





//make sure all years show up; if not, add missing ones
foreach ($classesonly as $thisclass){
	if (!array_key_exists($thisclass, $group0results)) {
	$newdata1 =  array (
      'value' => ''
    );
		$group0results[$thisclass] = $newdata1;
}	
}



//sort to include the new years in the right spots
ksort($group0results);




//end getting group 0


//get group 1
$sql = 'SELECT LEFT(cn_sort,2) as label,  count(*) as value FROM ' . THIS_TABLE . ' WHERE itemcallnumber LIKE :lcclass and LEFT(lastborrowed,4) >= :group0 and LEFT(lastborrowed,4) <= :group1 GROUP BY LEFT(cn_sort,2)';
$stmt = $pdo->prepare($sql);
$stmt->execute( array( ':group0' => $group0,':group1' => $group1,':lcclass' => $lcclass . "%" ) );
//use fetch group/unique to bring back years as keys---don't know how this works
$group1results = $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_UNIQUE);



//combine all results with a single character sublcass into one (e.g., B1000 and B2000 show up as B1 and B2, make them all B0 instead)
$singlecharlabel = $lcclass;
$singlecharvalue = '';
foreach ($group1results as $k4=>$v4){
if (is_numeric(substr($k4,1,1)))	{ 
 $singlecharvalue+= $v4['value'];
 //remove it from the master array, we will add total later
   unset($group1results[$k4]);
}
}


$letteronlyarray = array("value" => $singlecharvalue);
$group1results[$singlecharlabel . "0"] = $letteronlyarray;




//sort arraygroup0resultsto force the new one at the beginning
ksort($group1results);





//make sure all years show up; if not, add missing ones
foreach ($classesonly as $thisclass){
	if (!array_key_exists($thisclass, $group1results)) {
	$newdata1 =  array (
      'value' => ''
    );
		$group0results[$thisclass] = $newdata1;
}	
}



//sort to include the new years in the right spots
ksort($group1results);



//end getting group 1

//get group 2
$sql = 'SELECT LEFT(cn_sort,2) as label,  count(*) as value FROM ' . THIS_TABLE . ' WHERE itemcallnumber LIKE :lcclass AND LEFT(lastborrowed,4) > :group1 GROUP BY LEFT(cn_sort,2)';
$stmt = $pdo->prepare($sql);
$stmt->execute( array(':group1' => $group1,':lcclass' => $lcclass . "%" ) );
//use fetch group/unique to bring back years as keys---don't know how this works
$group2results = $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_UNIQUE);



//combine all results with a single character sublcass into one (e.g., B1000 and B2000 show up as B1 and B2, make them all B0 instead)
$singlecharlabel = $lcclass;
$singlecharvalue = '';
foreach ($group2results as $k4=>$v4){
if (is_numeric(substr($k4,1,1)))	{ 
 $singlecharvalue+= $v4['value'];
 //remove it from the master array, we will add total later
   unset($group2results[$k4]);
}
}


$letteronlyarray = array("value" => $singlecharvalue);
$group2results[$singlecharlabel . "0"] = $letteronlyarray;




//sort arraygroup0resultsto force the new one at the beginning
ksort($group2results);





//make sure all years show up; if not, add missing ones
foreach ($classesonly as $thisclass){
	if (!array_key_exists($thisclass, $group2results)) {
	$newdata1 =  array (
      'value' => ''
    );
		$group2results[$thisclass] = $newdata1;
}	
}



//sort to include the new years in the right spots
ksort($group2results);



//end getting group 2


$alldates = array_values($alldates);


$jsoncategories = json_encode($alldates);
$jsongroup0 = json_encode(array_values($group0results));
$jsongroup1 = json_encode(array_values($group1results));
$jsongroup2 = json_encode(array_values($group2results));


//make new array to hold percentages
$percents = array();


foreach ($group0results as $tkey => $tval){
	
	$percentstemp =  array (
     $tval['value'],
	 $group1results[$tkey]['value'],
	 $group2results[$tkey]['value'],
    );

$percents[$tkey] = $percentstemp;
	
}


//set page title here
$pagetitle = "LC Subclasses";



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
     $('input:radio[id=<?php echo $_GET['lcclass'];  ?>]').prop('checked', true);
}
else{
	     $('input:radio[id=All]').prop('checked', true);

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
    
      <form name="filteroptions" method="get" action="<?php echo basename(__FILE__); ?>">
      
<div id="accordion">
<?php include "includes/classmenu.php";?>

</div>
</div>
</form>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                 <?php include "includes/collapseleft.php" ?>  
               <div class="row">
<div class="col-md-12">
<h2>Total Items by Subclass: <?php echo $lcclass; ?></h2>     
          
          <span class="label label-success label-sm">Current Layer: Last Checkout Date</span>
</div>
</div> 
     
     
              
<div id="chartholder">
<canvas id="mainchart"></canvas>
</div>



<div id="itemsgohere">


  <table class="table table-striped table-condensed tablesorter table-bordered" id="resultstab" style="width:60%;margin: auto;">
              <thead>
                <tr>
                  <th>Call Number</th>
                  <th>< 2003 / Never</th>
                  <th>2003-2013</th>
                  <th>> 2013</th>
                </tr>
              </thead>
              <tbody>
			  <?php

foreach ($percents as $showkey => $showval){
	
//total num items
$totalnum = $showval[0] + $showval[1] + $showval[2];
	
echo "<tr><td>";
echo $showkey;
echo "</td><td>";
echo round($showval[0]/$totalnum * 100, 2) . "%";
echo "</td><td>";
echo round($showval[1]/$totalnum * 100, 2) . "%";
echo "</td><td>";
echo round($showval[2]/$totalnum * 100, 2) . "%";
echo "</td>";
echo "</tr>";
}

?>
</tbody>
</table>
</div>

<?php include "includes/chart_lastborrowed.php"; 
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
$("#resultstab").tablesorter(); 

//get position of chosen lc class among accordion headers so we know which one to expand
   var curclass=	$("#<?php echo substr($lcclass,0,1) ?>class").parent().children('h3').index($('#<?php echo substr($lcclass,0,1) ?>class'));
if (curclass == -1){
var findclass = false;	
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
