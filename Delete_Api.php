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
 
$id = $obj['id']; 
$Sql_Query = "DELETE from api_table WHERE id='$id'";
 
 if(mysqli_query($con,$Sql_Query)){
 
$MSG = 'Data deleted Successfully' ;
 
$json = json_encode($MSG); 
 echo $json ; 
 }
 else{ 
 echo 'Some error occured'; 
 }
 mysqli_close($con);

?>