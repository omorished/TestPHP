
<?php


class access {

//connection virables
var $host = null;
var $username = null;
var $password = null;
var $name = null;

private $connection = null;

//constructing class
function __construct($dbhost,$dbusernam,$dbpassword,$dbname) {

  $this->host = $dbhost;
  $this->username = $dbusernam;
  $this->password = $dbpassword;
  $this->name = $dbname;

  //echo $dbhost , $dbusernam , $dbpassword , $dbname;
}

//connection function
public function connect() {

  //conncetion to the db
  $this->connection = new mysqli($this->host,$this->username,$this->password,$this->name);

  //if there is an error while connecting to the db
  if(mysqli_connect_errno()) {

    echo "error connection to the db";
  }
  else {
   //echo "<br> db connected <br>";
 }

  //supporting all languages
  $this->connection->set_charset("utf8");
}

//disconnection function
public function disconnect() {

  if ($this->connection != null) {
    $this->connection->close();
  }

}

//register user
public function registerUser($username,$password,$salt,$email,$firstName,$lastName) {
 
 //sql command
 $sql = "INSERT INTO users SET username=?, password=?, salt=?, email=?, firstName=?, lastName=?";
 
 //prepare sql query and store result
 $statment = $this->connection->prepare($sql);
 
 //if error
 if (! $statment) {
  throw new Exception($statement->error);
 }
 
 //bind 5 params of tyoe String into sql command
 //s means string and we have six strings parameters to be insert, if you have int you put i and so on
 $statment->bind_param("ssssss",$username,$password,$salt,$email,$firstName,$lastName);
 
 $excutionResult = $statment->execute();
 
 return $excutionResult;
 
}

//select user information
public function getUserInformation($passedUsername) {

 //sql select command
 $sql = "SELECT * FROM Twitter.users WHERE username = '$passedUsername'";

 //result of selection
$result = $this->connection->query($sql);

// if we have at least 1 result returned
if($result != null &&  mysqli_num_rows($result) >= 1) {
 
 //assign result to assciative array
 $row = $result->fetch_array(MYSQLI_ASSOC);

   if(!empty($row)) {
    return $row;
  			}
 
		}

  return ""; 
   }
   
}
   

 ?>

