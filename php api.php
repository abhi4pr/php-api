<?php  
//connect with database
$con = mysql_connect('db662371336.db.1and1.com','dbo662371336','hus@1850') 
       or die('Cannot connect to the DB');
mysql_select_db('db662371336',$con);
$production = true ;

//if data is send by post method  
if(isset($_POST['action'])){
  $action = $_POST['action'] ;
  if($action != ""){
        //add donation request
        if($action == 'update_user_profile'){
          $user_id = $_POST['user_id'] ;
          
          $response_message_array = NULL;

          if (is_uploaded_file($_FILES['image']['tmp_name'])) {
              $pic_name = "";
              $uploads_dir = 'image/';
              $tmp_name = $_FILES['image']['tmp_name'];
              $temp_array = explode('.',$_FILES['image']['name']);
              $ext = end($temp_array);
              $pic_name = time().'.'.$ext ;
              move_uploaded_file($tmp_name, $uploads_dir.$pic_name);
              $file_path = $uploads_dir.$pic_name ;
              $update_query = " UPDATE users SET profile_picture = '$file_path' 
                                WHERE id = '$user_id' ";
              mysql_query($update_query); 

              $response_message_array['profile_picture'] = 'success' ;            
          }//end of the $_FILES['image']

          if(isset($_POST['name'])){
              $update_query = " UPDATE users SET name = '".$_POST['name']."' 
                                WHERE id = '$user_id' ";
              mysql_query($update_query);     

              $response_message_array['name'] = 'success' ;        
          }//end of the $_POST['name']

          if(isset($_POST['email'])){
              $select = " SELECT email FROM users WHERE email = '".$_POST['email']."' ";
              $result = mysql_query($select);
              
              if(mysql_num_rows($result) == 0){
                  $update_query = " UPDATE users SET email = '".$_POST['email']."' 
                                WHERE id = '$user_id' ";
                  mysql_query($update_query);
                  $response_message_array['email'] = 'success' ;       
              }else{
                  $response_message_array['email'] = 'not_updated:email already exist in database' ; 
              }
          }//end of the $_POST['email']

          if(isset($_POST['address'])){
              $update_query = " UPDATE users SET address = '".$_POST['address']."' 
                                WHERE id = '$user_id' ";
              mysql_query($update_query);   
              $response_message_array['address'] = 'success' ;       
          }//end of the $_POST['address']

          if(isset($_POST['mobile'])){
              $update_query = " UPDATE users SET address = '".$_POST['address']."' 
                                WHERE id = '$user_id' ";
              mysql_query($update_query);     
              $response_message_array['mobile'] = 'success' ;       
          }//end of the $_POST['mobile']
          
          $response['response'] = $response_message_array;   
        }
        #end of the update_user_profile

  }else{
    $response['error'] = 'Please specify the action';
  }#end of the else

  header('Content-type: application/json');
  echo json_encode($response);

  die;
}
#end of the post condition 

//if data is send by json row form 
$json = file_get_contents('php://input');
$obj = json_decode($json);
$action = $obj->{'action'} ;
$response = NULL ;

if($action != ""){
  //register
  if($action == 'register'){
    $select = "SELECT id FROM users WHERE email = '".$obj->{'email'}."' OR mobile = '".$obj->{'mobile'}."'";
    $result = mysql_query($select); 
    $number_of_rows = mysql_num_rows($result);

    // condition for user is not exist in database
    if($number_of_rows == 0){
      $insert_query = "INSERT INTO `users` ( name,
                                             email, 
                                             password, 
                                             address,
                                             mobile, 
                                             device_type , 
                                             device_token , 
                                             latitude , 
                                             longitude,
                                             created_at 
                                      )
                              VALUES ('".$obj->{'name'}."', 
                                      '".$obj->{'email'}."',
                                      '".md5($obj->{'password'})."',
                                      '".$obj->{'address'}."',
                                      '".$obj->{'mobile'}."',
                                      '".$obj->{'device_type'}."',
                                      '".$obj->{'device_token'}."',
                                      '".$obj->{'latitude'}."',
                                      '".$obj->{'longitude'}."',
                                      '".date("Y-m-d h:i:s")."'
                                     )";
      mysql_query($insert_query) ;
      $last_insert_id  = mysql_insert_id();
      $response['success'] = 'User created successfully.'; 
      $response['user_id'] = $last_insert_id; 
    }else{
      $response['error'] = 'User already exist.';
    }
  }
  #end of the register condition

  //login
  if($action == 'login'){ 
    $select = "SELECT id FROM users WHERE (`email` = '".$obj->{'userlogin'}."' OR `mobile` = '".$obj->{'userlogin'}."') AND `password` = '".md5($obj->{'password'})."' ";
    $result = mysql_query($select); 
    $number_of_rows = mysql_num_rows($result);
    if( $number_of_rows == 0 ){
      $response['error'] = 'Please check email or password.';
    }else{
      // update other info if user logged in
      $update = "UPDATE users SET device_type = '".$obj->{'device_type'}."' , 
                                  device_token = '".$obj->{'device_token'}."' , 
                                  latitude = '".$obj->{'latitude'}."' , 
                                  longitude = '".$obj->{'longitude'}."' 
                 WHERE (`email` = '".$obj->{'userlogin'}."' OR `mobile` = '".$obj->{'userlogin'}."' ) AND `password` = '".md5($obj->{'password'})."' ";
      mysql_query($update); 

      //select user data again
      $select_again = $select = "SELECT * FROM users WHERE (`email` = '".$obj->{'userlogin'}."' OR `mobile` = '".$obj->{'userlogin'}."' ) AND `password` = '".md5($obj->{'password'})."' ";
      $result_again = mysql_query($select_again); 
      $row = mysql_fetch_assoc($result_again) ;
      $response['success'] = 'User logged in successfully'; 
      $response['data'] = $row  ; 
    }
  }
  #end of the login condition

  //update lat / long
  if($action == 'update_lat_long'){
    $select = "SELECT id FROM users WHERE id = '".$obj->{'user_id'}."' ";
    $result = mysql_query($select); 
    $number_of_rows = mysql_num_rows($result);
    if( $number_of_rows == 0 ){
      $response['error'] = 'User id did not exist';
    }else{
      // update other info if user logged in
      $update = "UPDATE users SET latitude = '".$obj->{'latitude'}."' , 
                                  longitude = '".$obj->{'longitude'}."' 
                 WHERE id = '".$obj->{'user_id'}."'  ";
      mysql_query($update); 

      $response['success'] = 'Lat/Long of user is updated'; 
    }
  }
  #end of the update lat/long condition

  //cancel request
  if($action == 'close_request'){
      // update other info if user logged in
      $update = "UPDATE donation_request SET status = 'close' 
                 WHERE id = '".$obj->{'request_id'}."'  ";
      mysql_query($update); 

      $select = "SELECT * FROM donation_request WHERE id = '".$obj->{'request_id'}."' ";
      $result = mysql_query($select); 
      $row = mysql_fetch_assoc($result) ;
      $response['success'] = 'Donation request is closed.';
      $response['data'] = $row; 
  }
  #end of the cancel condition

  //Get request list based on distance
  if($action == 'get_requests_based_on_distance'){
      $user_id  = $obj->{'user_id'} ;
      $lat  = $obj->{'latitude'} ;
      $long = $obj->{'longitude'} ;
      $required_distance = $obj->{'distance'};  
      $select = "SELECT *,(((acos(sin((".$lat."*pi()/180)) * 
                          sin((`latitude`*pi()/180))+cos((".$lat."*pi()/180)) * 
                          cos((`latitude`*pi()/180)) * cos(((".$long."- `longitude`)* 
                          pi()/180))))*180/pi())*60*1.1515*1.609344
                      ) as distance 
                      FROM `donation_request` 
                      WHERE status = 'open'
                      HAVING distance <= ".$required_distance; 
      $result = mysql_query($select); 
      $response['data'] = NULL ;
      $count = 0 ;
      while($row = mysql_fetch_assoc($result)){
        $response['data'][$count] = $row;

        //get comment data regarding each request
        $select_comments = "SELECT * FROM comments_donation_request 
                            WHERE dontation_request_id = '".$row['id']."' ";
        $result_comments = mysql_query($select_comments);  
        while($row_comment = mysql_fetch_assoc($result_comments)){
           $response['data'][$count]['comments'][] = $row_comment;
        }//end of the inner while loop  

        $count++;
      }
      #end of the while loop
      $response['success'] = 'true';
  }
  #end of the get request list based on distance
  
  //set_comments
  if($action == 'set_comments'){
      $comment_text  = $obj->{'comment_text'} ;
      $dontation_request_id = $obj->{'dontation_request_id'} ;
      $user_id = $obj->{'user_id'};  
      $insert = "INSERT INTO comments_donation_request (  dontation_request_id ,
                                                          comment_text ,
                                                          user_id,
                                                          created_at )
                                              VALUES    ( '".$dontation_request_id."',
                                                          '".$comment_text."',
                                                          '".$user_id."',
                                                          '".date('Y-m-d H:i:s')."'
                                              )"; 
      mysql_query($insert);
      $last_insert_id = mysql_insert_id();
      $response['success'] = 'true';
      $response['comment_id'] = $last_insert_id ;
  }
  #end of the set comments

  //get_comments_based_on_request_id
  if($action == 'get_comments_based_on_request_id'){
      $dontation_request_id = $obj->{'dontation_request_id'} ;
      $user_id = $obj->{'user_id'};  
      
      //get comment data regarding each request
      $select_comments = "SELECT * FROM comments_donation_request 
                            WHERE dontation_request_id = '".$dontation_request_id."'  ";
      $result_comments = mysql_query($select_comments);  
      $response['success'] = 'true';
      $response['data'] = NULL ;
      $count = 0 ;
      while($row_comment = mysql_fetch_assoc($result_comments)){
          $response['data']['comments'][$count] = $row_comment;
          $count++;  
      }//end of the inner while loop 
  }
  #end of the get_comments_based_on_request_id
  //Forgot Password
  if($action == "forgotpass"){
    $select = "SELECT password FROM users WHERE email = '".$obj->{'email'}."'";
    $result = mysql_query($select,$con);

    $number_of_rows = mysql_num_rows($result);

    if($number_of_rows == 0){
      $response['error'] = "No such email id exist"; 
    }else{
      
      $to = $obj->{'email'};
      $subject = "New Password";
      $message = "Hello, You New Password: ".random_password(15);
      $headers .= "Content-type:text/html;charset=UTF-8";

      mail($to,$subject,$message,$header);

      $response['message'] = "Email Sent Successfully";
    }
  }
  #end of the Fortgot Password Condition
  if($action == "allusers"){
    if($obj->{'last_modified_time'} == 0 ){
      $select = "SELECT * FROM users WHERE id != ". $obj->{'id'};
    }else{
      $select = "SELECT * FROM users WHERE id != '".$obj->{'id'}."' AND created_at >= '".$obj->{'last_modified_time'}."'";
    }
    //echo $select ; die;
    $result = mysql_query($select,$con);
    $number_of_rows = mysql_num_rows($result);
    if($number_of_rows == 0){
      $response['error'] = "No Users Found";
    }else{
      $response['data'] = NULL;
      while ($row = mysql_fetch_assoc($result)){
        $response['data'][] = $row;
      }
    }
  }

  #end of the change password
  if($action == "change_password"){
    $old_password  = $obj->{'old_password'} ;
    $new_password = $obj->{'new_password'} ;
    $user_id = $obj->{'user_id'};   

    $select = "SELECT * FROM users WHERE password = '".md5($old_password)."' AND id = '".$user_id."'"; //vijayv2205
    $result = mysql_query($select,$con);
    $number_of_rows = mysql_num_rows($result);
    if($number_of_rows > 0){
      $update = "UPDATE users SET password = '".md5($new_password)."'  WHERE id = '".$user_id."'";
      mysql_query($update ,$con);
      $response['success'] = 'Password updated successfully.';
    }else{
      $response['error'] = "You have entered wrong old password.";
    }
  }
  #end of the condition change password
  
  #start of the send push notification for address
  if($action == "push_notification_address"){
    $ids  = $obj->{'ids'} ;
    
    $select = "SELECT * FROM users WHERE `id` IN ($ids)" ;
    $result = mysql_query($select,$con);
    $number_of_rows = mysql_num_rows($result);

    if($number_of_rows > 0){
      while( $row = mysql_fetch_assoc($result)){
            // Put your device token here (without spaces):
            $deviceToken = $row['device_token'];
            // Put your private key's passphrase here:
            $passphrase = '123';
            // Put your alert message here:
            $message = "Location of ".$row['name']." is ". $row['address'];
            
            $ctx = stream_context_create();
         
            if ($production) {
               stream_context_set_option($ctx, 'ssl', 'local_cert', 'imwithyou.pem');
            }else{
               stream_context_set_option($ctx, 'ssl', 'local_cert', 'imwithyou_pem.pem');
            }   
         
            //stream_context_set_option($ctx, 'ssl', 'local_cert', 'imwithyou_pem.pem');
            stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
            
            // Open a connection to the APNS server
            
	    if ($production) {
               $gateway = 'ssl://gateway.push.apple.com:2195';
            } else { 
               $gateway = 'ssl://gateway.sandbox.push.apple.com:2195';
            }	
            $fp = stream_socket_client($gateway , $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);				
            //$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);				
	    	

            if (!$fp){
              $response['error'] = "Unable to send notification";
              exit();
            } 
            // Create the payload body
            $body['aps'] = array(
              'alert' => array(
                    'body' => $message,
                'action-loc-key' => 'I am with you',
                ),
                'badge' => 2,
              'sound' => 'oven.caf',
              'content-available' => 1
              );
            // Encode the payload as JSON
            $payload = json_encode($body);
            // Build the binary notification
            $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
            // Send it to the server
            $r = fwrite($fp, $msg, strlen($msg));

            /*if (!$result){
              $response['error'] = "Message not deliver";
            }
            else{
              $response['success'] = 'Message successfully delivered';
            }*/
            // Close the connection to the server
            fclose($fp);
      }//end of the while loop
      $response['success'] = 'Message successfully delivered';
    }else{
      $response['error'] = "Please enter correct ids";
    }
  }
  #end of the push notification for address	
  
  //update device token
  if($action == 'update_device_token'){
      $user_id  = $obj->{'user_id'} ;
      $device_token = $obj->{'device_token'} ;
      $device_type = $obj->{'device_type'} ;
	
      $update = "UPDATE users SET device_type = '$device_type' , device_token = '$device_token' WHERE id = '$user_id' ";	

      mysql_query($update);
      $response['success'] = 'Device token updated succcessfully';
  }
  #end of update device token
}else{
  $response['error'] = 'Please specify the action';
}#end of the else

function random_password( $length = 8 ) {
$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
$password = substr( str_shuffle( $chars ), 0, $length );
return $password;
}

header('Content-type: application/json');
echo json_encode($response);

?>
