 <?php

//set variable names to prevent "undefined index" errors in log	
if (isset($_GET['lcclass'])) {
    $lcclass = $_GET['lcclass'];
} else {
    $lcclass = "";
}

//check if it's a single letter only (i.e., distinguish between all of B, and B1-B9999; the latter will be represneted as B0
if ((strlen($lcclass) == 2) and ($lcclass[1] == '0')) {
    $lcclass = $lcclass[0] . "[0-9]";
}


include "includes/dbconnect.php";

include "includes/preparedates_multi.php";


//get group -1
$sql  = 'SELECT copyrightdate as label,  count(*) as value FROM ' . THIS_TABLE . ' WHERE itemcallnumber REGEXP :lcclass and language = "eng" GROUP BY copyrightdate';
$stmt = $pdo->prepare($sql);
$stmt->execute(array(
    ':lcclass' => "^" . $lcclass
));
//use fetch group/unique to bring back years as keys---don't know how this works
$groupxresults = $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_UNIQUE);
//make sure all years show up; if not, add missing ones
foreach ($allyears as $thisdate) {
    if (!array_key_exists($thisdate, $groupxresults)) {
        $newdata1                 = array(
            'value' => ''
        );
        $groupxresults[$thisdate] = $newdata1;
    }
}
//sort to include the new years in the right spots
ksort($groupxresults);
//end getting group -1



//get group 0
$sql  = 'SELECT copyrightdate as label,  count(*) as value FROM ' . THIS_TABLE . ' WHERE itemcallnumber REGEXP :lcclass and language = "ger" GROUP BY copyrightdate';
$stmt = $pdo->prepare($sql);
$stmt->execute(array(
    ':lcclass' => "^" . $lcclass
));
//use fetch group/unique to bring back years as keys---don't know how this works
$group0results = $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_UNIQUE);
//make sure all years show up; if not, add missing ones
foreach ($allyears as $thisdate) {
    if (!array_key_exists($thisdate, $group0results)) {
        $newdata1                 = array(
            'value' => ''
        );
        $group0results[$thisdate] = $newdata1;
    }
}
//sort to include the new years in the right spots
ksort($group0results);
//end getting group 0


//get group 1
$sql  = 'SELECT copyrightdate as label,  count(*) as value FROM ' . THIS_TABLE . ' WHERE itemcallnumber REGEXP :lcclass and language = "fre" GROUP BY copyrightdate';
$stmt = $pdo->prepare($sql);
$stmt->execute(array(
    ':lcclass' => "^" . $lcclass
));
//use fetch group/unique to bring back years as keys---don't know how this works
$group1results = $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_UNIQUE);
//make sure all years show up; if not, add missing ones
foreach ($allyears as $thisdate) {
    if (!array_key_exists($thisdate, $group1results)) {
        $newdata1                 = array(
            'value' => ''
        );
        $group1results[$thisdate] = $newdata1;
    }
}
//sort to include the new years in the right spots
ksort($group1results);
//end getting group 1

//get group 2
$sql  = 'SELECT copyrightdate as label,  count(*) as value FROM ' . THIS_TABLE . ' WHERE itemcallnumber REGEXP :lcclass and language = "spa" GROUP BY copyrightdate';
$stmt = $pdo->prepare($sql);
$stmt->execute(array(
    ':lcclass' => "^" . $lcclass
));
//use fetch group/unique to bring back years as keys---don't know how this works
$group2results = $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_UNIQUE);
//make sure all years show up; if not, add missing ones
foreach ($allyears as $thisdate) {
    if (!array_key_exists($thisdate, $group2results)) {
        $newdata1                 = array(
            'value' => ''
        );
        $group2results[$thisdate] = $newdata1;
    }
}
//sort to include the new years in the right spots
ksort($group2results);
//end getting group 2

//get group 3
$sql  = 'SELECT copyrightdate as label,  count(*) as value FROM ' . THIS_TABLE . ' WHERE itemcallnumber REGEXP :lcclass and language <> "eng" and language <> "ger" and language <> "fre" and language <> "spa" and language is not null GROUP BY copyrightdate';
$stmt = $pdo->prepare($sql);
$stmt->execute(array(
    ':lcclass' => "^" . $lcclass
));
//use fetch group/unique to bring back years as keys---don't know how this works
$group3results = $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_UNIQUE);
//make sure all years show up; if not, add missing ones
foreach ($allyears as $thisdate) {
    if (!array_key_exists($thisdate, $group3results)) {
        $newdata1                 = array(
            'value' => ''
        );
        $group3results[$thisdate] = $newdata1;
    }
}
//sort to include the new years in the right spots
ksort($group3results);
//end getting group 3



$jsoncategories = json_encode($alldates);

$jsongroupx = json_encode(array_values($groupxresults));
$jsongroup0 = json_encode(array_values($group0results));
$jsongroup1 = json_encode(array_values($group1results));
$jsongroup2 = json_encode(array_values($group2results));
$jsongroup3 = json_encode(array_values($group3results));

//set page title here
$pagetitle = "Total Items by Copyright Date";


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
     $('input:radio[id=<?php echo $_GET['lcclass']; ?>]').prop('checked', true);
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
<?php
include 'includes/navbar_class.php';
?>


    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
    
      <form name="filteroptions" method="get" action="langbreakout.php">
      
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
<?php
if ($lcclass == "") {
    $lcdisplay = "All Classes";
} elseif ((strlen($lcclass) > 1) and ($lcclass[1] == '[')) {
    $lcdisplay = "LC Class: " . $lcclass[0] . "1 - " . $lcclass[0] . "9999";
} else {
    $lcdisplay = "LC Class: " . $lcclass;
}
?> 
<h2><?php
echo $lcdisplay;
?> <?php
include "includes/expanded.php";
?></h2>     
            
          <span class="label label-success label-sm">Current Layer: Language</span>
</div>
</div>

   
<div id="chartholder">
<canvas id="mainchart"></canvas>
</div>
<div id="itemsgohere"></div>


<?php
include "includes/chart_lang.php";
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
    
<?php
include "includes/loadaccordian.php";
?>

  </body>
</html>
