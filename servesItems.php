<?php
ob_start();
session_start();
require_once "init.php";

//Check If Get Request itemid Is Numeric Get The Integer Value Of it
$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

if ($do == 'showItem') {

    
           
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
                    <h2>My Products</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="profile.php">Profile</a></li>
                        <li class="breadcrumb-item active">My Products </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- End All Title Box -->


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
                        <p class="available-stock"><span> Add bt  / <a href="#"><?php echo $item['Username'];?> </a></span><p>
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
                                echo "<a href='tags.php?pagename={$tag}'>"."#".strtoupper($tag) . "</a>";
                                }
                            }
                        echo '</div>';
                    ?>
                </li>



                <div class="col-md-12 edit-items" >
                    <?php 
                        if (isset($_SESSION['uid'])) {
                            
                        
                            $stmt = $con->prepare("SELECT * FROM cart_item 
                                WHERE user_ID = ? AND item_ID = ? ");

                            $stmt->execute(array($_SESSION['uid'],$item['item_ID']));
                            $row = $stmt->fetch(); // جلب البيانات  
                            ?>
                            <a href="servesItems.php?do=editItem&itemid=<?=$itemid?>" class="btn btn-lg btn-warning btn-sm">Edit</a>
                            <a href="servesItems.php?do=deleteItem&itemid=<?=$itemid?>" class="btn btn-lg btn-danger btn-sm">Delete</a>
                    <?php }?>

                </div>



						
                    </div>
                </div>
            </div>
    </div>
    </div>


<!--  -->
    <hr>
    <!-- Start Add Comment Section --> 
    <?php if(isset($_SESSION['user'])){ ?>
        <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="add-comment">
                    <h3>Add your Comment</h3>
                    <form action="<?php echo $_SERVER['PHP_SELF'] . '?do=showItem&itemid=' . $item['item_ID'];?>" method="POST">
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
        
        <?php }
    
    else{ ?>

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
                        <img class="rounded-circle image-comment" 
                        src="images/<?php if (empty($comment['avatar'])) {
                                echo "no_avatar.png";
                            }else{
                                echo $comment['avatar'];
                            }?>" alt="no found">
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
                
            

<?php }

    }else {
        $theMsg="<div class='alert alert-danger'>There is no such ID or waiting By Approval from Admins </div?";
        redirectHome($theMsg, 'back', null ,3); 
    }
    


}elseif ($do == 'editItem') {

    //Select All Data Depend On This Id
    $stmt = $con->prepare("SELECT * FROM items WHERE item_ID = ?");

    //Execute Query
    $stmt->execute(array($itemid));

    //fetch The Data
    $item = $stmt -> fetch();

    //Check if there is a row like this data
    $count = $stmt->rowCount(); //هاي الرو كاونت فنكشن جاهزه بتجيبلي عدد الاسطر اللي لقاها 

    //If there record for this id show the form
    if($count > 0) { ?>


        <!-- Start All Title Box -->
        <div class="all-title-box">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <h2>Edit item</h2>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="profile.php">Profile</a></li>
                                <li class="breadcrumb-item active"> edit product </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        <!-- End All Title Box -->
        
        <div class="container">
                <div class="row">
                    <div class="col-lg-6 m-auto">
                        <div class="card bg-light mt-3">
                            <div class="card-body">
                                <form action="?do=Update" method="POST" enctype="multipart/form-data">
                                <input 
                                        type="hidden" 
                                        name="itemid" 
                                        value="<?php echo $itemid;?>">
                                <div class="container cat" style="display: contents">
                                <?php
                                    $dirname = "imageItems/".$item['image'];
                                    if (is_dir($dirname)) {
                                        $image ='';
                                        $images = glob($dirname."/*");
                                        for ($i=0; $i<count($images); $i++)
                                        { 
                                            $image = $images[$i];  ?>
                                            <img src="<?=$image?>" alt="not found" style="width:30%"
                                            onmouseover="bigImg(this)" onmouseout="normalImg(this)" onclick="deleteimage('<?=$image?>')">
                                        <?php }
                                    }
                                ?>
                                </div>

                                <!-- start multiple image field -->
                                <div class="custom-container">
                                    <br>
                                    <div class="button-wrap">
                                            <label class="button mb-3" for="upload">Upload File</label>
                                            <input id="upload" type="file" name="files[]" multiple>
                                    </div>
                                </div>
                                <!-- End image -->
                                    <input 
                                        type="text" 
                                        name="name" 
                                        placeholder="Name of The Item" 
                                        class="form-control my-2" 
                                        required="required" 
                                        value = "<?php echo $item['name'] ?>" /> 

                                    <input 
                                        type="text" 
                                        name="desc" 
                                        placeholder="Description The Item" 
                                        class="form-control my-2" 
                                        required="required"
                                        value = "<?php echo $item['description'] ?>" /> 
                
                                    <input 
                                        type="text" 
                                        name="price" 
                                        placeholder="Price of The Item" 
                                        class="form-control my-2" 
                                        required="required" 
                                        value = "<?php echo $item['price'] ?>" /> 

                                    <input 
                                        type="text" 
                                        name="country" 
                                        placeholder="Countery of Made" 
                                        class="form-control my-2" 
                                        required="required"
                                        value = "<?php echo $item['country_made'] ?>" /> 

                                    <div class="select-box">
                                        <label for="status">Select The Item Status: </label>
                                        <select name="status" class="mb-2" id="status">
                                            <option value="1" <?php if($item['status'] == 1){echo 'selected';} ?>>New</option>
                                            <option value="2" <?php if($item['status'] == 2){echo 'selected';} ?>>Like New</option>
                                            <option value="3" <?php if($item['status'] == 3){echo 'selected';} ?>>Used</option>
                                            <option value="4" <?php if($item['status'] == 4){echo 'selected';} ?>>Very Old</option>
                                        </select>
                                    </div>

                                    <div class="select-box">
                                        <label for="status">Select Category: </label>
                                        <select name="category" class="mb-2" id="status">
                                            <?php 
                                                $cats = getAllFrom("*", "categories", "WHERE parent = 0","" ,"ID");
                                                foreach($cats as $cat){
                                                    echo "<option value='".$cat['ID']."'";
                                                        if($item['cat_ID'] == $cat['ID']){echo 'selected';}
                                                    echo ">". $cat['name']. "</option>";   
                                                    $childCats = getAllFrom("*", "categories", "WHERE parent = {$cat['ID']}","" ,"ID");
                                                    foreach($childCats as $child){
                                                        echo '<optgroup label="Sub categories">';
                                                            echo "<option class='ms-3' value='".$child['ID']."'";
                                                                if($item['cat_ID'] == $child['ID']){echo 'selected';}
                                                            echo ">". '- '.$child['name']. "</option>";
                                                        echo '</optgroup>';
                                                    }
                                                }
                                                
                                            ?>
                                        </select>

                                        <!--Start Tags Feild-->
                                            <input 
                                                type="text" 
                                                name="tags" 
                                                placeholder="food, games, kids, lashes" 
                                                class="form-control my-2" 
                                                value = "<?php echo $item['tags']; ?>"
                                                > 
                                        <!--End Tags Feild-->

                                    </div>
                                    <button class="btn btn-success btn-sm form-control" >Save Item</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        
        <?php
        //Select All Comments For This itesm
        $stmt = $con->prepare("SELECT
                                comments.*,
                                users.UserName As member_name
                            FROM 
                                comments
                            INNER JOIN 
                                users 
                            ON 
                                users.userID = comments.user_ID
                            WHERE
                                item_ID = ?
                        ");

        //execute The Statement
        $stmt->execute(array($itemid));

        //Assign To Varaible
        $rows = $stmt->fetchAll();


        }

}elseif ($do == 'deleteItem') {

    function deleteDir($dirPath) {
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }


    $stmt1 = $con->prepare("SELECT * FROM items WHERE item_ID = ? AND member_ID = ?");
    $stmt1->execute(array($itemid,$_SESSION['uid']));
    $row = $stmt1->fetch();

    $dir = $row['image'];
    //echo "../imageItems/$dir";die;
    if (file_exists("imageItems/$dir")) {
        deleteDir("imageItems/$dir");  
    }
    $stmt = $con->prepare("DELETE FROM items
                        WHERE item_ID = ? AND member_ID = ?");

            //execute The Statement
    $stmt->execute(array($itemid,$_SESSION['uid']));

    header("Location: profile.php");
    exit;
}elseif ($do == 'Update') {

    echo '<h1 class="text-center"> Update Item</h1>';
    echo '<div class="container">';
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            //Get Vraiables From The Form
            $id         = $_POST['itemid'];
            $name       = $_POST['name'];
            $desc       = $_POST['desc'];
            $price      = $_POST['price'];
            $country    = $_POST['country'];
            $status     = $_POST['status'];
            $category   = $_POST['category'];
            $tags       = $_POST['tags'];

            // File upload configuration
            $stmt = $con->prepare("SELECT * FROM items WHERE item_ID = ?");
            $stmt->execute(array($id));

            $item= $stmt->fetch(); 

            $dirname = "imageItems/".$item['image'];
            if ( is_dir($dirname)) { 
                //echo $dirname;die;
                $targetDir = "$dirname/"; 
                $allowTypes = array('jpg','png','jpeg','gif','jfif'); 
        
                $fileNames = array_filter($_FILES['files']['name']); 
                if(!empty($fileNames)){ 
                    foreach($_FILES['files']['name'] as $key=>$val){ 
                        // File upload path 
                        $fileName = basename($_FILES['files']['name'][$key]); 
                        $targetFilePath = $targetDir . $fileName; 
                    
                        // Check whether file type is valid 
                        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
                        if(in_array($fileType, $allowTypes)){ 
                            // Upload file to server 
                            move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath);     
                        }
                    }  
                }else{ 
                    $statusMsg = 'Please select a file to upload.'; 
                }
            }

            //Validate The Form
            $formErrors = array(); //Array To coolect Al Errors

            if(empty($name)){
                $formErrors[] = "Name can't Be <strong>Empty</strong>";
            }

            if(empty($desc)){
                $formErrors[] = "Description can't Be <strong>Empty</strong>";
            }

            if(empty($price)){
                $formErrors[] =  "Price can't Be <strong>Empty</strong>";
            }
            if(empty($country)){
                $formErrors[] =  "Country can't Be <strong>Empty</strong>";
            } 

            //Print All Errors
            foreach($formErrors as $error){
                echo '<div class="alert alert-danger">' .$error .'</div>';
            }

            //Check if there is No Error Procees The Update Opertion
            if(empty($formErrors)){
                //Update The DataBase With This Info
                $stmt = $con->prepare("UPDATE 
                                            `items`
                                        SET  name         = ? , 
                                            description  = ? ,
                                            price        = ? ,
                                            country_made = ? ,
                                            status       = ? ,
                                            cat_ID       = ? ,
                                            tags         = ? 
                                        WHERE
                                            item_ID      = ?");
                                            
                $stmt->execute(array($name,$desc,$price,$country,$status,$category,$tags,$id));

                //echo Success Message
                $theMsg= "<div class='alert alert-success'>".$stmt->rowCount() ." ". 'Record Updated </div>';
                echo '<div class="container mt-3 text-center">';
                    redirectHome($theMsg,'back',4);
                echo '</div>';
            }

        } else {
                $theMsg= "<div class='alert alert-danger'>Sorry You Cant Browse This Page Directly </div>";
                echo '<div class="container mt-3 text-center">';
                    redirectHome($theMsg,4);
                echo '</div>';
        }
    echo '</div>';   
}


?>

<?php
require_once $comp . "footer.php"; 
ob_flush();
?>