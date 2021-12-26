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
                        <i class="fa fa-users"></i>
                        <div class="info">
                            Total Members
                            <span>
                                <a href="members.php">
                                    <?php echo countItems('userID' , 'users');?>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-pending">
                        <i class="fa fa-user-plus"></i>
                        <div class="info">
                            Pending Members
                            <span>
                                <a href="members.php?do=Manage&page=Pending">
                                    <?php echo checkItem("RegStatus", "users", 0); ?>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-items">
                        <i class="fa fa-tag"></i>
                        <div class="info">
                            Total Items
                            <span>
                            <a href="items.php?do=Manage">
                                <?php echo countItems('item_ID' , 'items');?>
                            </a>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-comments">
                      <i class="fa fa-comments"></i>
                      <div class="info">
                            Total Comments
                            <span>
                            <a href="comments.php?do=Manage">
                                <?php echo countItems('c_ID' , 'comments');?>
                            </a>
                            </span>
                      </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Start Latest comments and items and users on my website -->
        <div class="container latest">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card my-3" >
                        <?php $numUsers = 4; ?>
                        <div class="card-header">
                            <i class="fa fa-users"></i> Latest <?php echo $numUsers ?> Registers Users
                            <span class="toggle-info float-end">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>    
                        </div>

                        <div class="card-body">
                            <?php 
                                $theLatestUsers = getLatest("*", "users", "userID", $numUsers,'notadmin'); //Latest user Array
                                echo '<ul class="list-unstyled latest-users">';

                                if(!empty($theLatestUsers)){
                                    foreach($theLatestUsers as $user){
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
                                } else{

                                    $theMsg='<div class="nice-message">Theres No Users To show here</div>';
                                    echo '<div class="container mt-3 text-center">';
                                        echo $theMsg;
                                    echo '</div>';                                
                                }
                                echo '</ul>';
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card my-3" >
                        <?php $numItems = 4; ?>
                        <div class="card-header">
                            <i class="fa fa-tag"></i> Latest <?php echo $numItems ?> Approved items
                            <span class="toggle-info float-end">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>    
                        </div>

                        <div class="card-body">
                            <?php 
                                $theLatestItems = getLatest("*","items","item_ID",$numItems);
                                echo '<ul class="list-unstyled latest-users">';

                                if(!empty($theLatestItems)){
                                        foreach($theLatestItems as $item){
                                            echo '<li>' ;
                                                echo $item['name'];  
                                                    echo '<a href="items.php?do=Edit&itemid='. $item['item_ID'] .'">';
                                                        echo '<span class="btn btn-success float-end">';
                                                            echo '<i class="fa fa-edit"></i> Edit';
                                                        echo '</span>';
                                                echo '</a>';
                                                if ($item['approve'] == 0){
                                                        echo '<a href="items.php?do=Approve&itemid='. $item['item_ID'] .'">';
                                                            echo '<span class="btn btn-info float-end">';
                                                                echo '<i class="fas fa-check"></i> Approve';
                                                            echo '</span>';
                                                        echo '</a>'; 
                                                    }         
                                                echo '</li>';
                                  }
                                } else{
                                    $theMsg='<div class="nice-message">Theres No Items To show here</div>';
                                    echo '<div class="container mt-3 text-center">';
                                        echo $theMsg;
                                    echo '</div>';         
                                  }
                            echo '</ul>';
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="card my-3" >
                        <?php $numComments = 4; ?>
                        <div class="card-header">
                            <i class="fa fa-comments"></i> Latest <?php echo $numComments; ?> Comments
                            <span class="toggle-info float-end">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>    
                        </div>

                        <div class="card-body">
                        <?php

                            //Select All Comments 
                            $stmt = $con->prepare("SELECT
                            comments.*,
                                users.UserName As member_name
                            FROM 
                                comments
                            INNER JOIN 
                                users 
                            ON 
                                users.userID = comments.user_ID
                            ORDER BY
                                  c_ID DESC
                            LIMIT $numComments
                            ");
                            $stmt->execute();
                            $comments = $stmt->fetchAll();
                            if(!empty($comments)){
                                foreach($comments as $comment){
                                    echo '<a href="comments.php?do=Custom&comid=' .$comment['c_ID']. '" class="edit-comment">';
                                        echo '<div class="comment-box">';
                                            echo '<span class="member-n">'.$comment['member_name'] . '</span>';
                                            echo '<p class="member-c">'.$comment['comment'] . '</p>';
                                        echo '</div>';
                                    echo '</a>';
                                }
                            } else {
                                $theMsg='<div class="nice-message">Theres No Comments To show here</div>';
                                    echo '<div class="container mt-3 text-center">';
                                        echo $theMsg;
                                    echo '</div>';         
                            }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Start Latest comments and items and users on my website -->


        <?php
        /* End DashBoard Content Here */

        include $comp . 'footer.php';

    } else {
        header('location:index.php');
        exit();
    }
    

ob_end_flush(); // بعد هالفنكشن بيعرضلي كل اشي تخزن بالبفر

?>
