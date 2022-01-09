<?php
ob_start();
session_start(); 
$pageTitle = "login"; 

// اذا كنت مسجل يفوت على طول من غير تسجيل دخول 
//if (isset($_SESSION['user'])) { 
//    header('Location: index.php')  ; 
//} 
include_once 'user/init.php' ;

//  check if user coming form http post request
if ($_SERVER['REQUEST_METHOD']=='POST') {

            if(isset($_POST['login'])) {
                $user = $_POST['username'];
                $pass = $_POST['password'];
                $hashedpass =sha1($pass); // تشفير الباس للحماية ,
                
                //  if the user exist in database
                $stmt = $con->prepare("SELECT
                                        userid,username,password  
                                    FROM 
                                        users 
                                    WHERE
                                        username = ?  
                                    AND
                                        password = ? 
                                    ");
                $stmt->execute(array($user, $hashedpass));
                $get = $stmt->fetch();
                $count=$stmt->rowCount(); 
                if ($count > 0 ) {

                    $_SESSION['user']  = $user ; // register session name
                    $_SESSION['uid'] = $get['userid'] ; // register user id in session
                    header('Location: user/profile.php') ; 
                    exit(); 
            }

    } else { // sign up 

        $formerror = array();

        $username  = $_POST['username']; 
        $password  = $_POST['password'];
        $password2 = $_POST['password2'];
        $email     = $_POST['email'] ; 
        // username
        if(isset($username)){
            $filteruser = filter_var($_POST['username'],FILTER_SANITIZE_STRING);

            if(strlen($filteruser) < 3){
                $formerror[] = 'username can\'t must be larger than 4 character ' ; 
            }

        } 
        // password
        if(isset($password) && isset($password2)){
            
            if(empty($password)){
                $formerror[]= 'sorry password is empty' ; 
            }
                $pass1 = sha1($password)  ;  
                $pass2 = sha1($password2) ; 

                if($pass1 !== $pass2){
                    $formerror[]= 'sorry password is not match' ; 
                }
        } 
        // email 
        if(isset($email)){
            $filteremail =filter_var ($_POST['email'],FILTER_SANITIZE_EMAIL);

            if(filter_var($filteremail,FILTER_SANITIZE_EMAIL) != true){
                $formerror[]= 'This email is not valid' ; 
            }

        } 

        // لو مفيش اي ايرور بضيف اليوزر 

        if(empty($formerror)){

            // check if user exist in database
            $check =  checkitem("username","users" , $username); 
            if($check == 1){

                $formerror[]= ' sorry this user is exist '  ; 
            

            }else {
                
            

                    // insert user info in database
                    
                    $stmt = $con->prepare("INSERT INTO 
                                            users(username,password,email,regstatus , date)
                                            VALUE(:user , :pass , :email  , 0 , now() )
                                            ") ; 
                    $stmt->execute(array(
                        'user' => $username ,
                        'pass' => sha1($password) , 
                        'email' =>$email , 
                        
                    )) ; 
                

                    
                    // echo success message
                    $successmsg = 'congrats you are now registerd user' ; 

    }
        }
        // 
        
        
    }

}





?>


<div class="container login-page">
    <h1 class="text-center">
    <span class="selected" data-class="login">Login</span> | 
    <span data-class="signup">signup</span> 
    </h1>

    <!-- form login -->
    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ;?>"  method="POST" >
        <div class="input-container">

        <input  
        class="form-control" 
        type="text"     
        name="username" 
        autocomplete="off" 
        placeholder="type your username"/>

        </div>
        <input  
        class="form-control" 
        type="password" 
        name="password" 
        autocomplete="new-password"
        placeholder="type your password" />

        <input 
        class="form-control btn btn-primary"
        name="login" 
        type="submit" 
        value="login" />
    </form>
    <!-- end form login -->


    <!-- form Signup -->
    <form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ;?>"  method="POST">
        <input 
        pattern= ".{4,8}"
        title="username must be between 4 & 8 characters"
        class="form-control" 
        type="text"     
        name="username" 
        autocomplete="off" 
        placeholder="type your username"
        required/>

        <input  
        minlength="4"
        class="form-control" 
        type="password" 
        name="password" 
        autocomplete="new-password"
        placeholder="type a complex password" 
        required/>

        <input  
        minlength="4"
        class="form-control" 
        type="password" 
        name="password2" 
        autocomplete="new-password"
        placeholder="type a password again" 
        required/>

        <input  
        class="form-control" 
        type="email" 
        name="email" 
        placeholder="type a valid email" />


        <input 
        class="form-control btn btn-success" 
        name="signup"
        type="submit" 
        value="signup" />
    </form>
    <!-- end form signup -->
    <div class="the-errors text-center">
        <?php 
        if(!empty($formerror)){
            foreach($formerror as $error){
                echo  '<div class="alert alert-danger" role="alert">' .  $error . '</div>  <br>'; 
            }
        }

        if(isset($successmsg)){
            echo '<div class="alert alert-success" role="alert">' . $successmsg . '</div>'  ;
        }
        ?>
    </div>
</div>


<?php include_once 'user/'.$tpl.'footer.php' ;
ob_end_flush();
?>



