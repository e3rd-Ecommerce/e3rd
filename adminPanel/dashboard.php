<?php

ob_start(); // Output Buffering Start (:يعني بعد هالفنكشن ابدأ تخزين كل شيء يالصفحه باستثناء الهيدر)
            //  ob_start , ob_end -> let you control your output data

session_start();
    if(isset($_SESSION['Username'])){ //هون بشيك اذا فيه سشين موجوده ولا لأ
        
        $pageTitle = 'Dashboard';
        
        include 'init.php';
        /*Start DashBoard Content Here */


        ?>
        <div class="container home-stats text-center">
            <h1>Dashboard</h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="stat st-members">
                        Total Members
                        <span><a href="members.php"><?php echo countItems('userID' , 'users');?></a></span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-pending">
                        Pending Members
                        <span><a href="members.php?do=Manage&page=Pending">
                            <?php echo checkItem("RegStatus", "users", 0); ?>
                        </a></span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-items">
                        Total Items
                        <span>1500</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-comments">
                        Total Comments
                        <span>3500</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="container latest">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card" >
                        <?php $latestUsers = 4; ?>
                        <div class="card-header">
                            <i class="fa fa-users"></i> Latest <?php echo $latestUsers ?> Registers Users
                        </div>

                        <div class="card-body">
                            <?php 
                                $theLatest = getLatest("*", "users", "userID", $latestUsers); //Latest user Array
                                echo '<ul class="list-unstyled latest-users">';
                                    foreach($theLatest as $user){
                                        echo '<li>' ;
                                            echo $user['UserName'];  
                                                echo '<a href="members.php?do=Edit&userid='. $user['userID'] .'">';
                                                    echo '<span class="btn btn-success float-end">';
                                                        echo '<i class="fa fa-edit"></i> Edit';
                                                    echo '</span>';
                                               echo '</a>';
                                               if ($user['RegStatus'] == 0){
                                                    echo '<a href="members.php?do=Activate&userid='. $user['userID'] .'">';
                                                        echo '<span class="btn btn-info float-end">';
                                                            echo '<i class="fas fa-thumbs-up"></i> Activate';
                                                        echo '</span>';
                                                    echo '</a>'; 
                                                }         
                                            echo '</li>';
                                    }
                                echo '</ul>';
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card" >
                        <div class="card-header">
                            <i class="fa fa-tag"></i> Latest Items
                        </div>

                        <div class="card-body">
                                Some quick example text to build on the card title and make up the bulk of the card's content.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        /* End DashBoard Content Here */

        include $comp . 'footer.php';

    } else {
        header('location:index.php');
        exit();
    }
    

ob_end_flush(); // بعد هالفنكشن بيعرضلي كل اشي تخزن بالبفر

?>
