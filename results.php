
<script type="text/javascript">
$(document).ready(function() 
    { 
        $("#resultstab").tablesorter(); 
    } 
); 
</script>

 <link href="css/grid.css" rel="stylesheet">
<style type="text/css">
td img { display: inline-block; }
th { cursor: pointer; }

.table-responsive
{
    overflow-x: auto;
}

</style>
<hr />
  <a download="somedata.csv" href="#" onclick="return ExcellentExport.csv(this, 'resultstab');">
        <button type="button" class="btn btn-success">
        <i class="fa fa-floppy-o" aria-hidden="true"></i> Export Results as CSV
        </button>
        </a>
        <div class="table-responsive">
            <table class="table table-striped table-condensed tablesorter table-bordered" id="resultstab">
              <thead>
                <tr>
                  <th>Call Number</th>
                  <th>Barcode</th>
                  <th class="col-md-3">Title</th>
                  <th>Author</th>
                  <th>Date</th>
                  <th>Language</th>
                  <th>Total Checkouts</th>
                  <th>Last Checkout Date</th>
         
                 <th>Special Attributes</th>
               
                 
                </tr>
              </thead>
              <tbody>

<?php
 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include "config.php";

if (isset($_GET["year"])){
$searchinfo = $_GET["year"];
$searchfield = "copyrightdate";
$searchtextinfo = "Copyright Date: ";
}
elseif (isset($_GET["issues"])){
$searchinfo = $_GET["issues"];
$searchfield = "issues";
$searchtextinfo = "Total Checkouts: ";
$issuessearch = "";
$introtext = $_GET["issues"] . " Checkouts";	
}

$thisseries = $_GET["series"];
$thisclass = $_GET["lcclass"];

//figure out which page we're being loaded on
$pageload = $_SERVER['HTTP_REFERER'];


if (strpos($pageload,"authors_primary.php") > -1){

//get author
parse_str(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY), $queries);

	if ($thisseries == "primary"){
	$issuessearch = "and author LIKE '%" . $queries['author'] . "%'  and copyrightdate = ' " . $searchinfo . "'";
	$introtext = "Primary literature";	
	}
	else{
	$issuessearch = "and author NOT LIKE '%" . $queries['author'] . "%' and copyrightdate = ' " . $searchinfo . "'";	
	$introtext = "Secondary literature";	
	
	}

}


if (strpos($pageload,"authors_lang.php") > -1){
	
	if ($thisseries == "other"){
	$issuessearch = "and language <> 'eng' and language <> 'ger' and language <> 'fre' and language <> 'spa' and language is not null and copyrightdate = ' " . $searchinfo . "'";
	$introtext = "Language is other";	
	}
	else{
	$issuessearch = "and language = '" . $thisseries . "' and copyrightdate = ' " . $searchinfo . "'";	
	$introtext = "Language is " . $thisseries;	
	
	}

}


elseif (strpos($pageload,"authors_region.php") > -1){
	
	$issuessearch = "";	
	$introtext = "Call number range is " . $thisclass;	

}

elseif (strpos($pageload,"authors.php") > -1){
	
	$issuessearch = "and copyrightdate = '" . $searchinfo . "'";	
	$introtext = "";	

}

elseif (strpos($pageload,"authors_issues.php") > -1){

//figure out the issues range
if ($thisseries == "group0"){
$issuessearch = "and issues = 0 and copyrightdate = ' " . $searchinfo . "'";
$introtext = "0 Checkouts";	
}
elseif ($thisseries == "group1"){
$issuessearch = "and issues > 0 and issues <= 5 and copyrightdate = ' " . $searchinfo . "'";
$introtext = "1-5 Checkouts";	

}
elseif ($thisseries == "group2"){
$issuessearch = "and issues > 5 and issues <= 10 and copyrightdate = ' " . $searchinfo . "'";	
$introtext = "6-10 Checkouts";	
}
elseif ($thisseries == "group3"){
$issuessearch = "and issues > 10 and copyrightdate = ' " . $searchinfo . "'";
$introtext = ">10 Checkouts";		
}

}

elseif (strpos($pageload,"authors_lastborrowed.php") > -1){
//figure out the checkout date range
if ($thisseries == "group0"){
$issuessearch = "and lastborrowed is null and copyrightdate = ' " . $searchinfo . "'";
$introtext = "Last checked out before " . $last1 . "/Never checked out";	
}
elseif ($thisseries == "group1"){
$issuessearch = "and LEFT(lastborrowed,4) >= " . $last1 . " and LEFT(lastborrowed,4) <= "  . $last2 . " and copyrightdate = ' " . $searchinfo . "'";	
$introtext = "Last checked out between "  . $last1 . "-" . $last2;	

}
elseif ($thisseries == "group2"){
$issuessearch = "and LEFT(lastborrowed,4) > " . $last2 . " and copyrightdate = ' " . $searchinfo . "'";
$introtext = "Last checked out since " . $last2;	
}

}


elseif ((strpos($pageload,"issues.php") > -1)|| (strpos($pageload,"issueschart.php") > -1)){

//figure out the issues range
if ($thisseries == "group0"){
$issuessearch = "and issues = 0";
$introtext = "0 Checkouts";	
}
elseif ($thisseries == "group1"){
$issuessearch = "and issues > 0 and issues <= ". $circ1;	
$introtext = "1-" . $circ1 . " Checkouts";	

}
elseif ($thisseries == "group2"){
$issuessearch = "and issues > " . $circ1 . " and issues <= " . $circ2;	
$introtext = $circ1 . "-" . $circ2 . " Checkouts";	
}
elseif ($thisseries == "group3"){
$issuessearch = "and issues > " . $circ2;
$introtext = ">" . $circ2 ." Checkouts";		
}

}




elseif (strpos($pageload,"lastborrowedbreakout.php") > -1){
//figure out the checkout date range
if ($thisseries == "group0"){
$issuessearch = "and lastborrowed is null";
$introtext = "Last checked out before " . $last1 . "/Never checked out";	
}
elseif ($thisseries == "group1"){
$issuessearch = "and LEFT(lastborrowed,4) >= " . $last1 . " and LEFT(lastborrowed,4) <= "  . $last2;	
$introtext = "Last checked out between "  . $last1 . "-" . $last2;	

}
elseif ($thisseries == "group2"){
$issuessearch = "and LEFT(lastborrowed,4) > " . $last2;
$introtext = "Last checked out since " . $last2;	
}

}

elseif (strpos($pageload,"langbreakout.php") > -1){
//figure out the checkout date range
if ($thisseries == "other"){
$issuessearch = "and language <> 'eng' and language <> 'ger' and language <> 'fre' and language <> 'spa' and language is not null";
$introtext = "Language is other";	
}
else{
$issuessearch = "and language = '" . $thisseries . "' ";	
$introtext = "Language is " . $thisseries;	

}


}



elseif (strpos($pageload,"languagesbreakout.php") > -1){
$issuessearch = "and language = '" . $thisseries . "'";
$introtext = "All Items";
$searchinfo = $thisseries;
$searchtextinfo = "Language: ";	
}


elseif (strpos($pageload,"overviewbreakout.php") > -1){
$issuessearch = "";
$introtext = "All Items";	
}




	
include "includes/dbconnect.php";


if (strlen($thisclass) > 1 and ($thisclass[1] == "0")){
$thisclass = $thisclass[0] . "[0-9]";
}


if (strpos($pageload,"authors") > -1){
	$thissearch = explode("|", $thisclass);	
	//if it's not a range
	if ($thissearch[1] == ''){	
	$thissearch = "(cn_sort REGEXP '^" . $thissearch[0] . "')";	
	}

	//if it is a range
	else {
	$thissearch = "(cn_sort REGEXP '^" . $thissearch[0] . "' OR cn_sort REGEXP '^" . $thissearch[1] . "' OR (cn_sort >= '" . $thissearch[0] . "' AND cn_sort <= '" . $thissearch[1] . "'))";
	}
	
$sql0 = 'SELECT title,author,itemcallnumber,cn_sort,issues,copyrightdate,barcode,oclc,biblionumber,lastborrowed,special,duplicates,language FROM ' . THIS_TABLE . ' WHERE ' . $thissearch . ' ' . $issuessearch . ' ORDER BY cn_sort ASC';
$stmt = $pdo->prepare($sql0);
$stmt->execute( );

}

else if (strpos($pageload,"languagesbreakout.php") > -1){
	
$sql0 = 'SELECT title,author,itemcallnumber,cn_sort,issues,copyrightdate,barcode,oclc,biblionumber,lastborrowed,special,duplicates,language FROM ' . THIS_TABLE . ' WHERE itemcallnumber REGEXP  :lcclass ' . $issuessearch . ' ORDER BY cn_sort ASC';
$stmt = $pdo->prepare($sql0);
$stmt->execute( array(':lcclass' => "^" . $thisclass ) );


}
else {

$sql0 = 'SELECT title,author,itemcallnumber,cn_sort,issues,copyrightdate,barcode,oclc,biblionumber,lastborrowed,special,duplicates,language FROM ' . THIS_TABLE . ' WHERE itemcallnumber REGEXP  :lcclass and ' . $searchfield . ' = :cdate  ' . $issuessearch . ' ORDER BY cn_sort ASC';
$stmt = $pdo->prepare($sql0);
$stmt->execute( array(':lcclass' => "^" . $thisclass,':cdate' => $searchinfo ) );

}

$searchresults = $stmt->fetchAll();

echo '<div class="container"><div class="row text-center"><strong>' . count($searchresults) . " Items</strong></div><div class='row'>" ;
echo'<div class="col-md-4">Call Numbers Beginning with: ' . $thisclass . '</div>';
echo'<div class="col-md-4">' . $introtext . '</div>';
echo'<div class="col-md-4">' . $searchtextinfo . $searchinfo . '</div>';
echo '</div></div>';

foreach ($searchresults as $v2){
echo "<tr>";
echo "<td>" . $v2['itemcallnumber'] . "</td>";	
echo "<td>" . $v2['barcode'] . "</td>";	
echo "<td  class='col-md-3'>";

//insert first catalog link, if there is one
if ($catalogs[0]['field'] <> "") {
$fixedurl = str_replace("MAGICNUMBER", $v2[$catalogs[0]['field']], $catalogs[0]['pattern']);		
echo "<a target='_blank' href='" . $fixedurl . "'>";
echo $v2['title'];
echo "</a>";

//check if there are multiple catalog entries
if (count($catalogs) > 1){
	
	    //copy original array so we can shift it (remove first element)
		$shiftedarray = $catalogs;
		array_shift($shiftedarray);
		foreach ($shiftedarray as $catval){
			
		
		$fixedurl = str_replace("MAGICNUMBER", $v2[$catval['field']], $catval['pattern']);		
		echo " <a target='_blank' href='" . $fixedurl . "'>[" . $catval['abbrev'] . "]</a>";
		}
}

}
else{
echo $v2['title'];
	
	
}



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



echo "</tr>";
}



?>



    
 
                
              </tbody>
            </table>
            </div>