 <?php





	if (isset($_GET['lcclass'])){ $lcclass = $_GET['lcclass']; }
	else {	$lcclass = "";}
	

	
	
include "includes/dbconnect.php";



if ($lcclass == ''){
$sql = 'SELECT language as label, count(*) as value FROM ' . THIS_TABLE . ' WHERE language is not null and language <> "eng" GROUP BY language';

}
else
{
$sql = 'SELECT language as label, count(*) as value FROM ' . THIS_TABLE . ' WHERE language is not null and language <> "eng" and itemcallnumber LIKE :lcclass GROUP BY language';
}

$stmt = $pdo->prepare($sql);
$stmt->execute( array(':lcclass' => $lcclass . "%") );
$allyears = $stmt->fetchAll();




$jsonresults = json_encode($allyears);



//set page title here
$pagetitle = "All Foreign Language Items by Language";



include "includes/header.php";



?>

<script type="text/javascript">

function getresults(series,lcclass){
	$("#itemsgohere").load("results.php?series=" + series + "&lcclass=" + lcclass );
	
}

$(document).ready(function(e) {

var url = window.location.href;
var matchvar = url.match(/\?lcclass=[A-Z]/gi);
if(url.indexOf(matchvar) != -1){
     $('input:radio[id="<?php echo $lcclass; ?>"]').prop('checked', true);
}
});




		 
		 
</script>



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
         <div id="navbar" class="navbar-collapse collapse">
                   
          <ul class="nav navbar-nav navbar-left">
          <a class="navbar-brand" href="languages.php"><i class="fa fa-caret-right" aria-hidden="true"></i> Foreign Language Module</a>

           
          </ul>
        </div>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
    
      <form name="filteroptions" method="get" action="languagesbreakout.php">
      
<div id="accordion">
<?php include "includes/classmenu.php";?>

</div>
</div>
</form>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                 <?php include "includes/collapseleft.php" ?>  
   
    
   <?php
   if ($lcclass == "") {$lcdisplay = '<h2>All Foreign Language Items by <a href="languages.php" class="btn btn-secondary btn-lg active" role="button" aria-pressed="true">LC Class</a> <a href="" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">By Language</a></h2>';}
   elseif((strlen($lcclass) > 1) and ($lcclass[1] == '[')) {$lcdisplay = '<h2>All Foreign Language Items <a href="" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">LC Class: ' . $lcclass[0] . "1 - " . $lcclass[0] . "9999</a></h2>";  }
   else  {$lcdisplay = '<h2>All Foreign Language Items <a href="" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">LC Class: ' . $lcclass . '</a></h2>';}
   ?> 
                        <h3><?php echo $lcdisplay; ?></h3>    
            
<div id="chartholder">
<canvas id="mainchart"></canvas>
</div>
<div id="itemsgohere"></div>


<?php include "includes/chart_general.php"; 
display_chart("Language");

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
    
   <script type="text/javascript">

$(document).ready(function(e) {
//get position of chosen lc class among accordion headers so we know which one to expand
   var curclass=	$("#<?php echo substr($lcclass,0,1) ?>class").parent().children('h3').index($('#<?php echo substr($lcclass,0,1) ?>class'));
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
