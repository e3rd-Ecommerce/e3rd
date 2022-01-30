<?php

session_start();
include 'init.php'; 
if(isset($_GET['pageid']) && is_numeric($_GET['pageid'])){
    $pageid = intval($_GET['pageid']);
    $name_Cat = getAllFrom("name","categories", "WHERE ID = {$pageid}","","ID");
}
?>


   <!-- Start All Title Box -->
   <div class="all-title-box">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2><?php
                    foreach ($name_Cat as $key => $value) {
                        if (isset($value['name'])) {
                            echo $value['name'];
                        }else{
                            echo "Shop";
                        }
                    } ?>
                    </h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active">Shop</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<!-- End All Title Box -->


<!-- ******* -->

    <!-- Start Categories  -->
    <div class="categories-shop" id="output">
        <div class="container">
            <div class="row">

            <?php 
                    if(isset($_GET['pageid']) && is_numeric($_GET['pageid'])){
                        //$pageid = intval($_GET['pageid']);

                        $SubCat = getAllFrom("ID","categories", "WHERE parent = {$pageid}","","ID");
                        $newarray = [];
                        foreach ($SubCat as $key => $value) {
                            $newarray = (int)$value['ID'] ;
                        }
                        if (! empty($newarray)) {
                            $items = getAllFrom("*","items", "WHERE cat_ID = {$pageid}","AND approve = 1 OR cat_ID IN ($newarray)","item_ID");
                        }else{
                            $items = getAllFrom("*","items", "WHERE cat_ID = {$pageid}","AND approve = 1","item_ID");
                        }
                        
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
                    }
                        else {
                            $pageid = 0;
                            echo '<div class="alert alert-danger">Palese Select Category or Go back to Home Page</div>';
                        }
                            ?>

            </div>
        </div>
    </div>
    <!-- End Categories -->

<!-- ******* -->
<p>
    
    <?php
    $actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $actual_link = substr($actual_link, -10);
    $cat_id = (int) filter_var($actual_link, FILTER_SANITIZE_NUMBER_INT); 
    ?>
    <input type="hidden" name="cat_id" value="<?=$cat_id?>" id="cat_id">
</p>


<?php  include $comp . 'footer.php';?>

