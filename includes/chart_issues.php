<?php
function display_chart($which) {
global $color1;
global $color2;
global $color3;
global $color4;
global $group1;
global $group2;
global $group3;
global $group4;
global $group0results;
global $group1results;
global $group2results;
global $group3results;
global $alldates;
global $lcclass;
global $thisstart;
global $thisend;



?>

<script>

var ctx = document.getElementById("mainchart");
var config = {
    type: 'bar',
   data: {
        labels: [
		
		<?php
		$labelstr = '';
		 foreach ($alldates as $curdate){
			$labelstr .= '"' . $curdate['label'] . '",';	
			};
			rtrim($labelstr, ",");
			echo $labelstr;
?>
		
		
		
		],
		
		<?php
		
$pointstr0 = "";
$pointstr1 = "";
$pointstr2 = "";
$pointstr3 = "";

		foreach ($group0results as $thispoint){
			if ($thispoint['value'] != ""){
			$pointstr0 .= $thispoint['value'] . ",";	
				
			}
			else{
				$pointstr0 .= "0,";	
			}
		}
		$pointstr0 = substr($pointstr0, 0, -1);

			foreach ($group1results as $thispoint){
			if ($thispoint['value'] != ""){
			$pointstr1 .= $thispoint['value'] . ",";	
				
			}
			else{
				$pointstr1 .= "0,";	
			}
		}
		$pointstr1 = substr($pointstr1, 0, -1);
			foreach ($group2results as $thispoint){
			if ($thispoint['value'] != ""){
			$pointstr2 .= $thispoint['value'] . ",";	
				
			}
			else{
				$pointstr2 .= "0,";	
			}
		}
				$pointstr2 = substr($pointstr2, 0, -1);

		foreach ($group3results as $thispoint){
			if ($thispoint['value'] != ""){
			$pointstr3 .= $thispoint['value'] . ",";	
				
			}
			else{
				$pointstr3 .= "0,";	
			}
		}
		$pointstr3 = substr($pointstr3, 0, -1);
			
		?>
		
		
		
        
        datasets: [{
            data: [<?php echo $pointstr0; ?>],
			label: "0 checkouts",
            backgroundColor: "<?php echo $color1; ?>",
            hoverBackgroundColor: "rgba(50,90,100,1)"
        },{
            data: [<?php echo $pointstr1; ?>],
			label: "1-<?php echo $group1; ?> checkouts",
            backgroundColor: "<?php echo $color2; ?>",
            hoverBackgroundColor: "rgba(140,85,100,1)"
        },{
            data: [<?php echo $pointstr2; ?>],
			label: "<?php echo ($group1+1) . "-" . $group2; ?> checkouts",
            backgroundColor: "<?php echo $color3; ?>",
            hoverBackgroundColor: "rgba(46,185,235,1)"
        },{
            data: [<?php echo $pointstr3; ?>],
			label: "> <?php echo $group2; ?> checkouts",
            backgroundColor: "<?php echo $color4; ?>",
            hoverBackgroundColor: "rgba(0,51,0,1)"
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
                autoSkip:true,
				maxTicksLimit: 75
            },
            stacked: true,
			 scaleLabel: {
        display: true,
        labelString: '<?php echo $which; ?>'
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
	var curlabel = firstPoint._model.label;
	var curvalue = config.data.datasets[firstPoint._datasetIndex].data[firstPoint._index];
	
	if ((window.location.href).indexOf('subclass') > -1) {
	window.location.href="issues.php?lcclass=" + curlabel;
	}
	else if ((window.location.href).indexOf('checkouts') > -1) {
	window.location.href="subclass_issues.php?lcclass=" + curlabel;
	}
	else if ((window.location.href).indexOf('barcodes_issues') > -1) {
	return;
	}
	else if ((window.location.href).indexOf('authors_issues') > -1) {
	getresults(curlabel,curgroup,'<?php echo $thisstart . "|" . $thisend; ?>');
	}
	
	else{
	getresults(curlabel,curgroup,'<?php echo $lcclass; ?>');
	
	}
  
   }
  
});



</script>
       
       
       <?php }
	   
	   ?>