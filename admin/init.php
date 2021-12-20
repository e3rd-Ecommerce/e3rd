<?php 

include_once 'connect.php' ; 

// routes 

    $tpl = 'includes/templeats/' ;  // مسار التيمبليت 
    $languages = 'includes/languages/'; // مسار اللغة 
    $func = 'includes/functions/' ; //مسار الفنكشن 
    $css = 'layout/css/' ; // /مسار الcss 
    $js = "layout/js/"; //Js Directiory


    // انكلود للملفات المهمه 
    include_once $languages . 'english.php' ;
    include_once $func . 'function.php';

    include  $tpl . 'header.php' ; 


    // بكل  انكلود ناف بار الصفحات ما عدا الصفحات الفيها الفيريبل نوناف بار 

    if(!isset($NOnavbar)) { // لو فش بالصفحة فيريبل النونافبار اعمل ناف بار 
        include_once $tpl .'navbar.php' ;
    }




