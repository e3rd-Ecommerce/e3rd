<?php 
    ob_start();
    session_start();

    $pageTitle ="Show Item";
    require_once 'init.php';

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


    <!-- Start All Title Box -->
    <div class="all-title-box">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Shop Detail</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Shop</a></li>
                        <li class="breadcrumb-item active">Shop Detail </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- End All Title Box -->

    <!-- ******* -->
    
    <!-- Start Shop Detail  -->
    <div class="shop-detail-box-main">
        <div class="container">
            <div class="row">
                <div class="col-xl-5 col-lg-5 col-md-6">
                    <?php 
                    $dirname = "imageItems/".$item['image'];
                    if (is_dir($dirname)) {
                        $images = glob($dirname ."/*");
                    }
                    
                    ?>
                    <div id="carousel-example-1" class="single-product-slider carousel slide" data-ride="carousel">
                        <div class="carousel-inner" role="listbox">
                            <div class="carousel-item active"> <img class="d-block w-100" src="<?php echo $images[0]; ?>" alt="First slide"> </div>
                            <div class="carousel-item"> <img class="d-block w-100" src="<?php echo $images[1]; ?>" alt="Second slide"> </div>
                            <div class="carousel-item"> <img class="d-block w-100" src="<?php echo $images[2]; ?>" alt="Third slide"> </div>

                        </div>
                        <a class="carousel-control-prev" href="#carousel-example-1" role="button" data-slide="prev"> 
						<i class="fa fa-angle-left" aria-hidden="true"></i>
						<span class="sr-only">Previous</span> 
					</a>
                        <a class="carousel-control-next" href="#carousel-example-1" role="button" data-slide="next"> 
						<i class="fa fa-angle-right" aria-hidden="true"></i> 
						<span class="sr-only">Next</span> 
					</a>
                        <ol class="carousel-indicators">


                            <li data-target="#carousel-example-1" data-slide-to="0" class="active">
                                <img class="d-block w-100 img-fluid" src="<?php echo $images[0]; ?>" alt="" />
                            </li>
                            <li data-target="#carousel-example-1" data-slide-to="1">
                                <img class="d-block w-100 img-fluid" src="<?php echo $images[1]; ?>" alt="" />
                            </li>
                            <li data-target="#carousel-example-1" data-slide-to="2">
                                <img class="d-block w-100 img-fluid" src="<?php echo $images[3]; ?>" alt="" />
                            </li>
                        </ol>
                    </div>
                </div>

                <div class="col-xl-7 col-lg-7 col-md-6">
                    <div class="single-product-details">
                        <h2><?php echo $item['name'];?></h2>
                        <h5> <?php echo $item['price'];?> JOD</h5>
                        <p class="available-stock"><span> category / <a href="categories.php?pageid=<?php echo $item['cat_ID']; ?>"><?php echo $item['category_name'];?> </a></span><p>
                        <p class="available-stock"><span> Add by  / <a href="#"><?php echo $item['Username'];?> </a></span><p>
                        <span>Made in : </span><?php echo $item['country_made'];?>


						<h4>Short Description:</h4>
						<p> <?php echo $item['description'];?> </p>
                        <li class="list-item">
                            <?php
                                $allTags = explode(",",$item['tags']);
                                echo '<div class="item-tags">';
                                    echo '<span>Tags : </span>';
                                    foreach($allTags as $tag){
                                        $tag = strtolower(str_replace(' ','',$tag)); 
                                        if(!empty($tag)){
                                        echo "<a href='tags.php?pagename={$tag}' class='tag'>"."#".strtoupper($tag) . "</a>";
                                        }
                                    }
                                echo '</div>';
                            ?>
                        </li>

                


						<div class="price-box-bar">
							<div class="cart-and-bay-btn">
                            <?php 
                        if (isset($_SESSION['uid'])) {
                            
                        
                            $stmt = $con->prepare("SELECT * FROM cart_item 
                                WHERE user_ID = ? AND item_ID = ? ");

                            $stmt->execute(array($_SESSION['uid'],$item['item_ID']));
                            $row = $stmt->fetch(); // جلب البيانات  
                            $count=$stmt->rowCount(); 
                            

                            if ($count > 0 ) {?>
                                <input type="submit" class="btn btn-secondary btn-lg" value="In Cart">
                            <?php }else{?>
                                <input type="button" class="btn btn-primary btn-lg" id="<?= $item['item_ID']?>" value="Add to Cart" onclick="AddToCart(<?=$item['item_ID']?>,<?=$_SESSION['uid']?>)">
                    <?php } }?>
							</div> 
						</div>
	
                    </div>
                </div>
            </div>
    </div>
    </div>
    <!-- ******* -->

    <hr>
    <!-- Start Add Comment Section -->
    <?php if(isset($_SESSION['user'])){ ?>
    <div class="container">
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
    </div>

    
    <?php } else{ ?>
        <div class="container">
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
    </div>

    <?php } ?>
    <!-- End Add Comment Section -->
    <hr>
    <?php
            //Select All Users Except Admins
            $stmt = $con->prepare("SELECT
                                        comments.*,
                                        users.UserName As member,
                                        users.avatar
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
             foreach($comments as $comment){ ?>

<div class="container mt-5">
    <div class="d-flex justify-content-center row">
        <div class="col-md-12">
            <div class="p-3 bg-white rounded">
                <div class="review">
                    <div class="d-flex flex-row comment-user">
                        <img class="rounded-circle image-comment" src="images/<?=$comment['avatar']?>" width="60">
                        <div class="ml-2">
                            <div class="d-flex flex-row align-items-center"><span class="name font-weight-bold"><?php echo $comment['member'] ; ?></span><span class="dot"></span>
                            <span class="date"><?php echo $comment['comment_date']  ?></span></div>
                            <p class="comment-text"><?php echo $comment['comment']; ?></p>
                            <!-- <div class="rating"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<hr>
<?php
            }

    }else {
        $theMsg="<div class='alert alert-danger'>There is no such ID or waiting By Approval from Admins </div?";
        redirectHome($theMsg, 'back', null ,3); 
    }
    require_once $comp . "footer.php"; 
    ob_end_flush();
?>