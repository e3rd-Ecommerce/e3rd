<?php

/*
===================================================
== Manage Memebers Page
== You can Add | Edit | Delete Members From Here
===================================================
*/

    session_start();

    $pageTitle = "Members" ;

    if(isset($_SESSION['Username'])){ //هون بشيك اذا فيه سشين موجوده ولا لأ
        
        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage' ;

        // Start Manage Page
        if($do == 'Manage'){ //Manage Page
            

        } elseif ($do == 'Edit'){//Edit Page 

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
            if($stmt->rowCount() > 0) { ?>
                
                <div class="container">
                        <div class="row">
                            <div class="col-lg-6 m-auto">
                                <div class="card bg-light mt-5">
                                    <div class="card-titel bg-primary text-white">
                                        <h3 class="text-center py-4">Edit Member</h3>
                                    </div>

                                    <div class="card-body">
                                        <form action="?do=Update" method="POST">
                                            <input type="hidden" name="userid" value="<?php echo $userid;?>">
                                            <input type="text" name="username" placeholder="Username" class="form-control my-2" value="<?php echo $row['UserName'] ?>" autocomplete="off" required="required">
                                            <input type="hidden" name="oldpassword" value="<?php echo $row['Password']; ?>">
                                            <input type="password" name="newpassword" class="form-control my-2" autocomplete="new-password" placeholder="Leave it Blanck If You Dont Want To cahnge">
                                            <input type="email" name="email" placeholder="Email" class="form-control my-2" value="<?php echo $row['Email'] ?>" autocomplete="new-password" required="required">
                                            <input type="text" name="full" placeholder="Full Name" value="<?php echo $row['FullName'] ?>" class="form-control my-2" required="required">
                                            <button class="btn btn-success" >Save Changes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
    <?php

        //If there is no Such Id
        } else {

            echo 'Theres No Such ID';
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

                    //Password Trick Using Ternary condition
                    $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);
                    
                    //Validate The Form
                    $formErrors = array(); //Array To coolect Al Errors

                    if(strlen($user) < 4){
                        $formErrors[] = "<div class='alert alert-danger'>Username cant Be Less Than<strong> 4 Characters</strong></div>";
                    }

                    if(strlen($user) > 20){
                        $formErrors[] = "<div class='alert alert-danger'>Username cant Be More Than <strong>20 Characters</strong></div>";
                    }

                    if(empty($user)){
                        $formErrors[] =  "<div class='alert alert-danger'>Username cant be empty</div>";
                    }
                    if(empty($name)){
                        $formErrors[] = "<div class='alert alert-danger'>Full name cant bet empty</div>";
                    }
                    if(empty($email)){
                        $formErrors[]= "<div class='alert alert-danger'>email cant bet empty</div>";
                    }

                    //Print All Errors
                    foreach($formErrors as $error){
                        echo $error;
                    }

                    //Check if there is No Error Procees The Update Opertion
                    if(empty($formErrors)){
                        //Update The DataBase With This Info
                        $stmt = $con->prepare("UPDATE users SET UserName = ? , Email = ? , FullName = ?, Password = ? WHERE userID = ?");
                        $stmt->execute(array($user,$email,$name,$pass,$id));

                        // //echo Success Message
                        echo "<div class='alert alert-success'>".$stmt->rowCount() ." ". 'Record Updated </div>';
                    }

                } else {
                    echo "<div class='alert alert-danger'>You cant Browse This Page Directly</div>";
                }
            echo '</div>';
        
        }
    

            include $comp . 'footer.php';
        }else {
            header('location:index.php');
            exit();
        }   