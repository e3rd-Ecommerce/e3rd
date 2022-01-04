<?php 

    //  start the session 
    ob_start();

    session_start(); 

    session_unset(); // بحذف البيانات

    session_destroy(); // بحطم السيشون

    header('location: index.php'); 
    exit(); 

    ob_end_flush();