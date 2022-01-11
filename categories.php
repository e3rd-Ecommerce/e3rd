<?php

session_start();
include 'init.php'; 

?>

<div class="container">
    <h1 class="text-center">Show Category Items</h1>
    <div class="row">
    <?php

            // $pageid = isset($_GET['pageid']) && is_numeric($_GET['pageid']) ? intval($_GET['pageid']) : 0;

            if(isset($_GET['pageid']) && is_numeric($_GET['pageid'])){
                $pageid = intval($_GET['pageid']);
                $items = getAllFrom("*","items", "WHERE cat_ID = {$pageid}","AND approve = 1","item_ID");
                foreach($items as $item){

                    $dirname = "imageItems/".$item['image'];
                    if (is_dir($dirname)) {
                        $images = glob($dirname ."/*");
                    }

                    echo '<div class="col-sm-6 col-md-3">';
                        echo '<div class="card mb-2 item-box">';
                            echo '<img src="'.$images[0].'" alt="Thumbnail" class="img-fluid product-thumbnail">';
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
                echo '<div class="alert alert-danger">Palese Select Category or Go back to Home Page</div>';
            }
    ?>
    </div>
</div>
<?php  include $comp . 'footer.php';?>

