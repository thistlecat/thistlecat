function ipCIDRCheck($IP, $CIDR) {
    list ($net, $mask) = explode ('/', $CIDR);
    $ip_net = ip2long ($net);
    $ip_mask = ~((1 << (32 - $mask)) - 1);
    $ip_ip = ip2long ($IP);
    return (($ip_ip & $ip_mask) == ($ip_net & $ip_mask));
  }
  
//get user IP to allow for updating
foreach ($ipranges as $rang){
//check if comparing against IP or CIDR
if (strpos($rang, "/") !== false){

     if (ipCIDRCheck($_SERVER['REMOTE_ADDR'], $rang)){
     $rangecheck = "check";
     }
}
else {
   if ($_SERVER['REMOTE_ADDR'] == $rang){
   $rangecheck = "check";
   }
}


}

if (($rangecheck == "check") or (isset($allowallip))) {
$logstatus = "loggedin";
}

else{
$logstatus = "";
}
