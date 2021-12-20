<?php 

$dsn ='mysql:host=localhost;dbname=e3rd' ; 
$user = 'root' ; 
$pass = ''; 
$option=array(
    PDO::MYSQL_ATTR_INIT_COMMAND =>'SET NAMES utf8', 
);

try{
    $con = new PDO($dsn,$user,$pass,$option);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION) ; 
    // echo 'you are Connected welcome to database'; 
}

catch(PDOException $e){
    echo 'Failed to connect ' . $e->getMessage() ; 
    
}

?>