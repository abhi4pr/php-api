<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

$HostName = "localhost"; 
$DatabaseName = "ez_database"; 
$HostUser = "root"; 
$HostPass = "";
 
 $con = mysqli_connect($HostName,$HostUser,$HostPass,$DatabaseName); 
 $json = file_get_contents('php://input'); 
 $obj = json_decode($json,true); 
$name = $obj['name'];
 
$Sql_Query = "insert into api_table (name) values ('$name')"; 
 
 if(mysqli_query($con,$Sql_Query)){
 
$MSG = 'Data Inserted Successfully into MySQL Database' ;
 
$json = json_encode($MSG);
 
 echo $json ;
 
 }
 else{
 
 echo 'Try Again';
 
 }
 mysqli_close($con);

?>