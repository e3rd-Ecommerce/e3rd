<?php 

    require_once 'connect.php';

    //Routes
    $comp = "includes/components/"; //component Directiory
    $lang = "includes/languages/"; //language file directiory
    $func = 'includes/functions/' ; //Funcion Directory
    $css = "mainDesign/css/"; //css Directiory
    $js = "mainDesign/js/"; //Js Directiory

    //Include THe Important Files
    require_once $func . 'functions.php';
    require_once $lang . 'english.php'; //طبعا الصح اني استدعي ملف اللغات قبل كل الملفات هيك تجنبا لحدوث اخطاء بالويبسايت
    require_once $comp . 'header.php';

    //include Navbar On All Page Expect The One With $noNavBar Variable
   if(!isset($noNavBar)){ require_once $comp . "navbar.php";}
?>