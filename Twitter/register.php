


<?php

//STEP 1: Declare parameters of user info
//secure information
$username = htmlentities($_REQUEST['username']);
$password = htmlentities($_REQUEST['password']);
$email = htmlentities($_REQUEST['email']);
$firstName = htmlentities($_REQUEST['firstName']);
$lastName = htmlentities($_REQUEST['lastName']);

//check if parameter empty
if(empty($username) || empty($password) || empty($email) || empty($firstName) || empty($lastName)) {

  $returnArray['status'] = '400';
  $returnArray['message'] = 'Missing required information';

  echo json_encode($returnArray);


}

//STEP 2: Securing password
$salt = openssl_random_pseudo_bytes(20); //auto generated of 20 char

$secured_password = sha1($password . $salt);


//STEP 3: Build connection
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


//STEP 4: Insert user information (register user)
//echo "the username " . $username . "<br>";
//echo "secured password " . $secured_password . "<br>";
//echo "password " .$password . "<br>";
//echo "email " . $email . "<br>";
//echo "first name " . $firstName . "<br>";
//echo "last name" . $lastName . "<br>";

$result = $access->registerUser($username,$secured_password,$salt,$email,$firstName,$lastName);

 if($result) {
  
          $user = $access->getUserInformation($username);

          $returnArray["status"] = "200";
          $returnArray["message"] = "Successfully registerd user";
          
          $returnArray["id"] = $user["id"];
          $returnArray["username"] = $user["username"];
          $returnArray["email"] = $user["email"];
          $returnArray["firstName"] = $user["firstName"];
          $returnArray["lastName"] = $user["lastName"];
          $returnArray["ava"] = $user["ava"];
          
          echo json_encode($returnArray);
  
 } else {
  
  $returnArray['status'] = '400';
  $returnArray['message'] = 'could not register with provided information';
  echo json_encode($returnArray);
 }
 
 
 //STEP 5: close connection
 $access->disconnect();
 ?>

