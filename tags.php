<?php

session_start();
include 'init.php'; 


?>

<!-- Start All Title Box -->
    <div class="all-title-box">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2><?php echo $_GET['pagename']; ?></h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="profile.php">Profile</a></li>
                        <li class="breadcrumb-item active">tags</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- End All Title Box -->


    <div class="categories-shop" id="output">
        <div class="container">
            <div class="row">
    <?php
            if(isset($_GET['pagename'])){
                $pageName = $_GET['pagename']; 

                $items = getAllFrom("*","items", "WHERE tags LIKE '%{$pageName}%' ","AND approve = 1","item_ID");
                
                foreach($items as $item){
        
                    $dirname = "imageItems/".$item['image'];
                    if (is_dir($dirname)) {
                        $images = glob($dirname ."/*");
                    } 
                
                    echo '<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">';
                    echo '<div class="shop-cat-box">';
                    echo' <div class="type-lb">';
                    echo'   <p class="sale">'.$item['price'].' $</p>';
                    echo '   </div>';
                    echo '<img class="img-fluid" src="'.$images[0].'" alt="" />'; 
                    echo '<a class="btn hvr-hover" href="item.php?itemid=' . $item['item_ID'] .'">'. $item['name'] .'</a>';
                    echo '</div>';
                    echo '</div>';

                } 

            } else {
                $pageid = 0;
                echo '<div class="alert alert-danger">Palese Select Tag name or Go back to Home Page</div>';
            }
    ?>
            </div>
        </div>
    </div>


<?php  include $comp . 'footer.php';?>

