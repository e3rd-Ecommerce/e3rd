<?php 
    session_start();

    $pageTitle = "Profile";
    
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Update') {
            //Get Vraiables Form The Form
            $id = $_SESSION['uid'];
            $user = $_POST['username'];
            $email = $_POST['email'];
            $name = $_POST['full'];
            $password = $_POST['password'];
            $pass = sha1($_POST['password']);
            
            $avatarPath ='';
            
            $formErrors = array();

            if (!empty($_FILES['avatar']['name'])) {
                $avatar = $_FILES['avatar'] ?? null;
                if ($avatar) {
                    $avatarName = $avatar['name'];
                    $avatarName     = $avatar['name']; //File name with extension
                    $avatarSize     = $avatar['size']; //File Size in KiloByte
                    $avatarTmpName  = $avatar['tmp_name']; //is the temporary name of the uploaded file which is generated automatically by php, and stored on the temporary folder on the server.
                    $avatarType     = $avatar['type']; //File type [img/pdf/powerpoint and so on..]
                    //Get allowd Avatar Extension to check if the file from allowd extensions or not
                    if($avatarSize > 4194304){ //in byte
                        $formErrors[]="Avatar Cant be Larger than <strong>4 Megabyte</strong>";
                    }
                    $exploaded = explode('.', $avatarName);
                    $avatarExtension = strtolower(end($exploaded));
                    $avatarPath = rand(0,1000000) . "_" . $avatarName;
                    move_uploaded_file($avatar['tmp_name'],"images/$avatarPath");
                }
            }

            $avatarAllowedExtensions = array("jpeg","jpg","png","gif","jfif"); //امتداد الملفات(الصور اللي بدي اسمع للشخص يرفعها)

            //Validate The Form
            //Array To coolect Al Errors
            if (!empty($user)) {
                if(strlen($user) < 4){
                    $formErrors[] = "Username cant Be Less Than<strong> 4 Characters</strong>";
                }
                if(strlen($user) > 10){
                    $formErrors[] = "Username cant Be More Than <strong>10 Characters</strong>";
                }
                
            }
            if (!empty($password)) {
                if(strlen($password) < 4){
                    $formErrors[] = "PassWord cant Be Less Than<strong> 4 Characters</strong>";
                }
                if(strlen($password) > 8){
                    $formErrors[] = "PassWord cant Be More Than <strong>20 Characters</strong>";
                }
                
            }

            if(!empty($avatarName) && !in_array($avatarExtension,$avatarAllowedExtensions)){
                $formErrors[]="This Extension Is not <strong>Allowed</strong>";
            }

            //Print All Errors
            foreach($formErrors as $error){
                echo '<div class="container mt-3 text-center">';
                    echo '<div class="alert alert-danger">' .$error .'</div>';
                echo '</div>';
            }


            //Check if there is No Error Procees The Update Opertion
            if(empty($formErrors)){
                $stmt2 =  $con->prepare("SELECT *
                                        FROM 
                                            users
                                        WHERE 
                                            UserName = ?
                                        AND 
                                            userID != ?"); //يعني جيبلي كل السوامح اللي بالداتابيز باستثناء هذا السامح
                
                $stmt2->execute(array($user,$id));

                $rows = $stmt2->rowCount();

                if($rows == 1){
                    
                    //echo Success Message
                    $theMsg= "<div class='alert alert-danger'>Sorry This Username Is Exist </div>";
                    echo '<div class="container mt-3 text-center">';
                        redirectHome($theMsg,'back',4);
                    echo '</div>';
                    
                    } else {

                    $stmt2 =  $con->prepare("SELECT * FROM users WHERE userID = ?");
                    $stmt2->execute(array($id));
                    $rows = $stmt2->fetch();

                    if (empty($user)) {
                        $user = $rows['UserName'];
                    }
                    if (empty($email)) {
                        $email = $rows['Email'];
                    }
                    if (empty($name)) {
                        $name = $rows['FullName'];
                    }
                    if (empty($password)) {
                        $pass = $rows['Password'];
                    }
                    if (empty($avatarPath)) {
                        $avatarPath = $rows['avatar'];
                    }
                    
                    //Update The DataBase With This Info
                    $stmt = $con->prepare("UPDATE users SET UserName = ? , Email = ? , FullName = ?, Password = ? ,avatar = ? WHERE userID = ?");
                    $stmt->execute(array($user,$email,$name,$pass,$avatarPath,$id));

                    //echo Success Message
                    $theMsg= "<div class='alert alert-success'>".$stmt->rowCount() ." ". 'Record Updated </div>';
                    echo '<div class="container mt-3 text-center">';
                        redirectHome($theMsg,'back',4);
                    echo '</div>';
                }
            } else {
                echo '<div class="container mt-3 text-center">';
                    redirectHome('',4);
                echo '</div>';
                }



    }elseif (isset($_GET['do']) == 'editProfil') {

        $getUser = $con->prepare("SELECT * FROM users WHERE userID = ?");

        $getUser ->execute(array($_SESSION['uid']));

        $info = $getUser->fetch(); //Fetch This Row

        $userid = $info['userID'];
        ?>

            <!-- Start All Title Box -->
            <div class="all-title-box">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-12">
                                    <h2> Edit <?php echo $_SESSION['user'] ;?> Profile</h2>
                                        <ul class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="index.php">Shop</a></li>
                                            <li class="breadcrumb-item active">My Account</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
            <!-- End All Title Box -->


<!-- edit profile  -->

<!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ -->
<div class="container rounded bg-white mt-5 mb-5">
<form action="profile.php?do=Update" method="POST" enctype='multipart/form-data'>

    <div class="row">

        <div class="col-md-4 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5">

                <!--  -->
                <img class="rounded-circle mt-5" src="images/<?php
                                            if ($info['avatar'] == '') {
                                                echo "no_avatar.png";
                                            }else{
                                                echo $info['avatar'];
                                            }?>" 
                                    alt="Not Found" style="width: 100%;"/> 
                    <input id="upload" type="file" name="avatar">
                <!--  -->
            </div>
        </div>
        <div class="col-md-8 border-right">
            <div class="p-3 py-5">
        
                <div class="row mt-2">
                    <div class="col-md-6"><label class="labels">full name</label>
                    <input type="text" class="form-control" name="full" placeholder="<?= $info['FullName']?>"></div>

                    <div class="col-md-6"><label class="labels">Name</label>
                    <input type="text" class="form-control" name="username" placeholder="<?= $info['UserName']?>"></div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12"><label class="labels">Email ID</label>
                    <input  type="email" name="email" placeholder="<?= $info['Email']?>" class="form-control" ></div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12"><label class="labels">Password</label>
                    <input class="form-control" type="password" name="password" placeholder="Leave it Blanck If You Dont Want To cahnge"></div>
                </div>

                <div class="mt-5 text-center">
                    <input type="submit" value="Save" class="btn btn-success profile-button" > 
                </div>
            </div>
        </div>
        
    </div>
    
</div>
</div>
    </form>
</div>

        
<?php
    }elseif(isset($_SESSION['user'])){

        $getUser = $con->prepare("SELECT * FROM users WHERE userID = ?");

        $getUser ->execute(array($_SESSION['uid']));

        $info = $getUser->fetch(); //Fetch This Row

        $userid = $info['userID'];
?>


<!-- end edit profile  -->

<!-- ******** -->

        <!-- Start All Title Box -->
        <div class="all-title-box">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                        <h2><?php echo $_SESSION['user'] ;?> Profile</h2>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php">Shop</a></li>
                                <li class="breadcrumb-item active">My Account</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        <!-- End All Title Box -->




<!-- profile  -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
        <div class="col-md-12">
            <div class="osahan-account-page-left shadow-sm bg-white h-100">
                <div class="border-bottom p-5">
                    <div class="osahan-user text-center">
                        <div class="osahan-user-media">


                            <img class="mb-3 rounded-pill shadow-sm mt-1" src="images/<?php
                                            if ($info['avatar'] == '') {
                                                echo "no_avatar.png";
                                            }else{
                                                echo $info['avatar'];
                                            }?>" 
                                    alt="Not Found" style="width: 26%;"/>   


                            <div class="osahan-user-media-body">
                                <h1 class="mb-2"> <strong> <?php echo $info['FullName'] ?><strong> </h1>
                                <p class="mb-1"> <strong> Login username :  </strong>  <?php echo $info['UserName'];  ?> </p>
                                <p><strong> email :</strong> <?php echo $info['Email'];  ?></p>
                                <p> <strong> Register date : </strong> <?php echo $info['regDate'];  ?> </p> 
                    
                                <a href="profile.php?do=editProfile" class="text-primary mr-3">Edit Infromation</a> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="osahan-account-page-right shadow-sm bg-white p-4 h-100">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane  fade  active show" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                        <h4 class="font-weight-bold mt-0 mb-4">My Products</h4>
                        <div class="bg-white card mb-4 order-list shadow-sm">
                            <div class="gold-members p-4">
                        
                                <div class="media">
                                <?php 
                                $items = getAllFrom("*", "items", "WHERE member_ID = $userid", "", "item_ID");
                                if(!empty($items)){
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
                                                        <?php
                                                        if($item['approve'] == 0){
                                                            echo '<p class="sale">Waiting Approval</span>';
                                                        } else {
                                                            echo '<p class="sale" > New </p>'; 
                                                        }
                                                        ?> 
                                                        </div>
                                                        <?php
                                                        if (! empty($images[0])) {
                                                            echo "<img src='".$images[0]."' alt='Avatar' class='img-fluid'>";
                                                        }else {
                                                            echo "<img src='images/no_avatar.png' alt='Avatar' class='img-fluid'>";
                                                        }
                                                        ?>
                        </div>
                        <div class="why-text">

                        <?php  echo '<a href="servesItems.php?do=showItem&itemid=' . $item['item_ID'] .'"><h4>'. $item['name'] .'</h4></a>'; ?>
                            <h5> <?php echo $item['price'] ;?> </h5>
                        </div>
                    </div>
                </div>
                                <?php
                                }
                                }else {
                                    echo '<div>There\'s No Products to show, <a style="color:blue;"href="newad.php">Add new Produt</a></div>';
                                }

                                ?>
                                </div>
                            </div>
                        </div>
                        <h4 class="font-weight-bold mt-0 mb-4">My Comment</h4>
                        <div class="bg-white card mb-4 order-list shadow-sm">

                            <div class="gold-members p-4">
                                <div class="media">
                                    <!--   تعليقات   -->
                                    <?php

                        $comments = getAllFrom("comment", "comments", "WHERE user_ID = $userid", "", "c_ID");

                        if(!empty($comments)){

                            foreach($comments as $comment){
                                echo '<p>'.$comment['comment'].'</p>'; 
                            }

                        } else {
                            echo 'There\'s No Comments to show';
                        }

                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       </div>
    </div>
</div>



<?php
    }else {
        header('Location:profile.php');
    }

require_once $comp . "footer.php"; ?>