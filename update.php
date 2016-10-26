<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);


include 'config.php';


//get user IP to allow for updating
$thisip = $_SERVER['REMOTE_ADDR'];
if ((in_array($_SERVER['REMOTE_ADDR'], $ipranges) or (isset($allowallip)))) {



$dsn = "mysql:host=" . THIS_HOST . ";dbname=" . THIS_DB . ";charset=utf8";

$opt = array(
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
);


$pdo = new PDO($dsn,COLL_USEREDIT,COLL_PASSEDIT, $opt);




//keep track of number of records changed
$totalchanged = 0;

foreach  ($_POST as $thisbarcode => $thisstatus){
	

$sql = 'UPDATE ' . THIS_TABLE . ' SET status=:status WHERE barcode=:barcode';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":status", $thisstatus, PDO::PARAM_STR);
    $stmt->bindValue(":barcode", $thisbarcode, PDO::PARAM_STR);
    $stmt->execute();


//keep track of number of records changed
$totalchanged += $stmt->rowCount();

}
//display how many rows were updates
echo $totalchanged . " records were updated.<br /><br />";
echo '<a class="btn btn-success" href="' . $_SERVER['HTTP_REFERER'] . '" role="button">&laquo; Return to previous search</a>';

}

else{
header("HTTP/1.0 404 Not Found");
die("Not found.");
}


?>
