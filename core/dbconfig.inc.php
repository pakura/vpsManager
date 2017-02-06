<?php
session_start();
$access = false;
$host = '127.0.0.1';
$user = 'root';
$pass = 'MobilityDB123!))';
$db = 'mobility';
$con = mysqli_connect($host, $user, $pass, $db);
$ips_res = mysqli_query($con, "SELECT ip FROM access where 1=1");
$access_ips = array();
while ($row = mysqli_fetch_assoc($ips_res)){
    $access_ips[] = strval($row['ip']);
}

$ip = strval($_SERVER['REMOTE_ADDR']);
foreach ($access_ips as $key => $val){
    if($val == $ip)
        $access=true;
}

if($access == false && $config->ip_access == true){
    die('Access denied');
}
date_default_timezone_set('Asia/Tbilisi');
?>