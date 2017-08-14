 <?php

if (isset($_GET['author'])) {
    $thisauthor = $_GET['author'];
}
//if no author selected, redirect to main page
else {
    header("Location: authorsindex.php");
    exit();
}


include "config.php";
include "includes/dbconnect.php";


//get author call number range
$sql0 = 'SELECT authorname, cnstart, cnend FROM ' . AUTHOR_TABLE . ' WHERE authorname = :authorname';
$stmt = $pdo->prepare($sql0);
$stmt->execute(array(
    ':authorname' => $thisauthor
));
$authorinfo = $stmt->fetch();

//make sure we got a result
if ($authorinfo === false) {
    echo "No results.";
    exit();
}

$thisstart  = $authorinfo['cnstart'];
$thisend    = $authorinfo['cnend'];
$thisregion = substr($thisstart, 0, 2);

//if it's not a range
if ($thisend == '') {
    $sqlstr = "(cn_sort REGEXP '^" . $thisstart . "')";
}

//if it is a range
else {
    $sqlstr = "(cn_sort REGEXP '^" . $thisstart . "' OR cn_sort REGEXP '^" . $thisend . "' OR (cn_sort >= '" . $thisstart . "' AND cn_sort <= '" . $thisend . "'))";
}

//get all dates in query
$sql0 = 'SELECT copyrightdate as label, count(*) as value FROM ' . THIS_TABLE . ' WHERE ' . $sqlstr . ' GROUP BY copyrightdate';
$stmt = $pdo->prepare($sql0);
$stmt->execute();
$alldates = $stmt->fetchAll();

//make array of dates only
$datesonly = array();
foreach ($alldates as $thisv) {
    $datesonly[] = $thisv['label'];
}

//get earliest date in array
asort($datesonly);

//make array of desired range (earliest book through present year)
$allyears = array();

//check if first value is zero, if so, start with the next number, but manually insert it at the beginning (we don't want the range calcuated from 0-2016)
if ($datesonly[0] == '0') {
    $startingval = $datesonly[1];
    $allyears[]  = '0';
}

else {
    $startingval = $datesonly[0];
}

foreach (range($startingval, date("Y")) as $number1) {
    
    
    $allyears[] = $number1;
    
    if (!in_array($number1, $datesonly)) {
        $alldates[] = array(
            "label" => $number1,
            "value" => '0'
        );
        
    }
    
}

//sort the new array with the empty years
// Obtain a list of columns
foreach ($alldates as $key => $row) {
    $label[$key] = $row['label'];
    $value[$key] = $row['value'];
}
// Sort the data with volume descending, edition ascending
// Add $data as the last parameter, to sort by the common key
array_multisort($label, SORT_ASC, $alldates);


//get primary
$sql  = 'SELECT copyrightdate as label,  count(*) as value FROM ' . THIS_TABLE . ' WHERE ' . $sqlstr . ' and author LIKE :authorname GROUP BY copyrightdate';
$stmt = $pdo->prepare($sql);
$stmt->execute(array(
    ':authorname' => "%" . $thisauthor . "%"
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

//try insert link in each one to make chart clickable
foreach ($groupxresults as $k4 => $v4) {
    if ($v4['value'] <> "") {
        $groupxresults[$k4]['link'] = "JavaScript:getresults('" . $k4 . "','primary','" . urlencode($thisstart) . "|" . $thisend . "')";
    }
}

//end getting primary

//get secondary
$sql  = 'SELECT copyrightdate as label,  count(*) as value FROM ' . THIS_TABLE . ' WHERE ' . $sqlstr . ' and author NOT LIKE :authorname GROUP BY copyrightdate';
$stmt = $pdo->prepare($sql);
$stmt->execute(array(
    ':authorname' => "%" . $thisauthor . "%"
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

//try insert link in each one to make chart clickable
foreach ($group0results as $k4 => $v4) {
    if ($v4['value'] <> "") {
        $group0results[$k4]['link'] = "JavaScript:getresults('" . $k4 . "','secondary','" . $thisstart . "|" . $thisend . "')";
    }
}

//end getting group 0




$jsoncategories = json_encode($alldates);
$jsongroup0     = json_encode(array_values($group0results));
$jsongroupx     = json_encode(array_values($groupxresults));



//get all possible authors for left menu
$sqlx = 'SELECT authorname, cnstart, cnend, totals FROM ' . AUTHOR_TABLE . ' WHERE cnstart REGEXP "^' . $thisregion . '" ORDER BY totals DESC';
$stmt = $pdo->prepare($sqlx);
$stmt->execute();
$allauthors = $stmt->fetchAll();

$authorsonly = array();

$authorarray   = array();
$originalworks = array();
$crit          = array();

foreach ($allauthors as $vauth) {
    $authorarray[] = array(
        "label" => $vauth['authorname'],
        "value" => $vauth['totals']
    );
}

//now get full item list
$sql5  = 'SELECT title,author,itemcallnumber,cn_sort,issues,copyrightdate,barcode,oclc,biblionumber,lastborrowed,special,duplicates,language FROM ' . THIS_TABLE . ' WHERE ' . $sqlstr . ' ORDER BY cn_sort ASC';
$stmt5 = $pdo->prepare($sql5);
$stmt5->execute();
$allresultsitems = $stmt5->fetchAll();

//set page title here
$pagetitle = "Literature Module";

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


//highlight selected author in left menu
var url = window.location.href;
var matchvar = url.match(/\?author=[A-Z]/gi);
if(url.indexOf(matchvar) != -1){
	var tableRow = $("#resultstab tr td").filter(function() {
    return $(this).text() == "<?php
echo $thisauthor;
?>";
		}).parent('tr').addClass('table-success');

}
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
  <?php
include "includes/navbar_authors.php";
?>


    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
    
     
   
<h4>Top Authors in <?php echo $thisregion; ?></h4>


 <div class="table-responsive">
            <table class="table-hover table-striped table-condensed tablesorter table-bordered table" id="resultstab">
              <thead>
                <tr>
                  <th>Author</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
<?php
foreach ($authorarray as $va) {
    echo "<tr><td><small><a href='" . $_SERVER['PHP_SELF'] . "?author=" . $va['label'] . "'>" . $va['label'] . "</a></small></td><td><small>" . $va['value'] . "</small></td></tr>";
    
}

?>

</tbody>
</table>
</div>



</div>

        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <!--     <h1 class="page-header">All Item by Copyright Date</h1> -->
   
           <h2><?php
echo $thisauthor . " " . count($allresultsitems) . " items";


?></h2>  
           
            <span class="label label-success label-sm">Current Layer: Primary vs. Secondary</span>      

         <div id="chartholder">
<canvas id="mainchart"></canvas>
</div>

<div id="itemsgohere">
 
<?php
//only display table if we're on an author page
if ($thisauthor <> "") {
    
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
                 
                
                </tr>
              </thead>
              <tbody>';
    
    foreach ($allresultsitems as $v2) {
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
            if (count($catalogs) > 1) {
                
                //copy original array so we can shift it (remove first element)
                $shiftedarray = $catalogs;
                array_shift($shiftedarray);
                foreach ($shiftedarray as $catval) {
                    
                    
                    $fixedurl = str_replace("MAGICNUMBER", $v2[$catval['field']], $catval['pattern']);
                    echo " <a target='_blank' href='" . $fixedurl . "'>[" . $catval['abbrev'] . "]</a>";
                }
            }
            
        } else {
            echo $v2['title'];
            
            
        }
        
        if ($v2['duplicates']) {
            echo "<br /><small class='text-muted'>Possible duplicates:</small> ";
            //parse dups list	
            $alldups = explode(",", $v2['duplicates']);
            foreach ($alldups as $dupe) {
                echo ' <a target="_blank" href="http://vmi.worldcat.org/oclc/' . $dupe . '"><span class="label label-danger">' . $dupe . '</span></a>';
            }
        }
        echo "</td>";
        echo "<td>" . $v2['author'] . "</td>";
        echo "<td>" . $v2['copyrightdate'] . "</td>";
        echo "<td>";
        if ($v2['language'] <> 'eng' and $v2['language'] <> 'fre' and $v2['language'] <> 'spa' and $v2['language'] <> 'ger') {
            echo "<a target='_blank' href='https://www.loc.gov/marc/languages/language_code.html#" . substr($v2['language'], 0, 1) . "'>" . $v2['language'] . "</a>";
        } else {
            echo $v2['language'];
        }
        echo "</td>";
        echo "<td>" . $v2['issues'] . "</td>";
        echo "<td>" . substr($v2['lastborrowed'], 0, 4) . "</td>";
        echo "<td><span class='label label-success'>" . $v2['special'] . "</span>";
        echo "</td>";
        echo "</tr>";
    }
    
    echo "</tbody></table>";
    
}
?>


</div>


<script>

var ctx = document.getElementById("mainchart");
var config = {
    type: 'bar',
   data: {
        labels: [
		
		<?php
$labelstr = '';
foreach ($alldates as $curdate) {
    $labelstr .= '"' . $curdate['label'] . '",';
}
;
rtrim($labelstr, ",");
echo $labelstr;
?>
		
		
		
		],
		
		<?php

$pointstr0 = "";
$pointstrx = "";

foreach ($groupxresults as $thispoint) {
    if ($thispoint['value'] != "") {
        $pointstrx .= $thispoint['value'] . ",";
        
    } else {
        $pointstrx .= "0,";
    }
}
$pointstrx = substr($pointstrx, 0, -1);

foreach ($group0results as $thispoint) {
    if ($thispoint['value'] != "") {
        $pointstr0 .= $thispoint['value'] . ",";
        
    } else {
        $pointstr0 .= "0,";
    }
}
$pointstr0 = substr($pointstr0, 0, -1);



?>
		
		
		
        
        datasets: [{
            data: [<?php
echo $pointstrx;
?>],
			label: "Primary",
              backgroundColor: "<?php
echo $color4;
?>",
            hoverBackgroundColor: "rgba(50,90,100,1)"
        },{
            data: [<?php
echo $pointstr0;
?>],
			label: "Secondary",
            backgroundColor: "<?php
echo $color1;
?>",
            hoverBackgroundColor: "rgba(140,85,100,1)"
        }]
    },
    options: {
		  maintainAspectRatio: false,
    responsive: true,
    tooltips: {
        enabled: true
    },
	 tooltipTemplate: "Tooltip",
    hover :{
       mode: 'single'
    },
    scales: {
        xAxes: [{
            ticks: {
                beginAtZero:false,
                fontFamily: "'Open Sans Bold', sans-serif",
                fontSize:11
            },
            scaleLabel:{
                display:false
            },
            gridLines: {
				             display:false,
                color: "#fff",
                zeroLineColor: "#fff",
                zeroLineWidth: 0
            }, 
			 ticks: {
                autoSkip:true
            },
            stacked: true,
			 scaleLabel: {
        display: true,
        labelString: 'Copyright Date'
      }
        }],
        yAxes: [{
            gridLines: {
   
            },
            ticks: {
                fontFamily: "'Open Sans Bold', sans-serif",
                fontSize:11
            },
            stacked: true,
			 scaleLabel: {
        display: true,
        labelString: 'Number of Items'
      }
        }]
    },
	
    legend:{
        display:true
    },

    //show values on chart
  /*  animation: {
        onComplete: function () {
            var chartInstance = this.chart;
            var ctx = chartInstance.ctx;
            ctx.textAlign = "left";
            ctx.font = "9px Open Sans";
            ctx.fillStyle = "#fff";

            Chart.helpers.each(this.data.datasets.forEach(function (dataset, i) {
                var meta = chartInstance.controller.getDatasetMeta(i);
                Chart.helpers.each(meta.data.forEach(function (bar, index) {
                    data = dataset.data[index];
                    if(i==0){
                        ctx.fillText(data, 50, bar._model.y+4);
                    } else {
                        ctx.fillText(data, bar._model.x-25, bar._model.y+4);
                    }
                }),this)
            }),this);
        }
    }, */
    pointLabelFontFamily : "Quadon Extra Bold",
    scaleFontFamily : "Quadon Extra Bold",
}
};
var myChart = new Chart(ctx,config );
$("#mainchart").click(function(e) {
   var activeBars = myChart.getElementAtEvent(e); 
   if(activeBars.length > 0){   
    var firstPoint = activeBars[0];
	
	var curgroup = "group" + firstPoint._datasetIndex;
	
	if (curgroup== "group0") {curgroup = "primary";}
	else {curgroup = "secondary";}
	
	var curlabel = firstPoint._model.label;
	
	
	var curvalue = config.data.datasets[firstPoint._datasetIndex].data[firstPoint._index];

	getresults(curlabel,curgroup,'<?php
echo $thisstart . "|" . $thisend;
?>');
	
  
   }
  
});



</script>
       
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
	
	
		// get current URL path and assign 'active' class 			
	var pagename = decodeURI(window.location.href.split("/").slice(-1)); 		
	$('#navbar a[href="'+pagename+'"]').parent().addClass('active'); 
	
	
//get position of chosen lc class among accordion headers so we know which one to expand
   var curclass=	$("#<?php echo substr($thisauthor, 0, 1); ?>class").parent().children('h3').index($('#<?php echo substr($thisauthor, 0, 1); ?>class'));
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
