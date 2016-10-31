<?php
 session_start();


$barcodequery = $_SESSION["uploadquery"];




include "config.php";
	
include "includes/dbconnect.php";


	
	$sql0 = 'SELECT copyrightdate as label, count(*) as value FROM ' . THIS_TABLE . ' WHERE ' . $barcodequery . ' GROUP BY copyrightdate';
	$stmt = $pdo->prepare($sql0);
	$stmt->execute(  );
	$allyears = $stmt->fetchAll();

	
	
	//make array of dates only
	$datesonly = array();
	foreach ($allyears as $thisv){
	$datesonly[] =  $thisv['label'];
	}

	//get earliest date in array
	asort($datesonly);
	
	//make array of desired range (earliest book through present year)
	//check if first value is zero, drop if so
	if ($datesonly[0] == '0'){$startingval = $datesonly[1];}
	else{$startingval = $datesonly[0];}

	foreach (range($startingval, date("Y")) as $number1) {
	   
	   if (!in_array($number1, $datesonly)) {
		$allyears[] = array(
		"label" => $number1,
		"value" => '0',
		);
	
		}
	   
	}

	
	//sort the new array with the empty years
	// Obtain a list of columns
	foreach ($allyears as $key => $row) {
		$label[$key]  = $row['label'];
		$value[$key] = $row['value'];
	}
	
	// Sort the data with volume descending, edition ascending
	// Add $data as the last parameter, to sort by the common key
	array_multisort($label, SORT_ASC, $allyears);

	
	//make json array for whichever data we're charting
	$jsonresults = json_encode($allyears);
	
	
	

	//now get full item list
	$sql5 = 'SELECT title,author,itemcallnumber,cn_sort,issues,copyrightdate,barcode,oclc,biblionumber,lastborrowed,special,duplicates,language FROM ' . THIS_TABLE . ' WHERE ' . $barcodequery . ' ORDER BY cn_sort ASC';
	$stmt5 = $pdo->prepare($sql5);
	$stmt5->execute(  );
	$allresultsitems = $stmt5->fetchAll();


//set page title here
$pagetitle = "Uploaded Barcode File";



include "includes/header.php";

?>

    

<script type="text/javascript">

function getresults(year,series,lcclass){
	//check if second character is lowercase d - if so, it's a first letter only class (B0)

	$("#itemsgohere").load("results.php?year=" + year + "&series=" + series + "&lcclass=" + lcclass );
	
}

$(document).ready(function(e) {

//enable table sorter
$("#resultstab").tablesorter(); 
$("#resultstab1").tablesorter(); 

});



		 
		 
</script>
<script type="text/javascript" src="js/excellentexport.js"></script> 




    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <?php include "includes/navbar_barcodes.php"; ?>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
    
     
   
<h4>Upload file</h4>


      
</div>



</div>

        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <!--     <h1 class="page-header">All Item by Copyright Date</h1> -->
   
           <h2><?php 
		  echo "Uploaded Barcode File: ". " " . count($allresultsitems) . " items"; 

		   
		   ?></h2>        


<div id="chartholder">
<canvas id="mainchart"></canvas>
</div>



<div id="itemsgohere">
 
<?php
//only display table if we're on an author page


echo ' <a download="somedata.csv" href="#" onclick="return ExcellentExport.csv(this, \'resultstab1\');"> 
  <button type="button" class="btn btn-success">
        <i class="fa fa-floppy-o" aria-hidden="true"></i> Export Results as CSV
        </button>
        </a>


 <div class="table-responsive">
            <table class="table-hover table-striped table-condensed tablesorter table-bordered table" id="resultstab1">
              <thead>
                <tr>
                  <th>Call Number</th>
                  <th>Barcode</th>
                  <th>Title</th>
				  <th>Author</th>
                  <th>Date</th>
                  <th>Language</th>
                  <th>Total Checkouts</th>
                  <th>Last Checkout Date</th>
                
                 <th>Special Attributes</th>
              
                  <th>Catalog Links</th>
                </tr>
              </thead>
              <tbody>';

foreach ($allresultsitems as $v2){
echo "<tr>";
echo "<td>" . $v2['itemcallnumber'] . "</td>";	
echo "<td>" . $v2['barcode'] . "</td>";	
echo "<td  class='col-md-3'>" . $v2['title'];
if ($v2['duplicates']){
	echo "<br /><small class='text-muted'>Possible duplicates:</small> ";
//parse dups list	
$alldups = explode(",", $v2['duplicates']);
foreach ($alldups as $dupe){
echo ' <a target="_blank" href="http://vmi.worldcat.org/oclc/' . $dupe . '"><span class="label label-danger">' . $dupe . '</span></a>';
}}
echo "</td>";	
echo "<td>" . $v2['author'] . "</td>";	
echo "<td>" . $v2['copyrightdate'] . "</td>";	
echo "<td>";
if ($v2['language'] <> 'eng' and $v2['language'] <> 'fre' and $v2['language'] <> 'spa' and $v2['language'] <> 'ger'){
echo "<a target='_blank' href='https://www.loc.gov/marc/languages/language_code.html#" . substr($v2['language'],0,1) . "'>". $v2['language'] . "</a>";	
}
else { echo $v2['language']; }
echo "</td>";	
echo "<td>" . $v2['issues'] . "</td>";
echo "<td>" . substr($v2['lastborrowed'],0,4) . "</td>";		
echo "<td><span class='label label-success'>" . $v2['special'] . "</span>";	
echo "</td>";
echo "<td><a target='_blank' href='https://kohastaff.vmi.edu/cgi-bin/koha/catalogue/search.pl?q=" .  $v2['barcode'] . "'><img src='koha.jpg'></a> <a target='_blank' href='http://vmi.worldcat.org/oclc/" . $v2['oclc'] . "'><img src='worldcat.png'></a></td>";	

echo "</tr>";
}

echo "</tbody></table>";


?>


</div>

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
    
    
  </body>
</html>
