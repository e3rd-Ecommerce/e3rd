<?php 

    session_start();
    $noNavbar = '';
    $pageTitle = 'Login';
    
    if(isset($_SESSION['Username'])){ //هون بشيك اذا فيه سشين موجوده ولا لأ
        header('location: dashboard.php');
    }
    
    include 'init.php';

    //Check if user Comming from Post Request
    if($_SERVER['REQUEST_METHOD'] == "POST"){

        $username = $_POST['user'];
        $password = $_POST['pass'];
        $hashedPass =sha1($password); 
        
        //Check is The User Exist In DataBase
        $stmt = $con->prepare("SELECT 
                                    userID,UserName, Password
                               FROM 
                                    users
                               WHERE 
                                   UserName = ?
                               AND 
                                   Password = ?
                               AND 
                                   GroupID =1
                               LIMIT 1");

        $stmt->execute(array($username, $hashedPass));
        $row = $stmt -> fetch();
        $count = $stmt->rowCount(); //هاي الرو كاونت فنكشن جاهزه بتجيبلي عدد الاسطر اللي لقاها 
        
        //If count > 0 this mean that the database Contain Record About This UserName
        if($count > 0 ){
            $_SESSION['Username'] = $username;
            $_SESSION['ID'] = $row['userID'];
            header('location: dashboard.php');
            exit();
        }
    }
 ?>

<div class="container">
        <div class="row">
            <div class="col-lg-6 m-auto">
                <div class="card bg-light mt-5">

                    <div class="card-titel bg-primary text-white">
                        <h3 class="text-center py-4">Admin Login</h3>
                    </div>

                    <div class="card-body">
                        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                            <input type="text" name="user" placeholder="Enter User Name" class="form-control my-2 " autocomplete="off">
                            <input type="password" name="pass" placeholder="Enter Your Passowrd" class="form-control my-2" autocomplete="off">
                            <input class="btn btn-primary btn-block" type="submit" value="Login"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require_once $comp . "footer.php"; ?>