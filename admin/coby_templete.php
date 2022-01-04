<?php 
// templete page
ob_start(); 
session_start() ; 

$pageTitle= '' ; 


if (isset($_SESSION['username'])) {

    include_once 'init.php'; 

    $do= '' ; 

    if(isset($_GET['do'])) {

        $do=  $_GET['do']; 

    }else  {
        $do ='manage' ; // الصفحة الرئيسية

    }

    if($do == 'manage'){

        echo 'welcome' ; 

    } elseif ($do == 'add') {

        
    }elseif ($do == 'insert') {

        
    }elseif ($do == 'edit') {

        
    }elseif ($do == 'update') {
        

    }elseif ($do == 'delete') {

        
    }elseif ($do == 'activate') {

        
    } 

    include_once $tpl . 'footer.php' ; 

} 
        else { 

    // echo 'you are not authorized to view this page  ' ; 
    header('location: index.php') ;
    exit(); 

        }
