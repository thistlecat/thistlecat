<?php

//database settings
//probaby don't need to change this
define('THIS_HOST','localhost');

//database name
define('THIS_DB','');

//item table name
define('THIS_TABLE','');

//author table name
define('AUTHOR_TABLE','');

//LC data table name
define('LC_TABLE','');

//mysql username and password with select permissions only
define('COLL_USER','thistleuser');
define('COLL_PASS', '');

//mysql username and password with select and update permissions
define('COLL_USEREDIT','thistleedit');
define('COLL_PASSEDIT', '');

$libraryname = "";


//configure catalog links, you can have as many as you want
//make sure the field name to search on matches the column name in the database
//enter the URL pattern using the string "MAGICNUMBER" in place of the data being searched
//e.g., "http://yourlibrary.catalog.edu?record=b351241" should be entered as
//$catalogs = array( 
//            array (
//              "field" => "biblionumber",
//              "pattern" => "http://yourlibrary.catalog.edu?record=MAGICNUMBER",
//	        "abbrev" => "Catalog"
//            )
//         );

$catalogs = array( 
            array (
               "field" => "",
               "pattern" => "",
	       "abbrev" => ""
            )
         );



//enter single IP addresses or CIDRs (separated by commas) to allow users to change weeding statuses
//e.g., $ipranges = array('144.75.185.37','144.75.196.0/24');

//this can be left empty if you don't want to use this feature
$ipranges = array('');

//to allow all users to edit statuses, uncomment the line below (this is NOT advised to do on a production database)
//$allowallip = 'true';



//total circulation thresholds (there must be 3)
$circ0 = 0;
$circ1 = 5;
$circ2 = 20;


//last checkout date thresholds (there must be 2)
$last1 = 2005;
$last2 = 2010;


//palette colors
$color1 = '#660000';
$color2 = '#666666';
$color3 = '#99ccff';
$color4 = '#1aaf5d';

?>




