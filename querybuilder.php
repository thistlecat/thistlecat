<?php
//set page title here
$pagetitle = "Query Builder";

include 'config.php';

//authenticate user's IP
include 'includes/ipauth.php';

include "includes/header.php";

// function to convert itemcallnumber to cnsort
function cnsort_convert($callno)
	{
	// make sure first char is letter
	if (is_numeric($callno[0]))
		{
		return false;
		exit;
		}
	  else
		{
		// find position of first number
		if (preg_match('/[0-9]/', $callno, $matches, PREG_OFFSET_CAPTURE))
			{
			$numposition = $matches[0][1];
			// find out how long number is
			$numlen = strlen($callno) - $numposition;
			// get full num
			$fullnumber = substr($callno, $numposition, $numlen);
			// pad with necessary zeroes to make it 4 digits
			return substr($callno, 0, $numposition) . str_pad($fullnumber, 4, "0", STR_PAD_LEFT);
			}
		// if no numbers, return what was entered (no need to convert)
		  else
			{
			return $callno;
			exit;
			}
		}
	}
	
?>
<script type="text/javascript">
$(document).ready(function() 
 {
     $('#itemcallnumberend').attr("disabled", "disabled");
     $("#radiostart").prop("checked", true);

     $('#radiostart').click(function() {
         $('#itemcallnumberend').attr("disabled", "disabled");
         $('#itemcallnumberend').val("");
     });
     $('#radiobw').click(function() {
         $('#itemcallnumberend').removeAttr("disabled");
     });
	 $('[data-toggle="tooltip"]').tooltip({
        placement : 'top'
    });
	
    //activate table sorter
	$("#resultstab").tablesorter(); 
	
	//insert query values into form
	var urlquery = window.location.search.substring(1);
	var urlterms = urlquery.split("&");
	
	for (var i = 0; i < urlterms.length; i++) {
		//check last character of string to see if the term is emtpy or not
		if (urlterms[i].slice(-1) != "="){
			var strsplit = urlterms[i].split('=');
			     if (strsplit[0] == 'itemcallnumberstart'){
					  		$("#itemcallnumberstart").val(strsplit[1]);
							}
				if (strsplit[0] == 'itemcallnumberend'){
					  		$("#itemcallnumberend").val(strsplit[1]);
							if (strsplit[1] != ""){$('#itemcallnumberend').removeAttr("disabled");$("#radiobw").prop("checked", true);}
							}
				if (strsplit[0] == 'percent'){
					  		$("#percent").val(strsplit[1]);
							}
				if (strsplit[0] == 'copyrightdate'){
					  		$("#copyrightdate").val(strsplit[1]);
							}
				if (strsplit[0] == 'datefmt'){
					  		$("#datefmt").val(strsplit[1]);
							}
				if (strsplit[0] == 'checkoutfmt'){
					  		$("#checkoutfmt").val(strsplit[1]);
							}
				if (strsplit[0] == 'issues'){
					  		$("#issues").val(strsplit[1]);
							}   
				if (strsplit[0] == 'lastdatefmt'){
					  		$("#lastdatefmt").val(strsplit[1]);
							} 
				if (strsplit[0] == 'lastborrowed'){
					  		$("#lastborrowed").val(strsplit[1]);
							} 
				if (strsplit[0] == 'holdingsfmt'){
					  		$("#holdingsfmt").val(strsplit[1]);
							} 
				if (strsplit[0] == 'holdings'){
					  		$("#holdings").val(strsplit[1]);
							} 
				if (strsplit[0] == 'holdingsloc'){
					  		$('#' + strsplit[1]).prop('checked',true);
							} 
				if (strsplit[0] == 'special'){
					  		$("#special").val(strsplit[1]);
							} 
					if (strsplit[0] == 'language'){
					  		$("#language").val(strsplit[1]);
							} 
				if (strsplit[0] == 'duplicates'){
					  		$('#duplicates').prop('checked',true);
							} 
							
		}
    }
	
    } 
); 



</script>

 <link href="css/grid.css" rel="stylesheet">
		

<style type="text/css">
#lclist li {
    list-style:none;
	margin-left:-20px;
   
}#accordion label{
font-size:0.85em;display: inline !important;
font-weight:normal;
}

td img { display: inline-block; }
th { cursor: pointer; }

#resultstab .radio {
    display: block;
    margin-bottom: 0px;
    margin-top: 0px;
    position: relative;
}
.Keep{
	background-color:#98FB98 !important;
}
.Weed{
	background-color:#FFCCCC !important;
}
.Check{
	background-color:#ccc !important;
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
        
       <form name="queryform" action="querybuilder.php">  
<div class="queryheader">Call Number</div>

<label class="radio-inline"><input type="radio" id="radiostart" name="callnotype">starts with</label>

<label class="radio-inline"><input type="radio" id="radiobw" name="callnotype">is between</label>


<div class="form-inline">
    <input class="form-control input-sm" type="text" size="20" name="itemcallnumberstart" id="itemcallnumberstart" placeholder="Call number start" /><i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Only enter LC classes or class numbers, not Cutter numbers (e.g., RB or RB120, not RB120 .D49)"></i>
</div>
<div class="form-inline">
    <input class="form-control input-sm" type="text" size="20" name="itemcallnumberend" id="itemcallnumberend" placeholder="Call number end" />
</div>
<br />

            
        
<div class="queryheader">Copyright Date</div>
    <div class="form-inline"><select  class="form-control input-sm" name="datefmt" id="datefmt" >
       <option value="before">Before</option>
    <option value="after">After</option>
      <option value="equal">Equal to</option>
      </select>
   <input  class="form-control input-sm" type="text" size="10" name="copyrightdate" id="copyrightdate" placeholder="YYYY" /></div>
    <br />
        
   <div class="queryheader">Total Checkouts</div>
    <div class="form-inline"><select  class="form-control input-sm"  name="checkoutfmt" id="checkoutfmt"  >
       <option value="less">Fewer than</option>
    <option value="greater">Greater than</option>
      <option value="equal">Equal to</option>
      </select>
   <input  class="form-control input-sm" type="text" size="5" name="issues" id="issues" /></div>
      
        <br />
         <div class="queryheader">Last Checkout Date</div>
    <div class="form-inline"><select  class="form-control input-sm"  name="lastdatefmt" id="lastdatefmt" >
    <option value="before">Before</option>
   <option value="after">After</option>
      <option value="equal">Equal to</option></select>
   <input  class="form-control input-sm" type="text" size="10" placeholder="YYYY" name="lastborrowed" id="lastborrowed" /></div>
   
        
 
   
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
   <select  class="form-control input-sm"  name="special" id="special" >
     <option value="">All</option>
    <option value="yes">Protected</option>
      <option value="no">Unprotected</option>
      </select>
      
  <BR />
  
 <!--   <input type="checkbox" value="yes" name="duplicates" id="duplicates"> <label for="duplicates">Has Possible Duplicates</label> -->

  <BR />  <BR />

    <div class="queryheader">Percentage of Class to Weed <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Enter a value here to determine the best cutoff date if you only want to weed a certain percentage of the specified call number range."></i></div>
    <div class="form-inline">
   <input  class="form-control input-sm" type="text" size="20" name="percent" id="percent" placeholder="Enter number" />%</div>
<br /><br />
  <button type="submit" class="btn btn-primary">Search</button>
   </form>
   
        </div>
        
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
      

        <?php
//see if we're on a results page or not
$url       = $_SERVER['REQUEST_URI'];
$parsedurl = parse_url($url);
if (!isset($parsedurl['query'])) {
    
    echo "";
    
    
} else {
    
    if ($logstatus == "loggedin") {
        echo ' <form action="update.php" method="post" name="changestatus">';
        echo '<input type="Submit" class="btn btn-primary" value="Save Statuses"><br />';
        
    }
     
    
    //construct our query - the fun part
    //convert the terms into an array
    parse_str($parsedurl['query'], $query_array);
    
    $queryarray = array();
    
    foreach ($query_array as $k => $v) {
    //determine if single call number or range
if (($k == 'itemcallnumberstart') and ($v <> "") and (($query_array['itemcallnumberend'] == "") or !isset($query_array['itemcallnumberend']))) {
$callnumberquery = ' cn_sort like "' . cnsort_convert($v) . '%"';
$queryarray[] = $callnumberquery;
	}
	
//only use less than or equal to if it's a subclass only
if (($k == 'itemcallnumberstart') and ($v <> "") and ($query_array['itemcallnumberend'] <> "") and isset($query_array['itemcallnumberend'])) {
$callnumberquery = ' ((cn_sort > "' . cnsort_convert($v) . '" and cn_sort < "' . cnsort_convert($query_array['itemcallnumberend']) . '") OR cn_sort LIKE "' . cnsort_convert($query_array['itemcallnumberend']) . '%")' ;
$queryarray[] = $callnumberquery;
	}


        if (($k == 'copyrightdate') and ($v <> "")) {
            $thisformat = $query_array['datefmt'];
            if ($thisformat == 'before') {
                $boolean = ' < ';
            }
            if ($thisformat == 'after') {
                $boolean = ' > ';
            }
            if ($thisformat == 'equal') {
                $boolean = ' = ';
            }
            $queryarray[] = ' copyrightdate ' . $boolean . ' "' . $v . '"';
        }
        
        if (($k == 'issues') and ($v <> "")) {
            $thisformat = $query_array['checkoutfmt'];
            if ($thisformat == 'less') {
                $boolean = ' < ';
            }
            if ($thisformat == 'greater') {
                $boolean = ' > ';
            }
            if ($thisformat == 'equal') {
                $boolean = ' = ';
            }
            $queryarray[] = ' issues ' . $boolean . ' "' . $v . '"';
        }
        
        if (($k == 'lastborrowed') and ($v <> "")) {
            $thisformat = $query_array['lastdatefmt'];
            if ($thisformat == 'before') {
                $boolean = ' < ';
            }
            if ($thisformat == 'after') {
                $boolean = ' > ';
            }
            if ($thisformat == 'equal') {
                $boolean = ' = ';
            }
            $queryarray[] = ' lastborrowed ' . $boolean . ' "' . $v . '"';
        }
        
        if (($k == 'holdings') and ($v <> "")) {
            $thisformat = $query_array['holdingsfmt'];
            if ($thisformat == 'less') {
                $boolean = ' < ';
            }
            if ($thisformat == 'greater') {
                $boolean = ' > ';
            }
            if ($thisformat == 'equal') {
                $boolean = ' = ';
            }
            $queryarray[] = ' ' . $query_array['holdingsloc'] . 'Holdings ' . $boolean . ' "' . $v . '"';
        }
        if ($k == 'special') {
            if ($v == 'yes') {
                $queryarray[] = ' special REGEXP "VIVA"';
            }
            if ($v == 'no') {
                $queryarray[] = ' special NOT REGEXP "VIVA"';
            }
        }
        if ($k == 'duplicates') {
            if ($v == 'yes') {
                $queryarray[] = ' duplicates is not null';
            }
            //if ($v == 'no') {$queryarray[] = ' duplicates is null';}
        }
        
        
        if (($k == 'language') and ($v <> '')) {
            if ($v == 'noneng') {
                $queryarray[] = ' language <> "eng" and language is not null';
            } else {
                $queryarray[] = ' language = "' . $v . '"';
            }
        }
        
    }
    
    
    $thisquery = implode(" and ", $queryarray);
    
    
    include "includes/dbconnect.php";
    
    $sql0 = 'SELECT * FROM ' . THIS_TABLE . ' WHERE' . $thisquery . ' ORDER BY cn_sort ASC';
    
    $stmt = $pdo->prepare($sql0);
    $stmt->execute();
    $searchresults = $stmt->fetchAll();
    
    
    //function to remove array elements with a certain value (to get rid of dates that are zero)
    function removeElementWithValue($array, $key, $value)
    {
        foreach ($array as $subKey => $subArray) {
            if ($subArray[$key] == $value) {
                unset($array[$subKey]);
            }
        }
        return $array;
    }
    
    
    //get top X percent of results, if set
    if (isset($query_array['percent'])) {
        if ($query_array['percent'] <> "") {
            
            $datestosort = array();
            //create array of dates only, so we can sort on that field
            foreach ($searchresults as $searchdate) {
                $datestosort[] = $searchdate['copyrightdate'];
            }
            $shortarray = $searchresults;
            array_multisort($datestosort, SORT_ASC, $shortarray);
            $shortarray = removeElementWithValue($shortarray, "copyrightdate", 0);
            
            //find out how many results total are in this class (to calculate percentage)
            $sqltotal = 'SELECT count(*) as totalcount FROM ' . THIS_TABLE . ' WHERE ' . $callnumberquery;
            $stmt     = $pdo->prepare($sqltotal);
            $stmt->execute();
            $searchresultstotal = $stmt->fetch();
            
            //calculate percentage
            $desirednumber = round(($query_array['percent'] / 100) * $searchresultstotal['totalcount']);
            
            
            
            //make sure it's not higher than the total results brought back
            if ($desirednumber > count($searchresults)) {
                echo "These search parameters retrieve  " . round(count($searchresults) / $searchresultstotal['totalcount'] * 100) . "% of the range.";
                
            } else {
                
                
                $shortarray = array_slice($shortarray, 0, $desirednumber);
                
                $searchresults = $shortarray;
                //get last date in array, to find the cutoff
                $datecutoff    = $shortarray[$desirednumber - 1]['copyrightdate'];
                echo "<h4><span class='label label-primary'>Date cutoff for " . $query_array['percent'] . "%: " . $datecutoff . "</span></h4>";
				
				//reset the sort by call number
                function cmp($a, $b)
					{
						if ($a["cn_sort"] == $b["cn_sort"]) {
							return 0;
						}
						return ($a["cn_sort"] < $b["cn_sort"]) ? -1 : 1;
					}
					
					usort($searchresults,"cmp");
            }
            
        }
    }
    
    if (count($searchresults) > 5000) {
        echo "Your search retrieved " . count($searchresults) . " results, but ThistleCAT can only display a maximum of 5000. Please modify your search in the form on the left.";
        exit(); 
    }
    
    
    echo '  
        <a download="thistlecat_export.csv" href="#" onClick="return ExcellentExport.csv(this, \'resultstab\');">
        <button type="button" class="btn btn-success pull-right">
       <i class="fa fa-floppy-o" aria-hidden="true"></i> Export Results as CSV
        </button>
        </a>
		<div class="table-responsive">
            <table class="table table-striped table-condensed tablesorter table-bordered" id="resultstab">
              <thead>
                <tr>
				  <th>Weeding Status</th>
                  <th>Call Number</th>
                  <th>Barcode</th>
                  <th class="col-md-3">Title</th>
				  <th>Author</th>
                  <th>Date</th>
				  <th>Language</th>
                  <th>Total Checkouts</th>
                  <th>Last Checkout Date</th>
                 
                  <th>Special Attributes</th>
                  
                  
                </tr>
              </thead>
              <tbody>';
    
    
    
    echo '<div class="container">';
    echo '<div class="row text-center"><strong>' . count($searchresults) . " Items</strong></div>";
    echo '<div class="row text-center"><span class="label label-default">Your query: ' . $thisquery . '</span></div>';
    echo '</div>';
    
    
    foreach ($searchresults as $v2) {
        echo "<tr class='" . $v2['status'] . "'>";
        
        if ($logstatus == "loggedin") {
?>
<td><div style="display:none;"><?php
            echo $v2['status'];
?></div>
 <div class="radio">
  <label><input type="radio" name="<?php
            echo $v2['barcode'];
?>"<?php
            if ($v2['status'] == 'Keep') {
                echo " checked='checked'";
            }
?> value="Keep" />Keep</label>
</div>
<div class="radio">
  <label><input type="radio" name="<?php
            echo $v2['barcode'];
?>"<?php
            if ($v2['status'] == 'Weed') {
                echo " checked='checked'";
            }
?> value="Weed" />Weed</label>
</div>
<div class="radio">
  <label><input type="radio" name="<?php
            echo $v2['barcode'];
?>"<?php
            if ($v2['status'] == 'Check') {
                echo " checked='checked'";
            }
?> value="Check" />Check</label>
</div>
</td>
<?php
        } else {
            echo "<td id='" . $v2['barcode'] . "'>" . $v2['status'] . "</td>";
        }
        
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
        echo "<td  title='Copyright Date'>" . $v2['copyrightdate'] . "</td>";
        echo "<td>";
        if ($v2['language'] <> 'eng' and $v2['language'] <> 'fre' and $v2['language'] <> 'spa' and $v2['language'] <> 'ger') {
            echo "<a target='_blank' href='https://www.loc.gov/marc/languages/language_code.html#" . substr($v2['language'], 0, 1) . "'>" . $v2['language'] . "</a>";
        } else {
            echo $v2['language'];
        }
        echo "</td>";
        echo "<td  title='Total Checkouts'>" . $v2['issues'] . "</td>";
        echo "<td  title='Last Checkout Date'>" . substr($v2['lastborrowed'], 0, 4) . "</td>";
        echo "<td>";
        if ($v2['special']) {
            echo "<span class='label label-success'>" . $v2['special'] . "</span>";
        }
        echo "</td>";
        echo "</tr>";
    }
    
    echo ' </tbody>
            </table>';
    
}

if ($logstatus == "loggedin") {
    
    
    
    echo ' </form>';
}

?>

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
