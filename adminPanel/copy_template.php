<?php
/*
=======================================
== Template Page
=======================================
*/

ob_start(); //Output Buffering Start

session_start();

$pageTitel = '';

if(isset($_SESSION['Username'])){

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if($do == 'Manage'){
        echo "Welcome";
    } elseif ($do == 'Add'){

    } elseif ($do = "Insert"){

    } elseif ($do == 'Edit') {
        
    } elseif ($do == 'Update') {
        
    } elseif ($do == 'Delete'){

    } elseif ($do == 'Activate'){

    }

    require_once $comp . 'footer.php';

}else {
     
    header('location: index.php');

    exit();
}

ob_end_flush(); // Release The Output

?>