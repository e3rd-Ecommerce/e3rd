<?php

/*
===================================================
== Manage Memebers Page
== You can Add | Edit | Delete Members From Here
===================================================
*/

    ob_start();

    session_start();

    $pageTitle = "Members" ;

    if(isset($_SESSION['ID'])){ //هون بشيك اذا فيه سشين موجوده ولا لأ
        
        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage' ;

        // Start Manage Page
        if($do == 'Manage'){ //Manage Page 

            $query = '';

            if(isset($_GET['page']) && $_GET['page'] == 'Pending'){

                $query = 'AND RegStatus = 0';
            }
            //Select All Users Except Admins
            $stmt = $con->prepare("SELECT * FROM users WHERE GroupId != 1 $query ORDER BY userID DESC");

            //execute The Statement
            $stmt->execute();

            //Assign To Varaible
            $rows = $stmt->fetchAll();
        
            if(!empty($rows)){

                ?>

                <h2 class="text-center">Manage Members</h2>
                <div class="container"> 
                <a href="members.php?do=Add" class="btn btn-primary custom-Add-btn btn-sm my-2"><i class="fa fa-plus"></i> New Member</a>
                    <div class="table-responsive">
                        <table class="main-table text-center table table-bordered manage-members">
                            <tr> 
                                <td>#ID</td>
                                <td>Avatar</td>
                                <td>Username</td>
                                <td>Email</td>
                                <td>Full Name</td>
                                <td>Registerd Date</td>
                                <td>control</td>
                            </tr> 

                            <?php
                                foreach($rows as $row){
                                    echo "<tr>";
                                        echo "<td>". $row['userID'] . "</td>";
                                        echo "<td><img src='";
                                                if(!empty($row['avatar'])){echo "../images/". $row['avatar'];}
                                                else{echo "../images/no_avatar.png";}
                                        echo "' alt='Avatar here'/></td>";
                                        echo "<td>". $row['UserName'] . "</td>";
                                        echo "<td>". $row['Email'] . "</td>";
                                        echo "<td>". $row['FullName'] . "</td>";
                                        echo "<td>".$row['regDate']."</td>";
                                        echo "<td>
                                                <a href='?do=Edit&userid=$row[userID]' class='btn btn-info edit'><i class='fa fa-edit'></i> Edit</a>
                                                <a href='?do=Delete&userid=$row[userID]' class='btn btn-danger delete confirm'><i class='fa fa-close'></i> Delete </a>";
                                                if ($row['RegStatus'] == 0){
                                                echo "<a href='?do=Activate&userid=$row[userID]' class='btn btn-info activate'><i class='fas fa-check'></i> Activate</a>";
                                                }         
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            ?>
                            
                        </table>
                    </div>
                </div>
        <?php }else {
                $theMsg='<div class="nice-message">Theres No Members To show here</div>';
                echo '<div class="seperator"></div>';
                echo '<div class="container mt-3 text-center">';
                    echo $theMsg;
                    echo '<a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>';
                echo '</div>';
        }
        }elseif ($do == 'Add'){ //Add Members Page ?>

            
            <div class="container">
                        <div class="row">
                            <div class="col-lg-6 m-auto custom-form-header">
                                <div class="card bg-light mt-5">
                                    <div class="card-titel text-white">
                                        <h3 class="text-center py-4">Add New Member</h3>
                                    </div>

                                    <div class="card-body">
                                        <!--نوع الانكربشن هذا بستخدمه لما بدي ارفع ملفات/صور... الخ وفي النوع الديفولت اللي هو application/x-www-form-urlencoded/-->
                                        <form action="?do=Insert" method="POST" enctype='multipart/form-data'>
                                            <input type="text" name="username" placeholder="Username To Login into shop" class="form-control my-2" autocomplete="off" required="required" >
                                            <input type="password" name="password" class="form-control my-2" autocomplete="new-password" placeholder="Enter User Password" required="required">
                                            <input type="email" name="email" placeholder="Email Must Be Valid" class="form-control my-2" autocomplete="new-password" required="required" >
                                            <input type="text" name="full" placeholder="Full Name Appear In Your Profile Page" class="form-control my-2" required="required">
                                           
                                            <!--Start User profile picture-->
                                            <div class="custom-container">
                                                <div class="button-wrap">
                                                    <label class="button mb-3" for="upload">Upload File</label>
                                                    <input id="upload" type="file" name="avatar" required="required">
                                                </div>
                                            </div>
                                            <!--End User Profile Picture-->
                                            
                                            <div class="d-grid col-12">
                                                <button class="btn btn-outline-primary" type="submit">Add Member</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>

    <?php
    
        } elseif($do == 'Insert'){
            
            //inser Member to The Page
            
                if($_SERVER['REQUEST_METHOD'] == 'POST'){

                    echo '<h1 class="text-center"> Insert Member</h1>';
                    echo '<div class="container">';

                    //Uploade Variables
                    //$avatar = $_FILES['avatar'] ?? null;
                    $avatarName ='';
                    
                    if (!empty($_FILES['avatar']['name'])) {
                        $avatar = $_FILES['avatar'] ?? null; 
                        if ($avatar) {

                            $avatarName = $avatar['name'];
                            $avatarName     = $avatar['name']; //File name with extension
                            $avatarSize     = $avatar['size']; //File Size in KiloByte
                            $avatarTmpName  = $avatar['tmp_name']; //is the temporary name of the uploaded file which is generated automatically by php, and stored on the temporary folder on the server.
                            $avatarType     = $avatar['type']; //File type [img/pdf/powerpoint and so on..]
                            //Get allowd Avatar Extension to check if the file from allowd extensions or not
                            $exploaded = explode('.', $avatarName);
                            $avatarExtension = strtolower(end($exploaded));
                            $avatarPath = rand(0,1000000) . "_" . $avatarName;
                            move_uploaded_file($avatar['tmp_name'],"../images/$avatarPath");
                        }
                    }

                    $avatarAllowedExtensions = array("jpeg","jpg","png","gif","jfif"); //امتداد الملفات(الصور اللي بدي اسمع للشخص يرفعها)
                    
                    

                    //Get Vraiables Form The Form
                    $user = $_POST['username'];
                    $pass = $_POST['password'];
                    $email = $_POST['email'];
                    $name = $_POST['full'];
                    $hasehdPass = sha1($_POST['password']);

                    
                    //Validate The Form
                    $formErrors = array(); //Array To coolect Al Errors

                    if(strlen($user) < 4){
                        $formErrors[] = "Username cant Be Less Than<strong> 4 Characters</strong>";
                    }

                    if(strlen($user) > 20){
                        $formErrors[] = "Username cant Be More Than <strong>20 Characters</strong>";
                    }

                    if(empty($user)){
                        $formErrors[] =  "Username cant be empty";
                    }
                    if(empty($pass)){
                        $formErrors[] =  "Passowrd cant be empty";
                    }
                    if(empty($name)){
                        $formErrors[] = "Full name cant bet empty";
                    }
                    if(empty($email)){
                        $formErrors[]= "email cant bet empty</div>";
                    }
                    if(!empty($avatarName) && !in_array($avatarExtension,$avatarAllowedExtensions)){
                        $formErrors[]="This Extension Is not <strong>Allowed</strong>";
                    }
                    if(empty($avatarName)){
                        $formErrors[]="Avatar is <strong>Required</strong>";
                    }

                    if($avatarSize > 4194304){ //in byte
                        $formErrors[]="Avatar Cant be Larger than <strong>4 Megabyte</strong>";
                    }

                    //Print All Errors
                    foreach($formErrors as $error){
                        echo '<div class="alert alert-danger">' .$error .'</div>';
                    }

                    //Check if there is No Error Procees The Update Opertion
                    if(empty($formErrors)){

                        //$avatar = rand(0,1000000) . "_" . $avatarName;
                        //move_uploaded_file($avatarTmpName,"uploads\avatars\\" . $avatar); 

                        //Check If User Exist In DataBase
                        $check = checkItem("UserName", "users", $user);
                        if($check == 1){
                            $theMsg="<div class='alert alert-danger'>Sorry This User Is Exist</div>";
                                echo '<div class="seperator"></div>';
                                echo '<div class="container mt-3 text-center">';
                                    redirectHome($theMsg,'back');
                                echo '</div>';
                        } else {
                                //inser User Info in DataBase
                                $stmt = $con->prepare("INSERT INTO 
                                                        users(UserName,Password,Email,FullName, RegStatus,regDate,avatar)
                                                        VALUES(:zuser, :zpass, :zmail, :zname, 1, now(),:zavatar)");

                                $stmt->execute(array(
                                
                                'zuser'     => $user,
                                'zpass'     => $hasehdPass,
                                'zmail'     => $email,
                                'zname'     => $name,
                                'zavatar'   => $avatarPath
                                ));

                                //echo Success Message
                               $theMsg="<div class='alert alert-success'>".$stmt->rowCount() ." ". 'Record Inserted </div>';
                                
                               echo '<div class="seperator"></div>';
                               echo '<div class="container mt-3 text-center">';
                                    redirectHome($theMsg,null,'members');
                               echo '</div>';
                        }
                    }

                } else {

                    $theMsg = "<div class='alert alert-danger'>You cant Browse This Page Directly</div>" ;
                    echo '<div class="seperator"></div>';
                    echo '<div class="container mt-3 text-center">';
                        redirectHome($theMsg,4);
                    echo '</div>';
                }
            echo '</div>';
        

        }elseif ($do == 'Edit'){//Edit Page 

            //Check If Get Request userid Is Numeric 7 Get The Integer Value Of it
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
           
            //Select All Data Depend On This Id
            $stmt = $con->prepare("SELECT * FROM users WHERE userID = ? LIMIT 1");

            //Execute Query
            $stmt->execute(array($userid));

            //fetch The Data
            $row = $stmt -> fetch();

            //Check if there is a row like this data
            $count = $stmt->rowCount(); //هاي الرو كاونت فنكشن جاهزه بتجيبلي عدد الاسطر اللي لقاها 

            //If there record for this id show the form
            if($count > 0) { ?>
                
                <div class="container">
                        <div class="row">
                            <div class="col-lg-6 m-auto custom-form-header">
                                <div class="card bg-light mt-5">
                                    <div class="card-titel text-white">
                                        <h3 class="text-center py-4">Edit Member</h3>
                                    </div>

                                    <div class="card-body">
                                        <form action="?do=Update" method="POST" enctype='multipart/form-data'>

                                             <!--Start User profile picture-->
                                             <div class="custom-container">
                                             
                                                <img src="../images/<?php if ($row['avatar'] == '') {
                                                    echo "no_avatar.png";
                                                }else{
                                                    echo $row['avatar'];
                                                }?>" alt="Not Found" style="width: 35%;"/>
                                                
                                            </div>
                                            
                                            <div class="custom-container">
                                                <div class="button-wrap">
                                                    <label class="button mb-3" for="upload">Upload Your Image</label>
                                                    <input id="upload" type="file" name="avatar">
                                                </div>
                                            </div>
                                            <!--End User Profile Picture-->
                                            
                                            <input type="hidden" name="userid" value="<?php echo $userid;?>">
                                            <input type="text" name="username" placeholder="Username" class="form-control my-2" value="<?php echo $row['UserName'] ?>" autocomplete="off" required="required">
                                            <input type="hidden" name="oldpassword" value="<?php echo $row['Password']; ?>">
                                            <input type="password" name="newpassword" class="form-control my-2" autocomplete="new-password" placeholder="Leave it Blanck If You Dont Want To cahnge">
                                            <input type="password" name="newpassword" class="form-control my-2" autocomplete="new-password" placeholder="Leave it Blanck If You Dont Want To cahnge">
                                            <input type="email" name="email" placeholder="Email" class="form-control my-2" value="<?php echo $row['Email'] ?>" autocomplete="new-password" required="required">
                                            <input type="text" name="full" placeholder="Full Name" value="<?php echo $row['FullName'] ?>" class="form-control my-2" required="required">
                                            <button class="btn btn-outline-primary" >Save Changes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
    <?php

        //If there is no Such Id
        } else {

                     $theMsg='<div class="alert alert-danger">Theres No Such ID</div>';
                        echo '<div class="seperator"></div>';
                        echo '<div class="container mt-3 text-center">';
                            redirectHome($theMsg,4);
                        echo '</div>';

            } 
        }elseif ($do == 'Update'){

            echo '<h1 class="text-center"> Update Member</h1>';
            echo '<div class="container">';
                if($_SERVER['REQUEST_METHOD'] == 'POST'){

                    //Get Vraiables Form The Form
                    $id = $_POST['userid'];
                    $user = $_POST['username'];
                    $email = $_POST['email'];
                    $name = $_POST['full'];
                    
                    $avatarPath ='';
                    
                    if (!empty($_FILES['avatar']['name'])) {
                        $avatar = $_FILES['avatar'] ?? null;
                        if ($avatar) {
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
                            move_uploaded_file($avatar['tmp_name'],"../images/$avatarPath");
                        }
                    }

                    $avatarAllowedExtensions = array("jpeg","jpg","png","gif","jfif"); //امتداد الملفات(الصور اللي بدي اسمع للشخص يرفعها)
                    

                    //Password Trick Using Ternary condition
                    $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);
                    
                    //Validate The Form
                    $formErrors = array(); //Array To coolect Al Errors

                    if(strlen($user) < 4){
                        $formErrors[] = "Username cant Be Less Than<strong> 4 Characters</strong>";
                    }

                    if(strlen($user) > 10){
                        $formErrors[] = "Username cant Be More Than <strong>10 Characters</strong>";
                    }

                    if(empty($user)){
                        $formErrors[] =  "Username cant be empty";
                    }

                    if(empty($email)){
                        $formErrors[]= "email cant bet empty</div>";
                    }
                    if(!empty($avatarName) && !in_array($avatarExtension,$avatarAllowedExtensions)){
                        $formErrors[]="This Extension Is not <strong>Allowed</strong>";
                    }
                    //Print All Errors
                    foreach($formErrors as $error){
                        echo '<div class="alert alert-danger">' .$error .'</div>';
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
                            echo '<div class="seperator"></div>';
                            echo '<div class="container mt-3 text-center">';
                                redirectHome($theMsg,'back',4);
                            echo '</div>';
                            
                         } else {

                            $stmt2 =  $con->prepare("SELECT * FROM users WHERE userID = ?");
                            $stmt2->execute(array($id));
                            $rows = $stmt2->fetch();

                            if ($avatarPath == '') {
                                $avatarPath = $rows['avatar'];
                            }
                            
                            //Update The DataBase With This Info
                            $stmt = $con->prepare("UPDATE users SET UserName = ? , Email = ? , FullName = ?, Password = ? ,avatar = ? WHERE userID = ?");
                            $stmt->execute(array($user,$email,$name,$pass,$avatarPath,$id));

                            //echo Success Message
                            $theMsg= "<div class='alert alert-success'>".$stmt->rowCount() ." ". 'Record Updated </div>';
                            echo '<div class="seperator"></div>';
                            echo '<div class="container mt-3 text-center">';
                                redirectHome($theMsg,'back',4);
                            echo '</div>';

                            
                        }
                    }

                } else {
                        $theMsg= "<div class='alert alert-danger'>Sorry You Cant Browse This Page Directly </div>";
                        echo '<div class="seperator"></div>';
                        echo '<div class="container mt-3 text-center">';
                            redirectHome($theMsg,4);
                        echo '</div>';
                }
            echo '</div>';
        
        } elseif($do == 'Delete'){//Delete Member Page

            echo '<h1 class="text-center"> Delete Member</h1>';
            echo '<div class="container">';
            
                //Check If Get Request userid Is Numeric 7 Get The Integer Value Of it
                $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
            
                //Select All Data Depend On This Id
                $check = checkItem('userID', 'users', $userid);

                //If there record for this id show the form
                if( $check > 0) { 

                    $stmt = $con->prepare("DELETE FROM users WHERE userID = :zuser");

                    $stmt->bindParam(":zuser",$userid);

                    $stmt->execute();

                    //echo Success Message
                     $theMsg = "<div class='alert alert-success'>".$stmt->rowCount() ." ". 'Record Deleted </div>';
                        echo '<div class="seperator"></div>';
                        echo '<div class="container mt-3 text-center">';
                            redirectHome($theMsg,'back',4);
                        echo '</div>';

                }else {
                    $theMsg= "<div class='alert alert-success'> This Id Is Not Exist </div>";
                        echo '<div class="seperator"></div>';
                        echo '<div class="container mt-3 text-center">';
                            redirectHome($theMsg,'back',4);
                        echo '</div>';
                }
            echo '</div>';

        } elseif($do == 'Activate'){//Activate Member Page

            echo '<h1 class="text-center"> Activate Member</h1>';
            echo '<div class="container">';
            
                //Check If Get Request userid Is Numeric 7 Get The Integer Value Of it
                $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
            
                //Select All Data Depend On This Id
                $check = checkItem('userID', 'users', $userid);

                //If there record for this id show the form
                if( $check > 0) { 

                    $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE userID = :zuser");

                    $stmt->bindParam(":zuser",$userid);

                    $stmt->execute();

                    //echo Success Message
                     $theMsg = "<div class='alert alert-success'> Member Approved <i class='fas fa-check-circle'></i></div>";
                        echo '<div class="seperator"></div>';
                        echo '<div class="container mt-3 text-center">';
                            redirectHome($theMsg,'back',4);
                        echo '</div>';

                }else {
                    $theMsg= "<div class='alert alert-success'> This Id Is Not Exist </div>";
                        echo '<div class="seperator"></div>';
                        echo '<div class="container mt-3 text-center">';
                            redirectHome($theMsg,4);
                        echo '</div>';
                }
            echo '</div>';
        }
    

            include $comp . 'footer.php';
        }else {
            header('location:index.php');
            exit();
        }   

ob_end_flush();

?>