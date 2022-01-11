<?php 
    ob_start();
    session_start();

    $pageTitle ="Show Item";
    include 'init.php';

    //Check If Get Request itemid Is Numeric Get The Integer Value Of it
    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
           
    //Select All Data Depend On This Id
    $stmt = $con->prepare("SELECT 
                                items.*, 
                                categories.name AS category_name,
                                users.UserName  AS Username
                          FROM 
                                items
                          INNER JOIN 
                               categories 
                          ON 
                                categories.ID = items.cat_ID
                          INNER JOIN 
                            users 
                          ON 
                            users.userID = items.member_ID
                          WHERE 
                            item_ID = ?
                          AND 
                            approve = 1 ");

    //Execute Query
    $stmt->execute(array($itemid));

    $count = $stmt->rowCount();

    if($count > 0){
        //fetch The Data
        $item = $stmt -> fetch();
?>

<h2 class="text-center text-muted m-3"><?php echo $item['name']; ?></h2>
<div class="container">
    
    <div class="row">
        
        <div class="col-md-3">
            <div class="product-images">
                <?php 
                    $dirname = "imageItems/".$item['image'];
                    if (is_dir($dirname)) {
                        $images = glob($dirname ."/*");
                    }
                    echo "<img src='".$images[0]."' alt='Item Thumbnail' class='product-thumbnail'>";
                ?>
            </div>
        </div>
        <div class="col-md-9 item-info">
            <div class="row">
                <div class="col-md-9">
                    <h2><?php echo $item['name'];?></h2>
                    <p><?php echo $item['description'];?></p>
                </div>
                <div class="col-md-3">
                   <a href="#" class="btn btn-primary text-light">Add To cart</a>
                </div>
            </div>
            <ul class="list-group list-group-flush list-unstyled">
                <li class="list-item">
                    <i class="fas fa-calendar-week"></i>
                    <span>Date Added : </span><?php echo $item['added_date'];?>
                </li>
                <li class="list-item">
                    <span>Price : </span><?php echo $item['price'];?> JOD
                </li>
                <li class="list-item">
                    <span>Made in : </span><?php echo $item['country_made'];?>
                </li>
                <li class="list-item">
                    <span>Category : </span><a href="categories.php?pageid=<?php echo $item['cat_ID']; ?>"><?php echo $item['category_name'];?></a>
                </li>
                <li class="list-item">
                    <span>Added by : </span><a href="#"><?php echo $item['Username'];?></a>
                </li>
                <li class="list-item">
                    <?php
                        $allTags = explode(",",$item['tags']);
                        echo '<div class="item-tags">';
                            echo '<span>Tags : </span>';
                            foreach($allTags as $tag){
                                $tag = strtolower(str_replace(' ','',$tag)); 
                                if(!empty($tag)){
                                echo "<a href='tags.php?pagename={$tag}'>"."#".strtoupper($tag) . "</a>";
                                 }
                            }
                        echo '</div>';
                    ?>
                </li>

            </ul>
        </div>
    </div>
    <hr>
    <!-- Start Add Comment Section -->
    <?php if(isset($_SESSION['user'])){ ?>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="add-comment">
                <h3>Add your Comment</h3>
                <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $item['item_ID'];?>" method="POST">
                    <textarea required name="comment" class="form-control mt-3 mb-2"></textarea>
                    <input type="submit" class="btn btn-primary" value="Add your Comment">
                </form>
                <?php 
                    if($_SERVER['REQUEST_METHOD'] == "POST"){

                        $comment    = filter_var($_POST['comment'], FILTER_SANITIZE_STRING );
                        $itemid     = $item['item_ID'];
                        $userid     = $item['member_ID'] ;

                        if(!empty($comment)){

                            $stmt = $con->prepare("INSERT 
                                                   INTO
                                                        comments(comment, status, comment_date, item_ID, user_ID)
                                                        VALUES(:zcomment, 0, now(),:zitemid, :zuserid )
                                                    ");

                                                    $stmt->execute(array(

                                                        'zcomment' => $comment,
                                                        'zitemid' => $itemid,
                                                        'zuserid' => $_SESSION['uid']
                                                    ));

                                if($stmt){
                                    echo '<div class="alert alert-success">comment Added</div>';
                                }
                        }
                        else {
                            echo '<div class="alert alert-danger">comment Cannot Be embty</div>';
                        }
                    }
                ?>
            </div>
        </div>
    </div>
    <?php } else{ ?>
         <div class="row justify-content-center">
         <div class="col-md-6">
             <div class="add-comment">
                 <h3>Add your Comment</h3>
                 <form action="#">
                     <textarea disabled class="form-control mt-3 mb-2">Please Login or register to add comment</textarea>
                     <a href="login.php" class="btn btn-primary">Login</a>
                 </form>
             </div>
         </div>
     </div>
    <?php } ?>
    <!-- End Add Comment Section -->
    <hr>
    <?php
            //Select All Users Except Admins
            $stmt = $con->prepare("SELECT
                                        comments.*,
                                        users.UserName As member
                                    FROM 
                                            comments
                                    INNER JOIN 
                                            users 
                                    ON 
                                            users.userID = comments.user_ID
                                    WHERE
                                            item_ID = ?
                                    AND
                                            status = 1
                                    ORDER BY 
                                            c_ID DESC
                                   ");

            //execute The Statement
            $stmt->execute(array($itemid));

            //Assign To Varaible
            $comments = $stmt->fetchAll();
             foreach($comments as $comment){
                echo '<div class="comment-box">';
                    echo '<div class="row my-5">';
                        echo '<div class="col-sm-2 text-center">';
                            echo '<img src="OIP.png" alt="Avatar" class="img-fluid img-thumbnail rounded-circle"/>';
                            echo '<div>'.$comment['member'].'</div>';
                        echo '</div>';
                        echo '<div class="col-sm-10">';   
                        echo '<p class="lead">'.$comment['comment'].'</p>';
                        echo '<p>'.$comment['comment_date'].'</p>';
                        echo '</div>';
                    echo '</div>';
                    echo '<hr>';
                echo '<div>';
            }
           ?>
<?php

    }else {
        $theMsg="<div class='alert alert-danger'>There is no such ID or waiting By Approval from Admins </div?";
        redirectHome($theMsg, 'back', null ,3); 
    }
    require_once $comp . "footer.php"; 
    ob_end_flush();
?>