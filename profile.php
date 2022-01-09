<?php 
    session_start();

    $pageTitle = "Profile";
    
    include 'init.php';

    if(isset($_SESSION['user'])){

        $getUser = $con->prepare("SELECT * FROM users WHERE UserName = ?");

        $getUser ->execute(array($session_user));

        $info = $getUser->fetch(); //Fetch This Row

        $userid = $info['userID']
?>

<h2 class="text-center text-muted m-3"><?php echo $_SESSION['user'].'\'s ';?>Profile</h2>
<div class="information block">
    <div class="container">
        <div class="card">
            <div class="card-header bg-info  text-light fw-bold">
                My information
            </div>

            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <i class="fas fa-id-card text-muted"></i>
                        <span class="fw-bold text-muted">Full name</span> : <?php echo $info['FullName'] . '</br>';  ?>
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-unlock-alt fa-fw text-muted"></i>
                        <span class="fw-bold text-muted">Login username</span> : <?php echo $info['UserName'] . '</br>';  ?>
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-envelope-square text-muted"></i>
                        <span class="fw-bold text-muted">Email</span> : <?php echo $info['Email'] . '</br>';  ?>
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-table text-muted"></i>
                        <span class="fw-bold text-muted">Register date</span> : <?php echo $info['regDate'] . '</br>';  ?>
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-heart text-muted"></i>
                        <span class="fw-bold text-muted">Favourite Category </span>:
                    </li>
                </ul>
                <a href="#" class="btn my-2">Edit Infromation</a>
            </div>
        </div>
    </div>
</div>

<div id="my-ads" class="my-products block">
    <div class="container">
        <div class="card">
            <div class="card-header bg-info  text-light fw-bold">
                My Pdroducts
            </div>

            <div class="card-body">
                <div class="row">
                <?php

                    $items = getAllFrom("*", "items", "WHERE member_ID = $userid", "", "item_ID");
                    if(!empty($items)){
                        foreach($items as $item){

                            echo '<div class="col-sm-4 col-md-2">';
                                echo '<div class="card mb-2 item-box">';
                                    if($item['approve'] == 0){
                                        echo '<span class="approve-tag">Waiting Approval</span>';
                                    }
                                    echo '<img src="OIP.png" alt="Avatar" class="img-fluid"/>';
                                    echo '<div class="card-body">';
                                            echo '<span class="price-tag">'. $item['price'] ."JOD".'</span>';
                                            echo '<a href="item.php?itemid=' . $item['item_ID'] .'"><h3 class="card-title">'. $item['name'] .'</h3></a>';
                                            echo '<p class="card-text ">';
                                             echo $item['description'];
                                            echo '</p>';
                                            echo '<div class="date">';
                                             echo $item['added_date'];
                                            echo '</div>';
                                    echo '</div>';
                                echo '  </div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div>There\'s No Products to show, <a style="color:blue;"href="newad.php">Add new Produt</a></div>';
                    }
                ?>      
                </div>
        </div>
    </div>
</div>

<!--Comment Section-->
<div id="my-coms" class="my-comments block">
    <div class="container">
        <div class="card">
            <div class="card-header bg-info  text-light fw-bold">
                Latest Comments
            </div>

            <div class="card-body">
                <div class="row">

                    <?php

                        $comments = getAllFrom("comment", "comments", "WHERE user_ID = $userid", "", "c_ID");

                        if(!empty($comments)){

                            foreach($comments as $comment){
                                echo '<p>'.$comment['comment'].'</p>'; 
                            }

                        } else {
                            echo 'There\'s No Comments to show';
                        }

                    ?>
                </div>
            </div>
        </div>
    </div>
</div>    
<?php
    } else {
        header('Location:login.php');
    }

require_once $comp . "footer.php"; ?>