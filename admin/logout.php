<?php 

    //  start the session 
    
    session_start(); 

    session_unset(); // بحذف البيانات

    session_destroy(); // بحطم السيشون

    header('location: index.php'); 
    exit(); 