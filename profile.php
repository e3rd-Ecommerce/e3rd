
<?php 
ob_start();
session_start();
$pageTitle = 'profile' ; 
include_once 'init.php' ;
if(isset ($_SESSION['user'])){ 

    $getuser = $con->prepare("SELECT * FROM users WHERE username= ? ");

    $getuser->execute(array($sessionuser));

    $info = $getuser->fetch(); 


?>

<h1 class="text-center">my profile</h1>

    <div class="information block">
        <div class="container">
            <div class="card">
                <div class="card-heading bg-info text-white ">My information</div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li>

                            <i class="fa fa-unlock-alt fa-fw"></i> 
                            <span> login name </span>:  <?php echo $info['username']; ?>   </li>
                        <li> 
                            <i class="fa fa-envelope"></i>                            
                            <span>Email </span>:   <?php echo $info['email'] ; ?>  </li>
                        <li> 
                            <i class="fa fa-user fa-fw"></i>
                            <span>fullname </span>:  <?php echo $info['fullname'];  ?> </li>
                        <li>
                            <i class="fa fa-calendar fa-fw"></i>
                            <span>register date  </span>:  <?php echo $info['date']; ?></li>
                        <li>
                            <i class="fa fa-tags"></i>
                            <span>fav category</span> :   </li>
                    </ul>
                    <a href="#" class="btn btn-info">Edit information </a>

                    </div>
            </div>
        </div>
    </div> 

    <div id="my-ads" class="my-ads block">
        <div class="container">
            <div class="card">
                <div class="card-heading bg-info text-white ">latest add</div>
                <div class="card-body">
                    <!--  -->
                        <div class="row">
                            <?php 

                            $items = getitem('member_id',$info['userid'],1) ;  
                        if(!empty($items)){
                            foreach($items as $item){

                                echo  '<div class="col-sm-6 col-md-3">'; 
                                    echo '<div class="card item-box">'; 
                                    if($item['approve'] == 0) {echo 
                                        ' <span class="approve-status"> Wating Approval </span>' ; }
                                    echo '<span class="price-tag" >  '.$item['price'].' </span>' ; 
                                        echo '<img class="img-img-fluid" src="../ecom/layout/images/img_avatar.png"  alt=""  />' ;
                                        echo '<div class="caption" >' ;   
                                        echo '<h3> <a href="items.php?itemid='.$item['item_id'].'">' . $item['name'] . '</a> </h3>' ; 
                                        echo '<p>' . $item['description'].  '</p>'  ;
                                        echo '<div class="date">' . $item['add_date'].  '</div>'  ;
                                        echo '</div>' ; 
                                    echo '</div>' ;
                                echo '</div>' ;
                            
                            } 
                        }else {
                                        echo ' <span> not items to show,create <a href="newad.php">new ad </a> </span>';
                                    }
                            ?>
                        </div>
                    <!--  -->
                </div>
            </div>
        </div>
    </div> 

    <div class="my-comment block">
        <div class="container">
            <div class="card">
                <div class="card-heading bg-info text-white ">latest comments</div>
                <div class="card-body">
                        <!--  -->
<?php
                        $stmt = $con->prepare("SELECT
                                                comment
                                                FROM 
                                                comment 
                                                WHERE
                                                user_id = ?")  ;  
                        $stmt->execute(array($info['userid'])); 

                        //assign to variable 
                        $comments = $stmt->fetchAll();

                        if(!empty($comments)){
                            foreach($comments as $comment){
                                echo '<p>' . $comment['comment'] . '</p>'; 
                            }

                        }
                        else {
                            echo 'there is no comment to show';
                        }
                ?>
                        <!--  -->
                    </div>
            </div>
        </div>
    </div> 





<?php } else {
    header('location: login.php');
    exit();
}
include_once $tpl . 'footer.php' ;  
ob_end_flush();
?>