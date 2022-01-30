<?php 

require_once "includes/functions/functions.php";
require_once 'adminPanel/connect.php';

if ($_POST['search_text'] == '' && $_POST['cat_id'] > 0) { ?>
    <div class="container">
            <div class="row">

            <?php 
                    
                        $pageid = intval($_POST['cat_id']);

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

                            
                        } ?>
            </div>
        </div>
        
        


<?php }elseif ($_POST['search_text'] == '' && $_POST['cat_id'] == 0) { ?>
    <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title-all text-center">
                        <h1>ALL Prodect</h1>
                    </div>
                </div>
            </div>
            
            <div class="row special-list">

            <?php
                $items = getAllFrom('*','items','WHERE approve = 1','','item_ID'); 
                foreach($items as $item){
                    $dirname = "imageItems/".$item['image'];
                    if (is_dir($dirname)) {
                        $images = glob($dirname ."/*");
                    } 
                ?>
                <div class="col-lg-3 col-md-6 special-grid ">
                    <div class="products-single fix">
                        <div class="box-img-hover">
                            <div class="type-lb">
                                <p class="sale">New</p>
                            </div>
                            <img src="<?php echo $images[0]; ?>" class="img-fluid" alt="Image">
                            <div class="mask-icon">
                            </div>
                        </div>
                        <div class="why-text">
                            <?php  echo '<a href="item.php?itemid=' . $item['item_ID'] .'"><h4>'. $item['name'] .'</h4></a>'; ?>
                            <h5> <?php echo $item['price'] ;?> </h5>
                        </div>
                    </div>
                </div>
            <?php } ?>
            </div>
    </div>






<?php }elseif ($_POST['search_text']) {

    $cat_id = $_POST['cat_id'];
    $search = $_POST['search_text'];
    if ($cat_id == 0) {
        $statement = $con->prepare("SELECT * FROM items WHERE approve = 1 AND name LIKE '%$search%' ");
        $statement->execute();
        $items = $statement->fetchAll();
    }else{
        $statement = $con->prepare("SELECT * FROM items WHERE approve = 1  AND cat_ID = ? AND name LIKE '%$search%' ");
        $statement->execute(array($cat_id));
        $items = $statement->fetchAll();
    }
    
    if (! empty($items)) { ?>
            <div class="container">
            <div class="row">

            <?php       
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

                            
                        } ?>

            </div>
        </div>
    <?php }

    }else{ 


       echo '<div class="container">';  
       echo '<div class="row">';    
       echo '<h2 style="width: 100%; text-align: center;"> No Thing :( </h2>';        
       echo '</div>';    
       echo '</div>'; 


    }
    
    
    ?>

        
