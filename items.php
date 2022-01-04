
<?php 
ob_start();
session_start();
$pageTitle = 'show items' ; 
include_once 'init.php' ;



        // بتحطط من الجيت ريكويست انو الايتيم اي دي رقم و بجيب الانتجر فاليو 
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) :  0  ; 
        // بختار البيانات من الداتا بيس من الاي دي المعين
        $stmt = $con->prepare("SELECT
                        items.*,
                        categories.name AS category_name,
                        users.username
                    FROM 
                        items
                        INNER JOIN 
                        categories
                        ON
                        categories.id=items.cat_id
                        INNER JOIN 
                        users
                        ON 
                        users.userid = items.member_id
                    WHERE
                        item_id = ?
                    AND 
                        approve =1   
                    ");

        $stmt->execute(array($itemid));   // اكسكيوت للكويري

        $count = $stmt->rowCount();
        if($count > 0 ){



        $item = $stmt->fetch(); // جلب البيانات  
?>

<h1 class="text-center"><?php echo $item['name'];  ?></h1>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <img class="img-responsive img-thumbnail float-center" src="../ecom/layout/images/img_avatar.png"  alt=""   />

        </div>
        <div class="col-md-9 item-info">
            <h2>  <?php echo $item['name'];        ?>   </h2>
            <p>   <?php echo $item['description']; ?>   </p>
            <ul class="list-unstyled">
                <li>
                    <i class="fa fa-calendar fa-fw"></i>
                    <span>added date</span> : <?php echo $item['add_date'];    ?>   
                </li>
                <li>
                    <i class="fas fa-dollar-sign fa-fw"></i>
                    <span>price</span>  : <?php echo $item['price']; ?>  
                </li>
                <li>
                    <i class="fa fa-building fa-fw"></i>
                    <span>made in</span> : <?php echo $item['country_made'] ;?> 
                </li>
                <li>
                    <i class="fa fa-tags fa-fw"></i>
                    <span>category</span>  : <a href="categories.php?pageid=<?php echo $item['cat_id'];?>"> <?php echo $item['category_name'] ;   ?> </a>
                </li>
                <li> 
                    <i class="fa fa-user fa-fw"></i>
                    <span>add by</span>  : <a href="#"> <?php echo $item['username'] ;  ?> </a>
                </li>
            </ul>
        </div>
    </div>
    <hr class="custom-hr">
    <?php 

    if (isset($_SESSION['user'])) { ?>

        <!-- start add comment -->
        <div class="row">
            <div class="col-md-offset-3">
                <div class="add-comment">
                <h3>add your comment</h3>
                <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $item['item_id'] ;?>" method="POST">
                    <textarea name="comment" required></textarea>
                    <input class="btn btn-primary" type="submit" value="add comment">
                </form>
                <?php 
                if($_SERVER['REQUEST_METHOD']=='POST'){

                    $comment= filter_var($_POST['comment'],FILTER_SANITIZE_STRING); 
                    $itemid = $item['item_id']; 
                    $userid = $_SESSION['uid'];


                    if(!empty($comment)){   

                        $stmt = $con->prepare("INSERT INTO 
                                        comment(comment,status,comment_date,item_id,user_id)
                                        VALUES(:zcommdnt, 0 , NOW() , :zitemid, :zuserid )");

                        $stmt->execute(array(
                            'zcommdnt'=> $comment ,
                            'zitemid' => $itemid ,
                            'zuserid' => $userid , 
                        ));

                        if($stmt){
                            echo '<div class="alert alert-success"> comment done </div> ';
                        }


                    }
                }
                ?>
                </div>
            </div>
        </div>
    <!-- end add comment -->

    <?php
    } else{
        echo '<a href="login.php"> login </a> or <a href="login.php"> register </a> to add comment  ' ; 
    }
    
    ?>
    
    <hr class="custom-hr">
                    <!-- user comment -->
                    <?php

                $stmt = $con->prepare("SELECT
                    comment.*, 
                    users.username AS member
                FROM 
                    comment 
                INNER JOIN 
                    users
                ON
                    users.userid= comment.user_id
                WHERE
                    item_id = ?
                AND 
                    status = 1 
                ORDER BY 
                    c_id DESC
                ") ;  

                $stmt->execute(array($item['item_id'])); 

                //assign to variable 
                $comments = $stmt->fetchAll();

                ?>
    
        <?php  foreach($comments as $com){ ?>
            <div class="comment-box">
                <div class="row"> 
                    <div class="col-sm-2 text-center ">
                        <img class="img-responsive img-thumbnail rounded-circle"  src="../ecom/layout/images/img_avatar.png"  alt=""   />
                            <?php echo $com['member']  ?> 
                    </div> 
                    <div class="col-sm-10">
                        <p class="lead">
                        <?php echo $com['comment']  ?>
                        </p>
                    </div>
                </div> 
                <hr class="custom-hr">
            </div>
            <?php  } ?>

                    <!-- end user comment -->
    </div>
</div>

<?php 
        } else {

            echo '<div class="alert alert-danger"> there is no such id OR this item is Waiting approval </div> ' ;

        }

?>

<?php
include_once $tpl . 'footer.php' ;  
ob_end_flush();
?>