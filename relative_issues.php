<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    
 <?php

include "config.php";
include "includes/dbconnect.php";

$sql  = 'SELECT LEFT(itemcallnumber,1) as label, SUM(issues) as checkouts, count(*) as items FROM ' . THIS_TABLE . ' GROUP BY LEFT(itemcallnumber,1)';
$stmt = $pdo->prepare($sql);
$stmt->execute();
$allyears = $stmt->fetchAll();

//calculate ratio of checkouts to total items, and save as chartable value
foreach ($allyears as $key => &$thisval) {
    $thisval['value'] = $thisval['checkouts'] / $thisval['items']; 
}

$jsonresults = json_encode($allyears);

//set page title here
$pagetitle = "Total Checkouts/Total Items";

include "includes/header.php";

?>

<script type="text/javascript">
function getresults(year,series,lcclass){
	$("#itemsgohere").load("results.php?year=" + year + "&series=" + series + "&lcclass=" + lcclass );
}	 
</script>

<style type="text/css">
[class$="y-axis-lines"] path:nth-child(1){
stroke: rgb(0, 0, 0) !important;
stroke-width:2 !important;
}

#lclist li {
    list-style:none;
	margin-left:-20px;
   
}#accordion label{
font-size:0.85em;display: inline !important;
font-weight:normal;
}
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
          <img style="float:left;" src="milkwhite.png" width="50"><a class="navbar-brand" href="index.php">ThistleCAT <?php
echo $libraryname;
?></a>
        </div>
       
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
    
      <form name="filteroptions" method="get" action="overviewbreakout.php">
      
<div id="accordion">
<?php
//generate LC class menu
$sql1 = 'SELECT * FROM LCclasses';

$stmt1 = $pdo->prepare($sql1);
$stmt1->execute();

$recordresults1 = $stmt1->fetchAll();
$prev           = null;
foreach ($recordresults1 as $val) {
    if (strlen($val['class']) == 1) {
        //check if last one was a last child that needs to be closed	
        if (($prev == 'E') or ($prev == 'F')) {
            echo "<div></div>";
        }
        if (strlen($prev) > 1) {
            echo "</div>";
        }
        
        echo '<h3 id="' . $val['class'] . 'class" class="parent"><input id="' . $val['class'] . '" type="radio" name="lcclass" value="' . $val['class'] . '" onclick="this.form.submit();"> <label for="' . $val['class'] . '">' . $val['class'] . ' <small>' . $val['description'] . '</small></label></h3>';
    } else {
        //check if last element was the parent or not
        if (strlen($prev) == 1) {
            echo "<div>";
        }
        
        echo '<input id="' . $val['class'] . '" type="radio" name="lcclass" value="' . $val['class'] . '" onclick="this.form.submit();"> <label for="' . $val['class'] . '"><strong>' . $val['class'] . '</strong> <small>' . $val['description'] . '</small></label><br />';
    }
    
    $prev = $val['class'];
}
?>
</div>
</div>
</form>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <!--     <h1 class="page-header">All Item by Copyright Date</h1> -->
            <h2>Total Checkouts/Total Items</h2>
<div id="chartholder">
<canvas id="mainchart"></canvas>
</div>
<div id="itemsgohere"></div>

This graph illustrates the number of total checkouts in a class divided by the number of items in that classes. In theory, we would prefer all classes to have at least a 1:1 ratio (i.e., every book has checked out at least once). Classes in red illustrate those with a less than 1:1 ratio.

<div id="itemsgohere"></div>

<script>

var ctx = document.getElementById("mainchart");
var config = {
    type: 'bar',
   data: {
        labels: [
		
		<?php
$labelstr = '';
foreach ($allyears as $curdate) {
    $labelstr .= '"' . $curdate['label'] . '",';
}
;
rtrim($labelstr, ",");
echo $labelstr;
?>	
		
		],

       datasets: [{
      
            data: [<?php
$datastr = '';
foreach ($allyears as $thispoint) {
    $datastr .= $thispoint['value'] . ',';
}
;
rtrim($datastr, ",");
echo $datastr;

?>],
            backgroundColor: [],
        
            borderWidth: 0
        }]
    },
    options: {
		  maintainAspectRatio: false,
    responsive: true,
     tooltips: {
        enabled: true,
		callbacks: {
                   
					label: function(tooltipItems, data) { 
					//only show first 2 decimals
					 var abbrevlabel = tooltipItems.yLabel.toString().substr(0, 4);
                        return abbrevlabel;
                    }
					 }
    },

    hover :{
        animationDuration:0
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
        display:false
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


  for (i = 0; i < myChart.data.datasets[0].data.length; i++) {
    if (myChart.data.datasets[0].data[i] < 1) {
         myChart.data.datasets[0].backgroundColor.push("#ff0000");
    } 
	else{
		myChart.data.datasets[0].backgroundColor.push("#0080ff");
	}
		
}

myChart.update();
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
//get position of chosen lc class among accordion headers so we know which one to expand
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
