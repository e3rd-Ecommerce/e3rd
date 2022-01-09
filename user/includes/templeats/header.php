<?php 
include_once '././init.php' 
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <title> <?php  getTitle() ?>  </title>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo $css; ?>frontend.css">
    <link rel="stylesheet" href="<?php echo $css; ?>all.min.css">
    <link rel="stylesheet" href="<?php echo $css; ?>jquery-ui.css">
    <link rel="stylesheet" href="<?php echo $css; ?>jquery.selectBoxIt.css">



</head>

<body> 
    <div class="upper-bar">
        <div class="container">
            <?php  

                if (isset($_SESSION['user'])) {  ?>
                    <div class="btn-group my-info " >
                        <a href="profile.php">
                    <img class="img-thumbnail rounded-circle"  src="../ecom/layout/images/img_avatar."  alt=""   />
                        </a>
                        <span class="btn btn-outline-info dropdown-toggle" data-bs-toggle="dropdown">
                                <?php echo $sessionuser ?>
                                <span class="caret"></span>
                        </span>
                            <ul class="dropdown-menu">
                                <li> <a href="profile.php">My Profile</a></li>
                                <li><a href="newad.php">New item</a></li>
                                <li><a href="profile.php#my-ads">My items</a></li>
                                <li><a href="logout.php">Logout</a></li>
                            </ul>
                        
                    </div>


<?php /*
                    echo "Welcome <strong> " . $sessionuser . '</strong> '; // من صفحة الinit
                    echo '<a  href="profile.php">My Profile</a> ' . '-';
                    echo '<a href="newad.php"> New item  </a>' ; 
                    echo '<a class="float-end" href="logout.php"> Logout</a>'; 

                    

                    $userStatus =  checkUserStatus($sessionuser) ; 

                    if($userStatus == 1){ // user is not activ

                                            


                    } */
                    
                }   else {
            
            ?>
            <a href="login.php">
                <span class="">login/signup</span>
            </a>
            <?php } ?>
        </div>
    </div>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container">
    <a class="navbar-brand" href="index.php">E3RD</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav-app">
    <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav-app">
    <ul class="navbar-nav ">

    <?php

    $categories = getallfrom("*" , "categories" , "where parent=0" , "" , "id" , "ASC") ; // فنكشن 
    foreach($categories as $cat){
    
        echo '<li class="nav-item">
        <a class="nav-link" href="categories.php?pageid=' . $cat['id'] . '"> 
        '. $cat['name']  . '</a> </li>';
    }

    ?>

    </ul>
    </div>
    </div>
</nav> 