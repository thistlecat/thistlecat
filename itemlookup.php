
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

$sql0 = 'SELECT title,itemcallnumber,cn_sort,issues,copyrightdate,barcode,oclc,biblionumber,lastborrowed,special,duplicates FROM ' . THIS_TABLE . ' WHERE barcode = :barcode';
$stmt = $pdo->prepare($sql0);
$stmt->execute(array(
    ':barcode' => $barcode
));
$searchresults = $stmt->fetch();


echo "<tr class='active'><td><h3>";

//insert first catalog link, if there is one
if ($catalogs[0]['field'] <> "") {
    $fixedurl = str_replace("MAGICNUMBER", $searchresults[$catalogs[0]['field']], $catalogs[0]['pattern']);
    echo "<a target='_blank' href='" . $fixedurl . "'>";
    echo $searchresults['title'];
    echo "</a>";
    
    //check if there are multiple catalog entries
    if (count($catalogs) > 1) {
        
        //copy original array so we can shift it (remove first element)
        $shiftedarray = $catalogs;
        array_shift($shiftedarray);
        foreach ($shiftedarray as $catval) {
            $fixedurl = str_replace("MAGICNUMBER", $searchresults[$catval['field']], $catval['pattern']);
            echo " <a target='_blank' href='" . $fixedurl . "'>[" . $catval['abbrev'] . "]</a>";
        }
    }
    
} else {
    echo $searchresults['title'];
}

echo "</tr>";
echo "<tr ><td> <div class='row'>";
echo '<div class="col-md-6"><dl class="dl-horizontal">';
echo '<dt>Copyright Date:</dt>';
echo '<dd>' . $searchresults['copyrightdate'] . '</dd>';
echo '<dt>Call Number:</dt>';
echo '<dd>' . $searchresults['itemcallnumber'] . '</dd>';
echo '<dt>Total Checkouts:</dt>';
echo '<dd>' . $searchresults['issues'] . '</dd>';
echo '<dt>Last Checkout Date:</dt>';
echo '<dd>' . substr($searchresults['lastborrowed'], 0, 10) . '</dd>';
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
    foreach ($alldups as $dupe) {
        echo ' <a target="_blank" href="http://vmi.worldcat.org/oclc/' . $dupe . '"><span class="label label-danger">' . $dupe . '</span></a>';
    }
    echo '</dd>';
}

echo "</dl></div></td></tr>";

echo "<tr class='bg-success'><td>";
echo '<dl class="dl-horizontal">';
echo '<dt>Special Attributes:</dt>';
echo '<dd>';
if ($searchresults['special'] <> "") {
    echo ' <span class="label label-success">' . $searchresults['special'] . '</span>';
}
echo '</dd>';
echo '<dd>';
echo '<span style="float:right;"><button type="button" class="btn btn-danger btn-sm" id="closerecord"><i class="fa fa-times" aria-hidden="true"></i> Close</span>';
echo '</dd>';
echo "</dl>";
echo "</td></tr>";
?>
</table>
