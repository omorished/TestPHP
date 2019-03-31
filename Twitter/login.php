<?php


//STEP 1: Check virables passing to this file via GET or POST
$username = htmlentities($_REQUEST['username']);
$password = htmlentities($_REQUEST['password']);


if(empty($username) || empty($password)) {
    
    $returnArray['status'] = '400';
    $returnArray['message'] = 'Messing required information';
    echo json_encode($returnArray);
    return;
    
}

//STEP 2: Build connection
//secure way to build connection
$file = parse_ini_file("/Applications/XAMPP/Twitter.ini");

//store connection variables from Twitter.ini
$host = trim($file["dbhost"]);
$user = trim($file["dbuser"]);
$pass = trim($file["dbpass"]);
$name = trim($file["dbname"]);

//to access functions in access.php
require("secure/access.php");

$access = new access($host,$user,$pass,$name);
$access->connect();


//STEP 3: get user info
// if we didn't get any user
$user = $access->getUserInformation($username);

if(empty($user)) {
     $returnArray['status'] = '403';
     $returnArray['message'] = 'user not found';
    echo json_encode($returnArray);
    return;
}

    
    //STEP 4: check validaity of password
    //get password and salt from db
  
  $secured_password = $user['password'];
  $salt = $user['salt'];
  
  //check passwords match from db & entered one
  if($secured_password == sha1($password . $salt)) {
    
       $returnArray['status'] = '200';
       $returnArray['message'] = 'Loged in successfully';
       
        $returnArray["id"] = $user["id"];
        $returnArray["username"] = $user["username"];
        $returnArray["email"] = $user["email"];
        $returnArray["firstName"] = $user["firstName"];
        $returnArray["lastName"] = $user["lastName"];
        $returnArray["ava"] = $user["ava"];
          
  }
        
  else {
  
  $returnArray['status'] = '400';
  $returnArray['message'] = 'Password did not match';

 }
    

//STEP 5: close connection
  $access->disconnect();

//STEP 6:Throw back all information
echo json_encode($returnArray);




?>