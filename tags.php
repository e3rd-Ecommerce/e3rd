<?php

session_start();
include 'init.php'; 

?>

<div class="container">
    <div class="row">
    <?php
            if(isset($_GET['pagename'])){
                $pageName = $_GET['pagename'];
                echo '<h1 class="text-center">'. $pageName.'</h1>';

                $items = getAllFrom("*","items", "WHERE tags LIKE '%{$pageName}%' ","AND approve = 1","item_ID");
                foreach($items as $item){

                    echo '<div class="col-sm-6 col-md-3">';
                        echo '<div class="card mb-2 item-box">';
                            echo '<img src="OIP.png" alt="Avatar" class="img-fluid"/>';
                            echo '<div class="card-body">';
                                    echo '<span class="price-tag">'. $item['price'] .'JOD</span>';
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
                $pageid = 0;
                echo '<div class="alert alert-danger">Palese Select Tag name or Go back to Home Page</div>';
            }
    ?>
    </div>
</div>
<?php  include $comp . 'footer.php';?>

