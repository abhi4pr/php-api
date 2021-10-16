<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

$HostName = "localhost"; 
$DatabaseName = "newdb"; 
$HostUser = "root"; 
$HostPass = "";
 
 $conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName); 
 
$sql = "SELECT area.DistName, dist.EnglishName from area LEFT JOIN dist ON area.DistID=dist.DistID UNION ALL SELECT area.DistName, dist.EnglishName from area RIGHT JOIN dist ON area.DistID=dist.DistID;"; 
$result = $conn->query($sql);
 
if ($result->num_rows > 0) { 
 $data = array();
 while($row = $result->fetch_assoc()) {
 
 $item[] = $row; 
 $json = json_encode($item);
 
 }
 
} else {
 echo "No Results Found.";
}
 echo $json;
$conn->close();
?>