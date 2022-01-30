<?php

    ob_start();
    session_start();
    $pageTitle = 'Login';
    
    if(isset($_SESSION['user'])){ //هون بشيك اذا فيه سشين موجوده ولا لأ
        header('location: index.php');
    }

    require_once 'init.php';

    //Check if user Comming from Post Request
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        
        if(isset($_POST['login'])){
            $user = $_POST['user'];
            $pass = $_POST['pass'];
            $hashedPass =sha1($pass); 

            //Check if User is exist in data base
            $stmt = $con->prepare("SELECT
                                        userID,UserName,Password
                                FROM
                                        users
                                WHERE 
                                        UserName = ?
                                AND 
                                        PASSWORD = ?
                                AND 
                                        RegStatus = 1
                                ");
            
            $stmt->execute(array($user,$hashedPass));

            $get = $stmt->fetch();
            
            $count = $stmt->rowCount();

            //Check if there's record in database
            if($count > 0){

                $_SESSION['user'] = $user; //Register The Session name
        
                $_SESSION['uid'] = $get['userID']; //Register The user id in the session
                
                header('Location: index.php'); //Rediredct To Homepage
                exit();
            } else{
                $theMsg = '<div class="alert alert-danger mt-2 text-center">Waiting For Approve!</div>';
               echo $theMsg;
            }
        } else {
                
            $formErrors = array();

            $username = $_POST['username'];
            $password = $_POST['password'];
            $password2 = $_POST['password2'];
            $email = $_POST['email'];


            if(isset($username)){

                $filterdUser = filter_var($username, FILTER_SANITIZE_STRING);
                
                if(strlen($filterdUser) < 4){

                    $formErrors[] = "Username Must Be Larger Than 4 Characters";
                }
            }

            if(isset($password) && isset($password2)){

                if(empty($_POST['password'])){
                    
                    $formErrors[] = 'Sorry Password Can\'t be empty';
                }

                $pass1 = sha1($password);
                $pass2 = sha1($password2);

                if($pass1 !== $pass2){

                    $formErrors[] = 'Sorry, The password does not match ';
                }
            }

            if(isset($email)){

                $filterdEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
                
                if(filter_var($filterdEmail, FILTER_VALIDATE_EMAIL) != true){

                    $formErrors[] = "This Email is not Valid";
                }
            }

            
            //Check if there is No Error Proceed The User information insert in database
            if(empty($formErrors)){

                //Check If User Exist In DataBase
                $check = checkItem("UserName", "users", $username);

                if($check == 1){
                    $formErrors[] = 'Sorry This Username Is Exist';
                } else {
                    
                        //insert User Info into DataBase
                        $stmt = $con->prepare("INSERT INTO 
                                                users(UserName,Password,Email, RegStatus,regDate)
                                                VALUES(:zuser, :zpass, :zmail, 0, now())");

                        $stmt->execute(array(
                            'zuser' => $username,
                            'zpass' => $pass1,
                            'zmail' => $email
                        ));

                        //set Success Message
                        $successMsg = 'Congrats Registered Done Successfully';                    
                }
            }

            
        }

    }
        
?>

<!-- ********************************************************************* -->

    <!-- Start All Title Box -->
    <div class="all-title-box">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Checkout</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Shop</a></li>
                        <li class="breadcrumb-item active">Checkout</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- End All Title Box -->

    <!-- Start Cart  -->
    <div class="cart-box-main">
        <div class="container">


            <div class="row new-account-login">
                <div class="col-sm-6 col-lg-6 mb-3">
                    <div class="title-left">
                        <h3>Account Login</h3>
                    </div>
                    <h5><a data-toggle="collapse" href="#formLogin" role="button" aria-expanded="false">Click here to Login</a></h5>


                    <form class="mt-3 collapse review-form-box" id="formLogin" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="InputEmail" class="mb-0">UserName</label>
                                <input type="text" name="user" class="form-control" id="InputEmail" placeholder="Enter UserName"> </div>
                            <div class="form-group col-md-6">
                                <label for="InputPassword" class="mb-0">Password</label>
                                <input type="password" name="pass" class="form-control" id="InputPassword" placeholder="Password"> </div>
                        </div>
                        <button type="submit"  name="login" class="btn hvr-hover">Login</button>
                    </form>


                </div>
                <!-- done -->





                <div class="col-sm-6 col-lg-6 mb-3">
                    <div class="title-left">
                        <h3>Create New Account</h3>
                    </div>
                    <h5><a data-toggle="collapse" href="#formRegister" role="button" aria-expanded="false">Click here to Register</a></h5>


                    <form class="mt-3 collapse review-form-box" id="formRegister" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                        <div class="form-row">

                            <div class="form-group col-md-6">
                                <label for="InputName" class="mb-0">User Name</label>
                                <input type="text" class="form-control" id="InputName" 
                                pattern = ".{4,}" 
                                title = "Username must be 4 Chars or greater"
                                name="username" 
                                placeholder="User Name"
                                required> </div>

                                <div class="form-group col-md-6">
                                <label for="InputEmail1" class="mb-0">Email Address</label>
                                <input type="email" name="email" class="form-control" id="InputEmail1" placeholder="Enter Email" required> </div>

                            <div class="form-group col-md-6">
                                <label for="InputLastname" class="mb-0">password</label>
                                <input  minlength = "4"
                                        type="password" 
                                        name="password"  
                                        class="form-control" 
                                        id="InputLastname" 
                                        placeholder="new password" 
                                        required> </div>

                            <div class="form-group col-md-6">
                                <label for="InputPassword1" class="mb-0">Password</label>
                                <input type="password" 
                                minlength = "4"
                                name="password2" 
                                autocomplete="new-password"
                                required
                                class="form-control" id="InputPassword1" placeholder="Write password again" required> </div>

                        </div>
                        <button type="submit"  name="signup" class="btn hvr-hover">Register</button>
                    </form>


                </div>
            </div>
        </div>
    </div>
<!-- ********************************************************************* -->

            <div class="the-errors text-center">
                <?php 
                    if(!empty($formErrors)){

                        foreach($formErrors as $error){
                            echo '<div class="alert alert-danger">'.$error .'</div>';
                        }
                    }

                    if(isset($successMsg)){
                        echo '<div class="alert alert-success">'.$successMsg.'</div>';
                    }

                ?>
            </div>
        </div>
    </main>

<?php
require_once $comp . 'footer.php';
ob_end_flush();
?>

