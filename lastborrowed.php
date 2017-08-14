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

//set upper limits of groups here
$group0 = $last1;
$group1 = $last2;

//get group 0
$sql  = 'SELECT LEFT(itemcallnumber,1) as label, count(*) as value FROM ' . THIS_TABLE . ' WHERE lastborrowed is null GROUP BY LEFT(itemcallnumber,1)';
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
$sql  = 'SELECT LEFT(itemcallnumber,1) as label,  count(*) as value FROM ' . THIS_TABLE . ' WHERE LEFT(lastborrowed,4) >= :group0 and LEFT(lastborrowed,4) <= :group1 GROUP BY LEFT(itemcallnumber,1)';
$stmt = $pdo->prepare($sql);
$stmt->execute(array(
    ':group0' => $group0,
    ':group1' => $group1
));
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
$sql  = 'SELECT LEFT(itemcallnumber,1) as label,  count(*) as value FROM ' . THIS_TABLE . ' WHERE LEFT(lastborrowed,4) > :group1 GROUP BY LEFT(itemcallnumber,1)';
$stmt = $pdo->prepare($sql);
$stmt->execute(array(
    ':group1' => $group1
));
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


$jsoncategories = json_encode($alldates);
$jsongroup0     = json_encode(array_values($group0results));
$jsongroup1     = json_encode(array_values($group1results));
$jsongroup2     = json_encode(array_values($group2results));


//make new array to hold percentages
$percents = array();

foreach ($group0results as $tkey => $tval) {
    
    $percentstemp = array(
        $tval['value'],
        $group1results[$tkey]['value'],
        $group2results[$tkey]['value']
    );
    
    $percents[$tkey] = $percentstemp;
    
}

//set page title here
$pagetitle = "Last Checkout Date";


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
    
      <form name="filteroptions" method="get" action="lastborrowedbreakout.php">
      
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
<h2>All Items by LC Class</h2>     
          
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

foreach ($percents as $showkey => $showval) {
    
    //total num items
    $totalnum = $showval[0] + $showval[1] + $showval[2];
    
    echo "<tr><td>";
    echo $showkey;
    echo "</td><td>";
    echo round($showval[0] / $totalnum * 100, 2) . "%";
    echo "</td><td>";
    echo round($showval[1] / $totalnum * 100, 2) . "%";
    echo "</td><td>";
    echo round($showval[2] / $totalnum * 100, 2) . "%";
    echo "</td>";
    echo "</tr>";
}

?>
</tbody>
</table>

</div>



  
<?php
include "includes/chart_lastborrowed.php";
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
