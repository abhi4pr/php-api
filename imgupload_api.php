<?php

$conn = mysqli_connect("localhost","root","","ez_database");

$response = array();

if(is_uploaded_file($_FILES["user_image"]["tmp_name"]) && $_POST["user_name"]){

$user_name = $_POST["user_name"];
$tmp_file = $_FILES["user_image"]["tmp_name"];
$img_name = $_FILES["user_image"]["name"];
$upload_dir = "./images/".$img_name;

$sql = "INSERT INTO img_table(user_name,user_image) VALUES ('$user_name','$img_name')";

if(move_uploaded_file($tmp_file,$upload_dir) && $conn->query($sql)){
$response["MESSAGE"] = "Upload Success";
$response["STATUS"] = 200;
}else{
$response["MESSAGE"] = "Upload Failed";
$response["STATUS"] = 404;
}

}

 echo json_encode($response);

?>