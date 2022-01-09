<?php 

// ERORR reporting 

ini_set('display_errors' , 'on') ; 
error_reporting(E_ALL); 


include_once '../admin/connect.php' ; 

$sessionuser= '';

if(isset($_SESSION['user'])){
    $sessionuser = $_SESSION['user'] ; 
}

// routes 

    $tpl = 'includes/templeats/' ;  // مسار التيمبليت 
    $languages = 'includes/languages/'; // مسار اللغة 
    $func = 'includes/functions/' ; //مسار الفنكشن 
    $css = 'layout/css/' ; // /مسار الcss 
    $js = "layout/js/"; //Js Directiory

    // انكلود للملفات المهمه 
    include_once $languages .'english.php' ;
    include_once $func . 'function.php';
    include_once  $tpl . 'header.php' ; 



    
    




