 <?php



include "config.php";
include "includes/dbconnect.php";



//get all possible authors for master list
$authorarray = array();
$sql0 = 'SELECT authorname, cnstart, cnend, totals FROM ' . AUTHOR_TABLE . ' ORDER BY totals DESC';
$stmt = $pdo->prepare($sql0);
$stmt->execute( );
$allauthors = $stmt->fetchAll();

foreach ($allauthors as $vauth){
$authorarray[] = array(
    "label" => $vauth['authorname'],
   "value" => $vauth['totals'],
  );
	
 }



//get stats for each region
$sqlPR = 'SELECT issues FROM allitems WHERE cn_sort REGEXP "^PR"';
$stmt = $pdo->prepare($sqlPR);
$stmt->execute( );
$totalPR = $stmt->rowCount();

$sqlPR = 'SELECT issues FROM allitems WHERE cn_sort REGEXP "^PR" and issues = 0';
$stmt = $pdo->prepare($sqlPR);
$stmt->execute( );
$noissuesPR = $stmt->rowCount();

$sqlPR1 = 'SELECT issues FROM allitems WHERE cn_sort REGEXP "^PR" and issues > 0 and issues < 6';
$stmt = $pdo->prepare($sqlPR1);
$stmt->execute( );
$issues15PR = $stmt->rowCount();

$sqlPR2 = 'SELECT issues FROM allitems WHERE cn_sort REGEXP "^PR" and issues > 5 and issues < 11';
$stmt = $pdo->prepare($sqlPR2);
$stmt->execute( );
$issues610PR = $stmt->rowCount();

$sqlPR3 = 'SELECT issues FROM allitems WHERE cn_sort REGEXP "^PR" and issues > 10';
$stmt = $pdo->prepare($sqlPR3);
$stmt->execute( );
$issues10PR = $stmt->rowCount();


//get stats for each region
$sqlPS = 'SELECT issues FROM allitems WHERE cn_sort REGEXP "^PS"';
$stmt = $pdo->prepare($sqlPS);
$stmt->execute( );
$totalPS = $stmt->rowCount();

$sqlPS = 'SELECT issues FROM allitems WHERE cn_sort REGEXP "^PS" and issues = 0';
$stmt = $pdo->prepare($sqlPS);
$stmt->execute( );
$noissuesPS = $stmt->rowCount();

$sqlPS1 = 'SELECT issues FROM allitems WHERE cn_sort REGEXP "^PS" and issues > 0 and issues < 6';
$stmt = $pdo->prepare($sqlPS1);
$stmt->execute( );
$issues15PS = $stmt->rowCount();

$sqlPS2 = 'SELECT issues FROM allitems WHERE cn_sort REGEXP "^PS" and issues > 5 and issues < 11';
$stmt = $pdo->prepare($sqlPS2);
$stmt->execute( );
$issues610PS = $stmt->rowCount();

$sqlPS3 = 'SELECT issues FROM allitems WHERE cn_sort REGEXP "^PS" and issues > 10';
$stmt = $pdo->prepare($sqlPS3);
$stmt->execute( );
$issues10PS = $stmt->rowCount();


//set page title here
$pagetitle = "Literature Module";



include "includes/header.php";


?>

    
<style>

.litsection{float:left;margin-left:5px;border-right:#dddddd 1px solid;margin-top:20px;}

</style>

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
        <div id="navbar" class="navbar-collapse collapse">
        
          <ul class="nav navbar-nav navbar-left">
                    

        
          </ul>
        
        </div>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <div class="col-md-3 col-md-offset-2 col-sm-6">
    
     
   
<h3 class="text-center">Top Authors</h3>

 <div class="table-responsive">
            <table class="table-hover table-striped table-condensed tablesorter table-bordered table" id="resultstab">
              <thead>
                <tr>
                  <th>Author</th>
                  <th>Total Items</th>
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

        <div class="col-md-6">
    <h1 class="page-header">Literature by Region</h1> 
                 
<p><a class="btn btn-danger btn-lg" href="authors_region.php?region=PR" role="button">English literature&raquo;</a></p>

<p><a class="btn btn-primary btn-lg" href="authors_region.php?region=PS" role="button">American literature&raquo;</a></p>


       
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
