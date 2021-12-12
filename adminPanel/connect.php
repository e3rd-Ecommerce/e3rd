<?php 

    $dsn = 'mysql:host=localhost;dbname=shop'; //Data Source Name
    $user = 'root';
    $pass = '';
    $option = array (
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
    );

    try {
        $con = new PDO($dsn , $user, $pass, $option);
        $con -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } 
    catch(PDOExeption $e) {
        echo "Failed To connect" . $e->getMessage();
    }

?>