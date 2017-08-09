 <?php



	
	if (isset($_GET['region'])){ $thisregion = $_GET['region']; }
	else {	$thisregion = "";}
	
	
	

include "includes/dbconnect.php";


$authorarray = array();



$sql0 = 'SELECT authorname, cnstart, cnend, totals,originalworks FROM ' . AUTHOR_TABLE . ' WHERE cnstart REGEXP "^' . $thisregion . '" ORDER BY totals DESC';
$stmt = $pdo->prepare($sql0);
$stmt->execute( );
$allauthors = $stmt->fetchAll();

$authorsonly = array();

$authorarray = array();
$originalworks = array();
$crit = array();

foreach ($allauthors as $vauth){
	
$authorarray[] = array(
    "label" => $vauth['authorname'],
   "value" => $vauth['totals'],
);

//make array of authors only
$authorsonly[] = array(
    "label" => $vauth['authorname']
);

//make array of criticism (subtract original works from totals); round negatives to zero
$critonly =  max($vauth['totals'] - $vauth['originalworks'],0);
if ($critonly == 0){
	$crit[] = array(
    "value" => ""
);

	
}
else {
$crit[] = array(
    "value" => $critonly,
	"link" => "authors.php?author=" .$vauth['authorname']

);
}

//make array of originalworks 
$originalworks[] = array(
     "value" => $vauth['originalworks'],
	 "link" => "authors.php?author=" .$vauth['authorname']
);

	
}

//insert link in each one to make  chart clickable
foreach  ($authorarray as $k4 => $v4){
	if ($v4['value'] <> ""){
		//insert correspreond lc class to make link
		$authorarray[$k4]['link'] = "authors.php?author=" .$v4['label'];
	}
}




//make json array for whichever data we're charting
$jsonresults = json_encode($authorarray);

$jsonauthors = json_encode($authorsonly);
$jsoncrit = json_encode($crit);
$jsonorig = json_encode($originalworks);


//}




$sql1 = 'SELECT topic, cnstart, cnend, totals FROM littopics WHERE cnstart REGEXP "^' . $thisregion . '" ORDER BY cnstart ASC';
$stmt1 = $pdo->prepare($sql1);
$stmt1->execute( );
$alltopics = $stmt1->fetchAll();

foreach ($alltopics as $vauth1){
	
$topicsarray[] = array(
    "label" => $vauth1['topic'],
   "value" => $vauth1['totals'],
   "link" => "JavaScript:getresults('','','" . urlencode($vauth1['cnstart']) . "|" . $vauth1['cnend'] . "')",
   "tooltext" =>$vauth1['cnstart'] . "|" . $vauth1['cnend']
);
	
}



//make json array for data we're charting
$jsontopics = json_encode($topicsarray);

//set page title here
$pagetitle = "Literature Module";



include "includes/header.php";

$charttype = 'stackedbar2D';
$chartsettings =  '"xAxisName": "Author","baseFontSize": "10","showValues": "0","yAxisName": "Number of Items","showSum": "1","paletteColors": "#88A65E,#BFB35A",';
$chartheight = '2000';


?>

    

<script type="text/javascript">

function getresults(year,series,lcclass){
	//check if second character is lowercase d - if so, it's a first letter only class (B0)

	$("#itemsgohere").load("results.php?year=" + year + "&series=" + series + "&lcclass=" + lcclass );
	
}

$(document).ready(function(e) {

//enable table sorter
$("#resultstab").tablesorter(); 


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
        
         
          <a class="navbar-brand" href="authorsindex.php"><i class="fa fa-caret-right" aria-hidden="true"></i> Literature Module</a>
       
      </div>
    </nav>

    <div class="container-fluid">

      
    <!--     <h1 class="page-header">All Item by Copyright Date</h1> -->
   
           <h2>
           <?php
		   if ($thisregion == 'PR'){$thistop = "English literature";}
		   else if ($thisregion == 'PS'){$thistop = "American literature";}
	else {$thistop = "Overview";}
	echo $thistop;
		   
		   ?>
           
           </h2>        
<div class="row">
<div class="col-md-12">


<div id="chartContainer1">
<canvas id="mainchart1"></canvas>
</div>


<script>

<?php
		
		//save call number ranges into JS array
		
		
		$labelstr = '';
		$callnostr = '';
		 foreach ($topicsarray as $curdate){
			$labelstr .= '"' . $curdate['label'] . '",';	
			$callnostr .=  '"' . $curdate['label'] . '": "' . $curdate['tooltext'] . '",'; 
			};
			$labelstr = substr($labelstr, 0, -1);
			$callnostr = substr($callnostr, 0, -1);
			
?>
var callnoarray = {<?php echo $callnostr; ?>};


var ctx = document.getElementById("mainchart1");
var config = {
    type: 'bar',
   data: {
        labels: [<?php echo $labelstr; ?>],
       datasets: [{
    
            data: [<?php
		$datastr = '';
		foreach ($topicsarray as $thispoint){
			$datastr .= $thispoint['value'] . ',';	
			};
			rtrim($datastr, ",");
			echo $datastr;
			
		?>],
            backgroundColor: '#0080ff',
        
            borderWidth: 0
			
			
        }]
    },
    options: {
		  maintainAspectRatio: false,
    responsive: true,
    tooltips: {
        enabled: true,
		callbacks: {
                    beforeLabel: function(tooltipItems, data) { 
					   var callnorange = callnoarray[tooltipItems.xLabel];
                        return   callnorange.replace('|', '-');
                    },
					label: function(tooltipItems, data) { 
					 
                        return tooltipItems.yLabel + ' items';
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
                fontSize:9,
				 autoSkip:false,
                maxTicksLimit:20
            },
           
			
            gridLines: {
				             display:false,
                color: "#fff",
                zeroLineColor: "#fff",
                zeroLineWidth: 0
            }, 
			
         
			 scaleLabel: {
        display: true,
        labelString: 'Topic'
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

   
    pointLabelFontFamily : "Quadon Extra Bold",
    scaleFontFamily : "Quadon Extra Bold",
}
};
var myChart = new Chart(ctx,config );



</script>

</div>
</div>
    
    <hr />
        <h3>Top Authors</h3>


<div class="row">
    <div class="col-md-2">
    
    	
            
             <div class="table-responsive">
                        <table class="table-hover table-striped table-condensed tablesorter table-bordered table" id="resultstab">
                          <thead>
                            <tr>
                              <th>Author</th>
                              <th>Totals</th>
                            </tr>
                          </thead>
                          <tbody>
            <?php
            foreach ($authorarray as $va){
            echo "<tr><td><small><a href='authors.php?author=" . $va['label'] . "'>" . $va['label'] . "</a></small></td><td><small>" . 	$va['value'] . "</small></td></tr>";
                
            }
            
            ?>
            
            </tbody>
            </table>
            </div>

    </div>

    
    <div class="col-md-10">
 
   <style>
   #chartholderauthors{
width: 100%;
height:1500px;	
}
</style> 
<div id="itemsgohere">

<div id="chartholderauthors">
<canvas id="mainchart"></canvas>
</div>
</div>


<script>

var ctx = document.getElementById("mainchart");
var config1 = {
    type: 'horizontalBar',
   data: {
        labels: [
		
		<?php


		$labelstr = '';
		 foreach ($authorsonly as $curdate){
			$labelstr .= '"' . $curdate['label'] . '",';	
			};
			rtrim($labelstr, ",");
			echo $labelstr;
?>
		
		
		
		],
		
		<?php
		
$pointstr0 = "";
$pointstr1 = "";


		foreach ($originalworks as $thispoint){
			if ($thispoint['value'] != ""){
			$pointstr0 .= $thispoint['value'] . ",";	
				
			}
			else{
				$pointstr0 .= "0,";	
			}
		}
		$pointstr0 = substr($pointstr0, 0, -1);

			foreach ($crit as $thispoint){
			if ($thispoint['value'] != ""){
			$pointstr1 .= $thispoint['value'] . ",";	
				
			}
			else{
				$pointstr1 .= "0,";	
			}
		}
		$pointstr1 = substr($pointstr1, 0, -1);
		
			
		?>
		
		
		
        
        datasets: [{
            data: [<?php echo $pointstr0; ?>],
			label: "Primary",
            backgroundColor: "<?php echo $color4; ?>",
            hoverBackgroundColor: "rgba(50,90,100,1)"
        },{
            data: [<?php echo $pointstr1; ?>],
			label: "Secondary",
            backgroundColor: "<?php echo $color1; ?>",
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
		mode: 'single',
		onHover: function() { $(this).css('cursor','pointer'); }
		
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
				            
            }, 
			 ticks: {
                autoSkip:true
            },
			position: "top",
            stacked: true,
			 scaleLabel: {
        display: true,
        labelString: 'Author'
      }
        }],
        yAxes: [{
            gridLines: {
    display:false,
                color: "#fff",
                zeroLineColor: "#fff",
                zeroLineWidth: 0
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
var myChart1 = new Chart(ctx,config1 );

$("#mainchart").click(function(e) {
   var activeBars = myChart1.getElementAtEvent(e); 
   if(activeBars.length > 0){   
    var firstPoint = activeBars[0];
	var curlabel = firstPoint._model.label;
	window.location.href="authors.php?author=" + curlabel;
   }
   
  
});



 $("#mainchart1").click(function(e) {
   var activeBars = myChart.getElementAtEvent(e); 
   if(activeBars.length > 0){   
    var firstPoint = activeBars[0];
	var curlabel = firstPoint._model.label;
    getresults('','',callnoarray[curlabel]);
   }
  });


</script>

    </div>
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
