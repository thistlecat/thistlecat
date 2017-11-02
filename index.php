<?php
   ini_set('session.cache_limiter','public');
   session_cache_limiter(false);
   session_start();
   
   ?>

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Thistle Collection Analysis Tool</title>
      <?php include "config.php"; ?>
      <?php include "includes/header.php"; ?>
      <!-- Custom styles for this template -->
      <style type="text/css">
         /* Move down content because we have a fixed navbar that is 50px tall */
         body {
         padding-top: 50px;
         padding-bottom: 20px;
         }
         .jumbotron{
         padding-top:25px;
         padding-bottom:25px;
         margin-bottom:0px;
         background-image: url(books.png);
         background-position: 0% 0%;
         background-size: cover;
         background-repeat: no-repeat;
         }
         label {
         font-weight: normal !important;
         }
         .col-md-4:nth-of-type(odd) { 
         background: #FFFACD;
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
         <div class="container">
            <div class="navbar-header">
               <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
               <span class="sr-only">Toggle navigation</span>
               <span class="icon-bar"></span>
               <span class="icon-bar"></span>
               <span class="icon-bar"></span	>
               </button>
               <img style="float:left;" src="milkwhite.png" width="50"><a class="navbar-brand" href="#">ThistleCAT <?php echo $libraryname; ?></a>
            </div>
         </div>
      </nav>
      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
         <div class="container">
            <h2>Thistle Collection Analysis Tool</h2>
            <p>ThistleCAT assists in analyzing your library's collection by providing data visualizations based on various parameters including copyright date, total checkouts, and last checkout date.</p>
            <p>
               <a class="btn btn-primary btn-lg" href="overview.php" role="button">Collection visualizations &raquo;</a>
               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-success btn-lg" href="languages.php" role="button">Foreign language module&raquo;</a>
               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-warning btn-lg" href="authorsindex.php" role="button">Literature module&raquo;</a>
            </p>
         </div>
      </div>
      <div class="container">
         <div class="row" id="resultsgohere"> </div>
         <!-- Example row of columns -->
         <div class="row">
            <div class="col-md-4">
                <h2>About this Tool</h2>
                  <p>ThistleCAT was developed in 2015 at the Virginia Military Institute's Preston Library.
                  For more information, see <a href="http://www.thistlecat.org">http://www.thistlecat.org</a>.
                  </p>
				  <br />
               <h2>Miscellaneous Views</h2>
               <ul>
                  <li><a href="allcheckouts.php">All Items by Total Checkouts</a></li>
                  <li><a href="alllastborrowed.php">All Items by Last Checkout Date</a></li>
                  <li><a href="relative_issues.php">Ratio of Checkouts to Collection Size</a></li>
               </ul>
               <br />
               <h2>Barcode List Analysis</h2>
               <p>Upload a file of scanned barcodes to analyze them.</p>
               <form action="" method="POST" enctype="multipart/form-data">
                  <input class="btn btn-default" type="file" name="image" /><br />
                  <input value="Upload file" class="btn btn-primary"  type="submit"/>
               </form>
               <br />
               <?php
                  if(isset($_FILES['image'])){
                     $errors= array();
                     $file_name = $_FILES['image']['name'];
                     $file_size =$_FILES['image']['size'];
                     $file_tmp =$_FILES['image']['tmp_name'];
                     $file_type=$_FILES['image']['type'];
                  
                  $tmp = explode('.',$_FILES['image']['name']);
                  $file_extension = end($tmp);
                  
                  
                     $file_ext=strtolower($file_extension);
                     
                     $expensions= array("txt");
                     
                     if(in_array($file_ext,$expensions)=== false){
                        $errors[]="extension not allowed, please choose a TXT file.";
                     }
                  
                     
                     if(empty($errors)==true){
                   
                   $barcodequery = "";
                  $allbarcodes = file($file_tmp);//file in to an array
                  
                  foreach ($allbarcodes as $thisbarcode){
                       if (is_numeric(trim($thisbarcode))) {
                   $barcodequery .= 'barcode = "' . trim($thisbarcode) . '" OR ';
                  	 }
                   else {echo "This file contains at least one invalid barcode.";break 3;}
                  }
                  
                  //remove last OR from query
                  $barcodequery = substr($barcodequery, 0, -3);
                  $barcodequery = str_replace(array("\r", "\n"), '', $barcodequery);
                  $_SESSION["uploadquery"] = $barcodequery;
                  
                  echo "<a href='barcodes.php'>" . count($allbarcodes) . " barcodes found. Go to analysis >> </a>";
                  
                     }else{
                        print_r($errors);
                     }
                  }
                  ?>
            </div>
            <div class="col-md-4">
               <h2>Query Builder</h2>
               <form name="queryform" action="querybuilder.php">
                   <div class="queryheader">Call Number</div> 
       <label class="radio-inline"><input type="radio" id="radiostart" name="callnotype">starts with</label>

<label class="radio-inline"><input type="radio" id="radiobw" name="callnotype">is between</label>


    <div class="form-inline">
   <input  class="form-control input-sm" type="text" size="20" name="itemcallnumberstart" id="itemcallnumberstart" placeholder="Call number start" /><i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Only enter LC classes or class numbers, not Cutter numbers (e.g., RB or RB120, not RB120 .D49)"></i>
   </div>
   <div class="form-inline">
   <input  class="form-control input-sm" type="text" size="20" name="itemcallnumberend" id="itemcallnumberend" placeholder="Call number end"/>
   </div>
    <br />
            
                  <div class="queryheader">Copyright Date</div>
                  <div class="form-inline">
                     <select  class="form-control input-sm" name="datefmt" >
                        <option value="before">Before</option>
                        <option value="after">After</option>
                        <option value="equal">Equal to</option>
                     </select>
                     <input  class="form-control input-sm" type="text" size="10" name="copyrightdate" placeholder="YYYY" />
                  </div>
                  <br />
                  <div class="queryheader">Total Checkouts</div>
                  <div class="form-inline">
                     <select  class="form-control input-sm"  name="checkoutfmt"  >
                        <option value="less">Fewer than</option>
                        <option value="greater">Greater than</option>
                        <option value="equal">Equal to</option>
                     </select>
                     <input  class="form-control input-sm" type="text" size="5" name="issues" />
                  </div>
                  <br />
                  <div class="queryheader">Last Checkout Date</div>
                  <div class="form-inline">
                     <select  class="form-control input-sm"  name="lastdatefmt" >
                        <option value="before">Before</option>
                        <option value="after">After</option>
                        <option value="equal">Equal to</option>
                     </select>
                     <input  class="form-control input-sm" type="text" size="10" placeholder="YYYY" name="lastborrowed" />
                  </div>
                  <BR />
                  <div class="queryheader">Language</div>
                  <select  class="form-control input-sm"  name="language" id="language" >
                     <option value="">All</option>
                     <option value="noneng">All non-English</option>
                     <option value="ara">Arabic</option>
                     <option value="chi">Chinese</option>
                     <option value="fre">French</option>
                     <option value="ger">German</option>
                     <option value="gre">Greek</option>
                     <option value="ita">Italian</option>
                     <option value="jpn">Japanese</option>
                     <option value="lat">Latin</option>
                     <option value="por">Portuguese</option>
                     <option value="rus">Russian</option>
                     <option value="spa">Spanish</option>
                  </select>
                  <BR />
                  <div class="queryheader">VIVA Protected Status</div>
                  <select  class="form-control input-sm"  name="special" >
                     <option value="">All</option>
                     <option value="yes">Protected</option>
                     <option value="no">Unprotected</option>
                  </select>
                  <BR />
                  <!-- <input type="checkbox" value="yes" name="duplicates" id="duplicates"> <label for="duplicates">Has Possible Duplicates</label> -->
                  <BR /><BR />
                  <span class="label label-warning">  Percentage of Class to Weed</span><i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Enter a value here to determine the best cutoff date if you only want to weed a certain percentage of the specified call number range."></i><br />
                  <div class="form-inline">
                     <input  class="form-control input" type="text" size="20" name="percent" placeholder="Enter number only" />
                  </div>
                  <BR />
                  <button type="submit" class="btn btn-primary">Search</button>
               </form>
            </div>
            <div class="col-md-4">
               <h2>Quick Item Lookup</h2>
               <form class="form-inline" id="lookupitem">
                  <div class="form-group">
                     <input type="text" class="form-control" id="barcode" name="barcode" placeholder="Enter item barcode">
                  </div>
                  <button type="submit" class="btn btn-primary btn-sm" onclick="return getitem();" >Lookup Item</button>
               </form>
               <br />
               <p>Get data for an individual item, including:
               <ul>
                  <li>Total checkouts</li>
                  <li>Last checkout date</li>
                  <li>Language</li>
               </ul>
               </p>
               <br />
               <br />
               <!--  <h2 class="text-danger">Alternate Editions Lookup</h2> 
                  <form name="queryform" action="duplicatereport.php">  
                   <span class="label label-danger">New!!!</span> View similar and alternate editions for items by LC class.<br /><br /> 
                   <select  class="form-control input-sm"  name="lcclass">
                  <option value="A">A</option>
                  <option value="B">B</option>
                  <option value="C">C</option>
                  <option value="D">D</option>
                  <option value="E">E</option>
                  <option value="F">F</option>
                  <option value="G">G</option>
                  <option value="H">H</option>
                  <option value="J">J</option>
                  <option value="K">K</option>
                  <option value="L">L</option>
                  <option value="M">M</option>
                  <option value="N">N</option>
                  <option value="P">P</option>
                  <option value="Q">Q</option>
                  <option value="R">R</option>
                  <option value="S">S</option>
                  <option value="T">T</option>
                  <option value="U">U</option>
                  <option value="V">V</option>
                  <option value="Z">Z</option>
                  
                  </select><br />
                  <button type="submit" class="btn btn-primary">Get Similar Editions</button>
                  </form>
                            <br /><br />
                  -->
            </div>
         </div>
         <hr />
      </div>
      <!-- /container -->
      <!-- Bootstrap core JavaScript
         ================================================== -->
      <!-- Placed at the end of the document so the pages load faster -->
      <script src="js/bootstrap.min.js"></script>
      <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
      <script src="js/ie10-viewport-bug-workaround.js"></script>
      <script type="text/javascript">
         function getitem(){
         $("#resultsgohere").load("itemlookup.php?" + $('#lookupitem').serialize() ).animate({
            height: 220,
            opacity: 1
         }, 350);
         
         }
         $(document).ready(function(e) {
		 $('#itemcallnumberend').attr("disabled","disabled");

if ($("#radiobw").is(':checked')) {
  $('#itemcallnumberend').removeAttr("disabled");
}

$('#radiostart').click(function()
{
  $('#itemcallnumberend').attr("disabled","disabled");
   $('#itemcallnumberend').val("");
});

$('#radiobw').click(function()
{
	  $('#itemcallnumberend').removeAttr("disabled");

});

              $('[data-toggle="tooltip"]').tooltip({
         
                placement : 'top'
         
            });
         	
         $("#lookupitem").on('submit',function(e) {
            e.preventDefault();
         });
         	
            });
      </script>
   </body>
</html>
