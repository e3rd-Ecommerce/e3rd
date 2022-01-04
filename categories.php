

<?php
ob_start();
session_start();
include_once 'init.php' ; 

$pageTitle= "category" ; 
?>



<div class="container">
    <h1 class="text-center">Show Category</h1>
    <div class="row">
    <?php 

    $items = getitem('cat_id',$_GET['pageid']) ; // فنكنشن بجيب الايتيم بمحلها حسب الاي دي وبحطها بالكاتيغوري
    foreach($items as $item){
        echo  '<div class="col-sm-6 col-md-3">'; 
            echo '<div class="card item-box">'; 
            echo '<span class="price-tag" > '.$item['price'].' </span>' ; 
                echo '<img class="img-img-fluid" src="../ecom/layout/images/img_avatar.png"  alt=""  />' ;
                echo '<div class="caption" >' ;   
                echo '<h3> <a href="items.php?itemid='.$item['item_id'].'">  ' . $item['name'] . ' </a> </h3>'; 
                echo '<p>' . $item['description'].  '</p>';
                echo '<div class="date">' . $item['add_date']. '</div>'  ;
                echo '</div>' ; 
            echo '</div>' ;
        echo '</div>' ;
    }
    ?>
    </div>
    
</div>





<?php include_once $tpl .  'footer.php' ;
ob_end_flush();
?>