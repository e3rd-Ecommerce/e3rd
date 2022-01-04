
<?php 
session_start(); 

$NOnavbar= '';
$pageTitle = "login"; 




// اذا كنت مسجل يفوت على طول من غير تسجيل دخول 
if (isset($_SESSION['username'])) { 
    header('Location: dashbord.php')  ; 
    exit() ;
}   

include 'init.php' ;


//  check if user coming form http post request
if ($_SERVER['REQUEST_METHOD']=='POST') {
    $username = $_POST['user'];
    $password = $_POST['pass'];
    $hashedpass =sha1($password); // تشفير الباس للحماية 
    

    //  if the user exist in database

    $stmt = $con->prepare("SELECT
                                userid,username,password  
                            FROM 
                                users 
                            WHERE
                                username = ?  
                            AND
                                password = ? 
                            and 
                                groupid = 1
                            LIMIT  1 ");

        $stmt->execute(array($username, $hashedpass));
        $row = $stmt->fetch(); // جلب البيانات  
        $count=$stmt->rowCount(); 
        

        if ($count > 0 ) {

            $_SESSION['username']  = $username ; // register session name
            $_SESSION['ID'] = $row['userid'] ; // register session id
            header('Location: dashbord.php') ; 
            exit(); 
        }

}

?>


<form action="<?php echo $_SERVER['PHP_SELF'] ;?>" class="login" method="POST">
    <h4 class="text-center" >admin login</h4>
    <input class="form-control input-lg" type="text" name="user" placeholder="Username" autocomplete="off">
    <input class="form-control input-lg"  type="password" name="pass" placeholder="password" autocomplete="new-password">
    <input class="btn btn-primary input-lg"  type="submit" value="login">

</form>



<?php include $tpl . 'footer.php' ; ?>