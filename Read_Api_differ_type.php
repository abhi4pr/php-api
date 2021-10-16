<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

$HostName = "localhost"; 
$DatabaseName = "ez_database"; 
$HostUser = "root"; 
$HostPass = "";
 
 $conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);
 
if ($conn->connect_error) { 
 die("Connection failed: " . $conn->connect_error);
} 
 
$sql = "SELECT * FROM api_table"; 
$result = $conn->query($sql);
 
if ($result->num_rows >0) { 
 
 while($row[] = $result->fetch_assoc()) {
 
 $item['userlist'] = $row; 
 $json = json_encode($item);
 
 }
 
} else {
 echo "No Results Found.";
}
 echo $json;
$conn->close();
?>