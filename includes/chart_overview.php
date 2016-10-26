<?php
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
global $allyears;




?>


<script>

var ctx = document.getElementById("mainchart");
var config = {
    type: 'bar',
   data: {
        labels: [
		
		<?php
		
		$labelstr = '';
		 foreach ($recordresults as $curdate){
			$labelstr .= '"' . $curdate['label'] . '",';	
			};
			rtrim($labelstr, ",");
			echo $labelstr;
?>
		
		
		
		],
		
		
		
		
		
        
       datasets: [{
      
            data: [<?php
		$datastr = '';
		foreach ($recordresults as $thispoint){
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
        enabled: false
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
                autoSkip:true,
				maxTicksLimit: 75
            },
         
			 scaleLabel: {
        display: true,
        labelString: 'LC Class'
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
   animation: {
        onComplete: function () {
            var chartInstance = this.chart;
            var ctx = chartInstance.ctx;
  ctx.textAlign = "center";
        ctx.textBaseline = "bottom";
		            ctx.font = "9px";
            ctx.fillStyle = "#fff";

            Chart.helpers.each(this.data.datasets.forEach(function (dataset, i) {
                var meta = chartInstance.controller.getDatasetMeta(i);
                Chart.helpers.each(meta.data.forEach(function (bar, index) {
                    data = dataset.data[index];
                   
                        ctx.fillText(data, bar._model.x, bar._model.y+15);
                    
                }),this)
            }),this);
        }
    }, 
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
	window.location.href="subclassbreakout.php?lcclass=" + curlabel;
  
   }
  
   
  
	
});



</script>