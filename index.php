<?php 
    
    ob_start();
    session_start();

    $pageTitle = "Hompepage";
    
    include 'init.php';
?>

<div class="container">
    <h1 class="text-center">Home</h1>
    <div class="row">
    <?php

        $items = getAllFrom('*','items','WHERE approve = 1','','item_ID');

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
    
    ?>
    </div>
</div>

<?php
    
    require_once $comp . "footer.php"; 
    ob_end_flush();
?>