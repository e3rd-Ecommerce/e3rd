<?php

session_start();
    if(isset($_SESSION['Username'])){ //هون بشيك اذا فيه سشين موجوده ولا لأ
        
        $pageTitle = 'Dashboard';
        
        include 'init.php';
          echo "Body";
        include $comp . 'footer.php';

    } else {
        header('location:index.php');
        exit();
    }
    