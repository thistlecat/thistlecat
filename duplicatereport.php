<?php



	
include "includes/dbconnect.php";


$thisclass = $_GET["lcclass"]; 

//set page title here
$pagetitle = "Alternate Editions Report";



include "includes/header.php";

?>


<script type="text/javascript">
$(document).ready(function() 
    { 
   var url = window.location.href;
var matchvar = url.match(/\?lcclass=[A-Z]/gi);
if(url.indexOf(matchvar) != -1){
     $('input:radio[id=<?php echo $thisclass ?>]').prop('checked', true);
}
	
    } 
); 



</script>

 <link href="css/grid.css" rel="stylesheet">



		 
		 
</script>

<style type="text/css">
#lclist li {
    list-style:none;
	margin-left:-20px;
   
}#accordion label{
font-size:0.85em;display: inline !important;
font-weight:normal;
}

td img { display: inline-block; }
th { cursor: pointer; }
</style>

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
        
    <form name="filteroptions" action="duplicatereport.php">  
  <div id="accordion">
<?php include "includes/classmenu.php";?>

</div>
</div>
   </form>
        </div>
        
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                         <?php include "includes/collapseleft.php" ?>  

        <h3>Alternate Editions Report for LC Class <?php echo  $thisclass; ?></h3>
        <a download="somedata.csv" href="#" onClick="return ExcellentExport.csv(this, 'resultstab');">
        <button type="button" class="btn btn-success">
       <i class="fa fa-floppy-o" aria-hidden="true"></i> Export Results as CSV
        </button>
        </a>

        <?php
//see if we're on a results page or not
$url = $_SERVER['REQUEST_URI'];
$parsedurl = parse_url($url);
if (!isset($parsedurl['query'])){
	
	echo "";
	
	
	}
else {
    
  
 echo '<div class="table-responsive">
            <table class="table table-striped table-condensed tablesorter table-bordered" id="resultstab">
              <thead>
                <tr>
                  <th>Call Number</th>
                  <th>Barcode</th>
                  <th class="col-md-3">Title</th>
				  <th>Author</th>
                  <th>Date</th>
                  <th>Total Checkouts</th>
                  <th>Last Checkout Date</th>
             
                  <th>Special Attributes</th>
                
                  <th>Catalog Links</th>
                </tr>
              </thead>
              <tbody>';







$sql0 = 'SELECT oclc,duplicates FROM ' . THIS_TABLE . ' WHERE itemcallnumber LIKE :thisclass AND duplicates is not null ORDER BY cn_sort ASC';

$stmt = $pdo->prepare($sql0);
$stmt->execute( array(':thisclass' => $thisclass . "%") );
$searchresults = $stmt->fetchAll();

if (count($searchresults) > 5000){echo count($searchresults) . " results -- too many!"; exit(); }

echo '<div class="container"><div class="row text-center"><strong>' . count($searchresults) . " Items</strong></div><div class='row'>" ;
echo '</div></div>';


$allresults = array();
foreach ($searchresults as $v2){
	
//combine oclc number and dups field to make one big string of all related numbers
//trim leading zeroes from the oclc field to match, since the duplicates are truncated
$allnumbers = ltrim($v2['oclc'], '0') . "," . $v2['duplicates']; 
	
//convert to array
$duparray = explode(",", $allnumbers);
//sort array so we can match duplicated entries
sort($duparray);	
$allresults[] = $duparray;

}

//function to pad numbers back to 8 digits
function pad($n)
{
if (strlen($n) < 8) { 
$m = str_pad($n, 8, "0", STR_PAD_LEFT);
return($m);
	}
else {return ($n);}
}

$input = array_map("unserialize", array_unique(array_map("serialize", $allresults)));

foreach ($input as $entry){	
//pad numbers back to 8 digits
$paddedentry = array_map("pad", $entry);
$comma_separated = implode("' or oclc = '", $paddedentry);
$comma_separated = "oclc = '" . $comma_separated . "'";

//query each number
$sql5 = 'SELECT title,author,itemcallnumber,cn_sort,issues,copyrightdate,barcode,oclc,biblionumber,lastborrowed,viva,duplicates FROM ' . THIS_TABLE . ' WHERE ' . $comma_separated . ' GROUP BY oclc';
$stmt5 = $pdo->prepare($sql5);
$stmt5->execute(  );
$searchresults5 = $stmt5->fetchAll();
foreach ($searchresults5 as $v2){

echo "<tr>";
echo "<td>" . $v2['itemcallnumber'] . "</td>";	
echo "<td>" . $v2['barcode'] . "</td>";	
echo "<td  class='col-md-3'>" . $v2['title']. "</td>";
echo "<td>" . $v2['author']. "</td>";
echo "<td  title='Copyright Date'>" . $v2['copyrightdate'] . "</td>";	
echo "<td  title='Total Checkouts'>" . $v2['issues'] . "</td>";
echo "<td  title='Last Checkout Date'>" . substr($v2['lastborrowed'],0,4) . "</td>";		
echo "<td>";
if ($v2['viva']) {
echo "<span class='label label-success'>VIVA</span>";
}
echo "</td>";	
echo "<td><a target='_blank' href='https://kohastaff.vmi.edu/cgi-bin/koha/catalogue/search.pl?q=" .  $v2['barcode'] . "'><img src='koha.jpg'></a> <a target='_blank' href='http://vmi.worldcat.org/oclc/" . $v2['oclc'] . "'><img src='worldcat.png'></a></td>";	

echo "</tr>";
}
echo "<tr><td colspan='11' bgcolor='#999'></td></tr>";


}





echo ' </tbody>
            </table>';

 }
?>






    
 
          
            </div>
    
<small>Data last updated: Jan 4, 2016</small>

       
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
   var curclass=	$("#<?php echo substr($thisclass,0,1) ?>class").parent().children('h3').index($('#<?php echo substr($thisclass,0,1) ?>class'));
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
