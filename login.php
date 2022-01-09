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
                                ");
            
            $stmt->execute(array($user,$hashedPass));

            $get = $stmt->fetch();
            
            $count = $stmt->rowCount();

            //Check if there's record in database
            if($count > 0){

                $_SESSION['user'] = $user; //Register The Session name
        
                $_SESSION['uid'] = $get['userID']; //Register The user id in the session
                
                header('Location: index.php'); //Rediredvt To Homepage
                exit();
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

                    $formErrors[] = 'Sorry, The password does not match 
                    ';
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

    <main class="login-page">
        <div class="login-block">
            <h1><span class="selected" data-class="login">Login</span> | <span  data-class="signup">Signup</span></h1>
            <!--Start Login Form -->
            <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                <div class="form-group">
                    <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user ti-user"></i></span>
                    <input name="user" type="text" class="form-control" placeholder="Username">
                    </div>
                </div>
                
                
                <hr class="hr-xs">

                <div class="form-group">
                    <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-lock ti-unlock"></i></span>
                    <input name="pass" type="password" class="form-control" placeholder="Your password" autocomplete="new-password">
                    </div>
                </div>

                <input class="btn btn-primary btn-block" name="login" type="submit" value="Login">

                <!-- <div class="login-footer">
                    <h6>Or register with</h6>
                    <ul class="social-icons">
                        <li><a class="facebook" href="#"><i class="fab fa-facebook"></i></a></li>
                        <li><a class="twitter" href="#"><i class="fab fa-twitter"></i></a></li>
                        <li><a class="linkedin" href="#"><i class="fab fa-linkedin"></i></a></li>
                    </ul>
                </div> -->

            </form>
            <!--End Login Form -->

            <!--Start Signup form -->
            <form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                <div class="form-group">
                    <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user ti-user"></i></span>
                    <input  
                            pattern = ".{4,}" 
                            title = "Username must be 4 Chars or greater"
                            name="username" 
                            type="text" 
                            class="form-control" 
                            placeholder="Choose Username"
                            required /> <!-- Front End Validation -->
                    </div>
                </div>

                <hr class="hr-xs">

                <div class="form-group">
                    <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user ti-user"></i></span>
                    <input  
                            type="email" 
                            name="email" 
                            class="form-control" 
                            placeholder="Your email"
                            required />
                    </div>
                </div>
                
                <hr class="hr-xs">

                <div class="form-group">
                    <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-lock ti-unlock"></i></span>
                    <input    
                            minlength = "4"
                            type="password" 
                            name="password" 
                            class="form-control" 
                            placeholder="Choose password" 
                            autocomplete="new-password"
                            required />
                    </div>
                </div>

                <hr class="hr-xs">

                <div class="form-group">
                    <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-lock ti-unlock"></i></span>
                    <input 
                            minlength = "4"
                            type="password" 
                            name="password2" 
                            class="form-control" 
                            placeholder="Write password again" 
                            autocomplete="new-password"
                            required />
                    </div>
                </div>

                <input class="btn btn-primary btn-block" name="signup" type="submit" value="Sign up">

                <div class="login-footer">
                    <h6>Or register with</h6>
                    <ul class="social-icons">
                        <li><a class="facebook" href="#"><i class="fab fa-facebook"></i></a></li>
                        <li><a class="twitter" href="#"><i class="fab fa-twitter"></i></a></li>
                        <li><a class="linkedin" href="#"><i class="fab fa-linkedin"></i></a></li>
                    </ul>
                </div>

            </form>
            <div class="the-errors text-center">
                <?php 
                    if(!empty($formErrors)){

                        foreach($formErrors as $error){
                            echo '<div class=msg error>'.$error .'</div>';
                        }
                    }

                    if(isset($successMsg)){
                        echo '<div class="msg success">'.$successMsg.'</div>';
                    }

                ?>
            </div>
        </div>
    </main>

<?php
require_once $comp . 'footer.php';
ob_end_flush();
?>

