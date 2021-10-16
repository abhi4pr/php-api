<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

$HostName = "localhost"; 
$DatabaseName = "newdb"; 
$HostUser = "root"; 
$HostPass = "";
 
 $con = mysqli_connect($HostName,$HostUser,$HostPass,$DatabaseName);
 
 $json = file_get_contents('php://input');
 
 $obj = json_decode($json,true);
 
$name = $obj['name'];
 
$pass = $obj['pass'];
 
$Sql_Query = "select * from tablename where name = '$name' and pass = '$pass'";

$result = $con->query($Sql_Query);

if($result->num_rows > 0){
	echo json_encode($name);
}
 else{
 	echo json_encode('nooo');
 }
 
 mysqli_close($con);
?>