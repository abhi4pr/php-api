<?php

$HostName = "localhost";
 
$DatabaseName = "ez_database";
 
$HostUser = "root";
 
$HostPass = "";
 
 $con = mysqli_connect($HostName,$HostUser,$HostPass,$DatabaseName);
 
 $json = file_get_contents('php://input');
 
 $obj = json_decode($json,true);
 
$name = $obj['name'];
$id = $obj['id'];
 
$Sql_Query = "UPDATE api_table SET name ='$name' WHERE id='$id'";
 
 if(mysqli_query($con,$Sql_Query)){
 
$MSG = 'Data updated Successfully into MySQL Database' ;
 
$json = json_encode($MSG);
 
 echo $json ;
 
 }
 else{
 
 echo 'Try Again';
 
 }
 mysqli_close($con);

?>