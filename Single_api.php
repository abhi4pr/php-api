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
 
$ID = $obj['pid']; 
$CheckSQL = "SELECT * FROM post_table WHERE pid='$ID'"; 
$result = $con->query($CheckSQL);
 
if ($result->num_rows >0) { 
 while($row[] = $result->fetch_assoc()) {
 
	 $Item = $row; 
	 $json = json_encode($Item);
 
 }
 
}else { 
 echo "No Results Found."; 
}
 
echo $json;
 
$con->close();
?>