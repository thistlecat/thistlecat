<?php

function display_chart($which) {

global $color1;
global $color2;
global $color3;
global $color4;
global $alldates;
global $group0;
global $group1;
global $group2;
global $group3;
global $group4;
global $groupxresults;
global $group0results;
global $group1results;
global $group2results;
global $group3results;
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
$pointstrx = "";
$pointstr0 = "";
$pointstr1 = "";
$pointstr2 = "";
$pointstr3 = "";



			foreach ($groupxresults as $thispoint){
			if ($thispoint['value'] != ""){
			$pointstrx .= $thispoint['value'] . ",";	
				
			}
			else{
				$pointstrx .= "0,";	
			}
		}
				$pointstrx = substr($pointstrx, 0, -1);

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
            data: [<?php echo $pointstrx; ?>],
			label: "English",
            backgroundColor: "#cccccc",
            hoverBackgroundColor: "rgba(50,90,100,1)"
        },{
            data: [<?php echo $pointstr0; ?>],
			label: "German",
            backgroundColor: "#004000",
            hoverBackgroundColor: "rgba(50,90,100,1)"
        },{
            data: [<?php echo $pointstr1; ?>],
			label: "French",
            backgroundColor: "#0000b3",
            hoverBackgroundColor: "rgba(140,85,100,1)"
        },{
            data: [<?php echo $pointstr2; ?>],
			label: "Spanish",
            backgroundColor: "#660000",
            hoverBackgroundColor: "rgba(46,185,235,1)"
        },{
            data: [<?php echo $pointstr3; ?>],
			label: "Other",
            backgroundColor: "#b8860b",
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
	var curlabel = firstPoint._model.label;
	var curvalue = config.data.datasets[firstPoint._datasetIndex].data[firstPoint._index];
	
	var curlang = firstPoint._model.datasetLabel;
	//translate the langauges
	if (curlang == "English"){
		var curgroup = "eng"
	}
	else if (curlang == "German"){
		var curgroup = "ger"
	}
	else if (curlang == "French"){
		var curgroup = "fre"
	}
	else if (curlang == "Spanish"){
		var curgroup = "spa"
	}
	else {
		var curgroup = "other"
	}
	
	
	if ((window.location.href).indexOf('languages') > -1) {
	window.location.href="subclass_languages.php?lcclass=" + curlabel;
	}
	else if ((window.location.href).indexOf('subclass') > -1) {
	window.location.href="langbreakout.php?lcclass=" + curlabel;
	}
	else if ((window.location.href).indexOf('barcodes_lang') > -1) {
	return;
	}
	else if ((window.location.href).indexOf('authors_lang') > -1) {
	getresults(curlabel,curgroup,'<?php echo $thisstart . "|" . $thisend; ?>');
	}
	else {

	getresults(curlabel,curgroup,'<?php echo $lcclass; ?>');
  
	}
   }
  
   
  
	
});



</script>
       
   
       <?php }
	   
	   ?>