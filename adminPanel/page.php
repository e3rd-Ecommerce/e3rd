<?php 

    /*
        Categories => [Manage | Edit | Update | Add | Insert | Delete | Stats]
        هاي يعني كل كاتيجوري الها هاي الصفحات راح تكون ف بدل ما اعمل الف صفحه عندي
        لا بعملها بالجيت وبصير بناءا على متغير الدو بالجيت اجيب الصفحه اللي محتاجها
    */

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage' ;

    // If The Page Is main Page

    if($do == 'Manage'){
        echo 'Welcome Manage Category';
        echo '<a href="page.php?do=Add"> Add New Category </a>';
    } elseif ($do == 'Add'){
        echo 'Welcome You are In Add Category Page';
    } elseif ($do == 'Insert'){
        echo 'Welcome You are In Insrt Category Page';
    } else {
        echo 'Error There\'s No Page With this Name';
    }
?>