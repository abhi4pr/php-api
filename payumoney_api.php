<?php 

$HostName = "localhost";
$DatabaseName = "id3505550_wordpress3";
$HostUser = "id3505550_root";
$HostPass = "moc.tsohbew000@123";
 
$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);

$obj = json_decode(file_get_contents('php://input'), true);

if(isset($obj["txnid"]))
{
    $merchant_key = "CQFOIEbB";
    $salt = "FBlXRuSgG5";
    $txnid = $obj['txnid'];
    $amount = $obj['amount'];
    $productinfo = $obj['productinfo'];
    $firstname = $obj['firstname'];
    $email = $obj['email'];
    $phone = $obj['phone'];

    $query = "INSERT INTO `pay_table` ( txnid, amount, productinfo, firstname, email, phone ) VALUES ('$txnid', '$amount', '$productinfo', '$firstname', '$email', '$phone')";
    
    $hash = $merchant_key."|".$txnid."|".$amount."|".$productinfo."|".$firstname."|".$email."|".$phone."|".$salt;
    $hashkey = strtolower(hash('sha512', $hash));

    $result = $conn->query($query);
    
    if ($result == 1)
    {
        $data[hash] = $hashkey;
        header('Content-type: application/json');
                
    }
    else
    {
        $data["message"] = "data not saved successfully";
        $data["status"] = "error";    
    }
}
else
{
    $data["message"] = "Format not supported";
    $data["status"] = "error";    
}
    echo json_encode($data);