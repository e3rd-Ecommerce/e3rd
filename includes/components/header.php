<?php require_once "init.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php printTitle(); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?php echo $css; ?>jquery-ui.css" />
    <link rel="stylesheet" href="<?php echo $css; ?>jquery.selectBoxIt.css" />
    <link rel="stylesheet" href="<?php echo $css; ?>front.css" />
</head>
<body>

        <div class="upper-bar">
            <div class="container">
                <?php 
                    if(isset($_SESSION['user'])){ ?>

                        <div class="dropdown my-info text-right float-end">
                            <?php 
                                $stmt = $con->prepare("SELECT 
                                                            avatar
                                                      FROM 
                                                            users 
                                                      WHERE
                                                           UserName = ?
                                            ");

                                $stmt->execute(array($_SESSION['user']));

                                $userInfo = $stmt->fetch();

                                echo "<img src='images/". $userInfo['avatar']."' alt='Item Thumbnail' class='product-thumbnail'>";
                            ?>
                            <button class="btn btn-info dropdown-toggle text-light" type="button" id="nav-toggle" data-bs-toggle="dropdown" >
                                <?php echo $session_user; ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="nav-toggle">
                                <li><a class="dropdown-item" href="profile.php">My Profile</a></li> 
                                <li><a class="dropdown-item" href="newad.php">New Product</a></li>
                                <li><a class="dropdown-item" href="profile.php#my-ads">My Products</a></li>
                                <li><a class="dropdown-item" href="profile.php#my-coms">My Comments</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </div>
                       
                        <?php

                        
                        // $userStatus = checkUserStatus($session_user);
                        
                        // if($userStatus == 1){
                        //     echo 'Your memebership need To activate By Admin';
                        // }
                        
                    } else {
                        echo '<a href="login.php" class="float-end">';
                            echo '<span>Login | Signup</span>';
                        echo '</a>'; 
                    }
                ?>
            </div>   
        </div>
        <div class="clear-fix"></div>
          <!--Start navBar-->
          <nav class="navbar navbar-expand-lg bg-dark navbar-dark py-3">
            <div class="container">
                <a href="index.php" class="navbar-brand">Homepage</a>

                <button 
                    class="navbar-toggler"
                    type="button" 
                    data-bs-toggle="collapse"
                    data-bs-target="#app-nav"
                >

                <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="app-nav">
                    <ul class="navbar-nav ms-auto">

                        <li class="nav-item ">
                            <div class="cart-container">
                                <a class="nav-link" href="cart.php" class="cart-link">
                                    <i class="fas fa-shopping-cart cart-icon"></i>
                                    <span id="cart" class=" cart-counter">
                                        <?php
                                            $count =0;
                                            if ($count == 0) echo $count;
                                        ?>
                                    </span>
                                </a>
                            <div>
                        </li>
                        <?php

                            $categories = getAllFrom("*", "categories" ,"WHERE parent = 0", "", "ID", "ASC");

                            foreach($categories as $cat){

                                echo '<li class="nav-item"> 
                                            <a href="categories.php?pageid='.$cat['ID'].'" class="nav-link">
                                                '.$cat['name'].'
                                            </a>
                                      </li>';
                            }
                        
                        ?>
                        
                    </ul>
                </div>
            </div>
        </nav>