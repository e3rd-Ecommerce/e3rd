
<?php 
ob_start();

session_start();
$pageTitle = 'homepage' ; 
include_once 'init.php' ; ?>

<!--  -->
<div class="container">
    <div class="row">
    <?php 
    $alladd = getallfrom('*', 'items','WHERE approve = 1' , '' , 'item_id'  ) ; // فنكشن 
    foreach($alladd as $item){
        echo  '<div class="col-sm-6 col-md-3">'; 
            echo '<div class="card item-box">'; 
            echo '<span class="price-tag" >  '.$item['price'].' </span>' ; 
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




<!--  -->
<?php
include_once $tpl . 'footer.php' ; 
ob_end_flush();
?>

