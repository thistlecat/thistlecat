
<script type="text/javascript" src="js/jquery.tablesorter.min.js"></script> 



<style type="text/css">
.headerbuttons {
    float: right;
}
.dl-horizontal dt {
    white-space: normal;
}
dl {
	margin-bottom: 0px;
}

</style>
<script type="text/javascript">
    
$("#closerecord").click(function() {
 $("#resultsgohere").animate({
    height: 0,
    opacity: 1
}, 350).empty();

});

</script>

 <div>
            <table class="table table-condensed tablesorter" id="resultstab">

<?php
 


$barcode = $_GET["barcode"];

	
include "includes/dbconnect.php";


$sql0 = 'SELECT title,itemcallnumber,cn_sort,issues,copyrightdate,barcode,oclc,biblionumber,lastborrowed,viva,duplicates FROM ' . THIS_TABLE . ' WHERE barcode = :barcode';

$stmt = $pdo->prepare($sql0);
$stmt->execute( array(':barcode' => $barcode ) );
$searchresults = $stmt->fetch();


echo "<tr class='active'><td><h3>" . $searchresults['title'];
echo "<span class='headerbuttons'><a target='_blank' href='https://kohastaff.vmi.edu/cgi-bin/koha/catalogue/search.pl?q=" .  $searchresults['barcode'] . "'><img src='koha.jpg'></a> <a target='_blank' href='http://vmi.worldcat.org/oclc/" . $searchresults['oclc'] . "'><img src='worldcat.png'></a></span></h3></td></tr>";
echo "<tr ><td> <div class='row'>";
   
      
    
echo '<div class="col-md-6"><dl class="dl-horizontal">';
echo '<dt>Copyright Date:</dt>';
echo '<dd>' . $searchresults['copyrightdate'] . '</dd>';
echo '<dt>Call Number:</dt>';
echo '<dd>' . $searchresults['itemcallnumber'] . '</dd>';
echo '<dt>Total Checkouts:</dt>';
echo '<dd>' . $searchresults['issues'] . '</dd>';
echo '<dt>Last Checkout Date:</dt>';
echo '<dd>' . substr($searchresults['lastborrowed'],0,10) . '</dd>';
echo "</dl>";
echo '</div><div class="col-md-6"><dl class="dl-horizontal">';
echo '<dt>Item Barcode:</dt>';
echo '<dd>' . $searchresults['barcode'] . '</dd>';
echo '<dt>OCLC Number:</dt>';
echo '<dd>' . $searchresults['oclc'] . '</dd>';

if ($searchresults['duplicates']) {
echo '<dt>Possible Duplicates:</dt><dd>'; 
//parse dups list	
$alldups = explode(",", $searchresults['duplicates']);
foreach ($alldups as $dupe){
echo ' <a target="_blank" href="http://vmi.worldcat.org/oclc/' . $dupe . '"><span class="label label-danger">' . $dupe . '</span></a>';
}
echo  '</dd>';
}




echo "</dl></div></td></tr>";

echo "<tr class='bg-success'><td>";
echo '<dl class="dl-horizontal">';
echo '<dt>Special Attributes:</dt>';
echo '<dd>';
if ($searchresults['viva'] == "Yes"){echo ' <span class="label label-success">VIVA Protected Title</span>';}
echo '</dd>';
echo '<dd>';
echo '<span style="float:right;"><button type="button" class="btn btn-danger btn-sm" id="closerecord"><i class="fa fa-times" aria-hidden="true"></i> Close</span>';
echo '</dd>';
echo "</dl>";
echo "</td></tr>";





?>


</table>
    
<!-- <a class="btn btn-warning btn-sm" href="index.php" role="button">&laquo;Go back </a><br /><br /> -->
