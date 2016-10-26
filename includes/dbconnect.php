<?php



// load dsn/connection data from
$incFile='config.php';
if (file_exists($incFile)) {
   require_once($incFile);
} else {
   echo '<b>Could not find the connection file</b>';
   exit();
}

$dsn = "mysql:host=" . THIS_HOST . ";dbname=" . THIS_DB . ";charset=utf8";

$opt = array(
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
);


$pdo = new PDO($dsn,COLL_USER,COLL_PASS, $opt);



?>