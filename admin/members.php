<?php 

// manage members page 
// you can add | edit | delete members from here


ob_start(); 
session_start() ; 

$pageTitle= 'members' ; 

if (isset($_SESSION['username'])) {


    include_once 'init.php'; 

            $do= '' ; 

            if(isset($_GET['do'])) {

                $do=  $_GET['do']; 

            }else  {
                $do ='manage' ; // الصفحة الرئيسية
            }

            if($do == 'manage'){  //manage member page 

                $query ='';


                if(isset($_GET['page']) && $_GET['page'] == 'panding'){

                    $query = 'AND regstatus = 0 ' ; 
                }

                $stmt = $con->prepare("SELECT * FROM users WHERE groupid != 1 $query ORDER BY userid DESC ")  ; //جلب جميع الاعضاء ما عدا الادمن 

                $stmt->execute(); 

                //assign to variable 
                $row = $stmt->fetchAll();
            
                if(! empty($row)){

            ?>

                        <div class="row mt-5">
                        <div class="col-12 grid-margin">
                        <div class="card">
                        <div class="card-body"> 
                        <h2 class="card-title mt-5 text-center text-muted">manage members</h2>
                        <div class="table-responsive">
                        <table class="table">

                            <tr>
                                <td>#id</td>
                                <td>user name</td>
                                <td>email</td>
                                <td>full name</td>
                                <td>registerd date</td>
                                <td>control</td>
                            </tr>
                            <?php 
                            foreach($row as $row) {

                                echo "<tr>" ; 
                                echo "<td>" . $row['userid'] . "</td>" ;
                                echo "<td>" . $row['username'] . "</td>" ; 
                                echo "<td>" . $row['email'] . "</td>" ;
                                echo "<td>" . $row['fullname'] . "</td>" ;
                                echo "<td>" . $row['date'] . "</td>" ; 
                                echo "<td>
                                <a href='members.php?do=edit&userid=" .$row['userid'] . "'class='badge badge-outline-warning'>edit</a>
                                <a href='members.php?do=delete&userid=" .$row['userid'] . "'class='badge badge-outline-danger mr-1'>del</a>"; 

                                if($row['regstatus'] ==0 ){

                                echo "<a href='members.php?do=activate&userid=" .$row['userid'] . "'class='badge badge-outline-success'>activate</a>";

                                }

                                echo  "</td>";
                                echo "</tr>" ; 
                            } 
                            ?>
                            
                        </table>
                        
                        </div>
                        <div>
                        <a href="members.php?do=add" class="btn btn-primary mt-4"> new members </a> 
                        </div>
                        </div>
                    </div>
                </div>
                

        <?php  } else {
            echo '<div class="container">' ; 
            echo '<div class="nice-message"> there\'s No member to show </div> '; 
            if ( !(isset($_GET['page']) && $_GET['page'] == 'panding')){
            echo '<a href="members.php?do=add" class="btn btn-primary"> new members </a>'; 
            }


            echo '</div>' ; 
        } ?>

<?php
            }


            elseif($do == 'add') {  //add new members  ?>

                <h1 class="text-center"><?php echo lang('add-members') ; ?></h1> 
                <div class="container">
                    <form action="?do=insert" class="" method="POST">

                        <!-- start username field -->
                        <div class=" row mb-3  ">
                            <label class="col-sm-2 col-lg-1 col-form-label"><?php echo lang('username') ; ?></label>
                            <div class="col-sm-10 col-md-4"> 
                                <input type="text" name="username" class="form-control"  autocomplete="off" required="required" placeholder="username to login into shop"/>
                            </div>
                        </div>
                        <!-- end username -->

                        <!-- start password field -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-lg-1 col-form-label"><?php echo lang('password') ; ?></label>
                            <div class="col-sm-10 col-md-4"> 
                                <input type="password" name="password" class="form-control"  autocomplete="new-password" required="required" placeholder="password must be hard"/>
                                <i class="bi bi-eye"></i>
                            </div>
                        </div>
                        <!-- end password -->

                            <!-- start email field -->
                            <div class=" row mb-3">
                            <label class="col-sm-2 col-lg-1 col-form-label"><?php echo lang('email') ; ?></label>
                            <div class="col-sm-10 col-md-4"> 
                                <input type="email" name="email"  class="form-control" required="required" placeholder="email must be valid"  />
                            </div>
                        </div>
                        <!-- end email -->

                            <!-- start full name field -->
                            <div class="row mb-3 ">
                            <label class="col-sm-2 col-lg-1 col-form-label"> <?php echo lang('full-name') ; ?></label>
                            <div class="col-sm-10 col-md-4"> 
                                <input type="text" name="fullname"  class="form-control" placeholder="full name appear in your profile page" />
                            </div>
                        </div>
                        <!-- end full name -->

                        <!-- start save btn field -->
                        <div class="mt-3">
                            <div class="col-sm-offset-2 col-sm-10 "> 
                                <input type="submit" value="add member" class="btn btn-info " />
                            </div>
                        </div>
                        <!-- end save btn -->
                        
                    </form>
                </div>

            <?php
            
            }  elseif ($do == 'insert') {
            // insert member page

                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                    
                    echo  "<h1 class='text-center'> <?php echo lang('add-members') ; ?> </h1> " ; 
                    echo '<div class="container">' ;

                    // get variables from the form

                    $user  = $_POST['username'];
                    $pass  = $_POST['password'];
                    $email = $_POST['email'];
                    $name  = $_POST['fullname'];
                    $hashpass = sha1($_POST['password']) ;  // تشفير للباس وعشان الانبتي تاع الباس الفاضي الو هاش

                    // فالديشكن للفورم 
                    $formErros = array() ; 
                    if(empty($user)){
                        $formErros[] = 'username cant be <strong> empty </strong>' ;
                    }
                    if(strlen($user) < 4 ){
                        $formErros[] = 'username cant be less <strong> 4 characters </strong>';
                    }
                    if(strlen($user) > 15 ){
                        $formErros[] = 'username cant be more than <strong> 4 characters </strong> ';
                    }
                    if(empty($pass)){
                        $formErros[] = 'password cant be <strong> empty </strong>' ;
                    }
                    if(empty($name)){
                        $formErros[] = 'name cant be <strong> empty </strong> ' ;
                    }
                    if(empty($email)){
                        $formErros[] = ' email cant be <strong> empty </strong> ' ;
                    }
                    
                    // loop into error array and echo it 
                    foreach($formErros as $error) {
                        echo '<div class="alert alert-danger">' . $error . '</div>' ; 
                    }
    
    
                    // لو مفيش ايرور بالفالديشن كمل     
                    if(empty($formErros)){

                        // check if user exist in database
                        $check =  checkitem("username","users" , $user); 
                        if($check == 1){

                            $theMsg=  '<div class="alert alert-danger" sorry this user is exist </div> '  ; 
                            redircthome($theMsg , 'back') ; // فنكشن 

                        }else {
                            
                        

                                // insert user info in database
                                
                                $stmt = $con->prepare("INSERT INTO 
                                                        users(username,password,email,fullname,regstatus , date)
                                                        VALUE(:user , :pass , :email  ,:fname , 1 , now() )
                                                        ") ; 
                                $stmt->execute(array(
                                    'user' => $user ,
                                    'pass' => $hashpass , 
                                    'email' =>$email , 
                                    'fname'=> $name 
                                )) ; 
                            
            
                                
                                // echo success message
                                echo "<div class='container'>" ;
                                $theMsg = "<div class='alert alert-success'>"  .   $stmt->rowCount() . ' Record inserted  </div>' ; // عدد الريكورد التي تمت 
                                redircthome($theMsg ,'back') ; // فنكشن 
                                echo '</div>' ;

                }
                    }
    
                }
                else {

                    $theMsg =  '<div class="alert alert-danger"> sorry you cant browes this page </div>' ;
                    redircthome($theMsg ,'back') ; // فنكشن 
                    }
                echo '</div>' ; 




        }   elseif($do == 'edit'){   //edit page



                // بتحطط من الجيت ريكويست انو اليوزر اي دي رقم و بجيب الانتجر فاليو 
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) :  0  ; 
                // بختار البيانات من الداتا بيس من الاي دي المعين
            $stmt = $con->prepare("SELECT
                                * 
                            FROM 
                                users 
                            WHERE
                                userid = ?  
                            LIMIT  1 ");
        
        $stmt->execute(array($userid));   // اكسكيوت للكويري

        $row = $stmt->fetch(); // جلب البيانات  
        
        $count=$stmt->rowCount();  // ان في حالة الرو كاونت اكبر من صفر معناها في يوزر بالرقم دا
                
                if( $count > 0 ) { ?>

                <h1 class="text-center"><?php echo lang('Edit-members') ; ?></h1> 
                <div class="container">
                    <form action="?do=update" class="" method="POST">
                        <!-- اليوزر اي دي الي رح اختارو واحدث من خلالو عن الاي دي  -->
                        <input type="hidden" name="userid" value="<?php echo $userid ?>" >
                        <!-- start username field -->
                        <div class=" row mb-3  ">
                            <label class="col-sm-2 col-lg-1 col-form-label"><?php echo lang('username') ; ?></label>
                            <div class="col-sm-10 col-md-4"> 
                                <input type="text" name="username" class="form-control" value="<?php echo $row['username']; ?>" autocomplete="off" required="required" />
                            </div>
                        </div>
                        <!-- end username -->
                        <!-- start password field -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-lg-1 col-form-label"><?php echo lang('password') ; ?></label>
                            <div class="col-sm-10 col-md-4"> 
                                <input type="hidden" name="oldpassword" value="<?php echo $row['password'] ?>" />
                                <input type="password" name="newpassword" class="form-control"  autocomplete="new-password"  placeholder="leave blank if you font want to change"/>
                            </div>
                        </div>
                        <!-- end password -->
                            <!-- start email field -->
                            <div class=" row mb-3">
                            <label class="col-sm-2 col-lg-1 col-form-label"><?php echo lang('email') ; ?></label>
                            <div class="col-sm-10 col-md-4"> 
                                <input type="email" name="email" value="<?php echo $row['email']; ?>" class="form-control" required="required"  />
                            </div>
                        </div>
                        <!-- end email -->
                            <!-- start full name field -->
                            <div class="row mb-3 ">
                            <label class="col-sm-2 col-lg-1 col-form-label"> <?php echo lang('full-name') ; ?></label>
                            <div class="col-sm-10 col-md-4"> 
                                <input type="text" name="fullname" value="<?php echo $row['fullname']; ?>" class="form-control" />
                            </div>
                        </div>
                        <!-- end full name -->
                        <!-- start save btn field -->
                        <div class="mt-3">
                            <div class="col-sm-offset-2 col-sm-10 "> 
                                <input type="submit" value="save" class="btn btn-info btn-lg" />
                            </div>
                        </div>
                        <!-- end save btn -->
                        
                    </form>
                </div>

<?php 
            } 
            // لو ما في اي دي بالداتا بيس
            else {

                echo "<div class='container'> "  ; 
                $theMsg = '<div class="alert alert-danger" theres no such id </div> '; 
                redircthome($theMsg) ; // فنكشن 
                echo '</div>' ; 
            }
        } 

        elseif ($do == 'update') { //update page
        echo  "<h1 class='text-center'> <?php echo lang('Edit-members') ; ?> </h1> " ; 
        echo '<div class="container">' ;
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                // get variables from the form
                $id    = $_POST['userid'];
                $user  = $_POST['username'];
                $email = $_POST['email'];
                $name  = $_POST['fullname'];
                // password 
                $pass= '' ; 
                if(empty($_POST['newpassword'])) {
                    $pass = $_POST['oldpassword'] ; 
                }
                else {
                    $pass = sha1($_POST['newpassword']) ; 
                }

                // فالديشكن للفورم 
                $formErros = array() ; 


                if(empty($user)){
                    $formErros[] = '<div class="alert alert-danger"> username cant be <strong> empty </strong> </div>' ;
                }
                if(strlen($user) < 4 ){
                    $formErros[] ='<div class="alert alert-danger"> username cant be less <strong> 4 characters </strong> </div>';
                }
                if(strlen($user) > 15 ){
                    $formErros[] ='<div class="alert alert-danger"> username cant be more than <strong> 4 characters </strong>  </div>';
                }
                if(empty($name)){
                    $formErros[] = '<div class="alert alert-danger"> name cant be <strong> empty </strong>  </div>' ;
                }
                if(empty($email)){
                    $formErros[] = '<div class="alert alert-danger"> email cant be <strong> empty </strong>  </div>' ;
                }
                
                // loop into error array and echo it 
                foreach($formErros as $error) {
                    echo $error ; 
                }


                // لو مفيش ايرور بالفالديشن كمل جيب البيانات من داتا بيس
                if(empty($formErros)){

// /منشيك اذا الاسم الي بدنا نعدلو موجود لاسم مستخدم اخر 
                    $stmt2 = $con->prepare("SELECT
                                            * 
                                            FROM 
                                            users
                                            WHERE 
                                            username = ?
                                            AND 
                                            userid != ? "); 
                    $stmt2->execute(array($user,$id));
                    $count=$stmt2->rowCount(); 

                
                    
                    if($count == 1) {

                        $theMsg = "<div class='alert alert-danger'> sorry this user is exist </div>";
                        redircthome($theMsg , 'back') ; // فنكشن 

                    } 

                    else {

                            // update the database with this info 
                            $stmt = $con->prepare("UPDATE
                                users
                            SET 
                                username = ? ,
                                email = ? ,
                                fullname = ? ,
                                password =? 
                            WHERE 
                                userid = ? "); 

                            $stmt->execute(array($user,$email,$name,$pass,$id)) ; 
                            // echo success message

                            $theMsg= "<div class='alert alert-success'>"  .   $stmt->rowCount() . ' Record Updated  </div>' ; // عدد الريكورد التي تمت 
                            redircthome($theMsg ,'back') ; // فنكشن 

                    
                    }

                }

            }
            else {

                $theMsg =  "<div class='alert alert-danger'> sorry you cant browes this page </div>" ;
                redircthome($theMsg) ; // فنكشن 


                }

            echo '</div>' ; 
    } 
        elseif ($do == 'delete') {
            

                echo  "<h1 class='text-center'> delete members </h1> " ; 
                echo  '<div class="container">' ;
                    // delete member page

                            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) :  0  ; 
                                // بختار البيانات من الداتا بيس من الاي دي المعين
                        
                                $check =  checkitem('userid','users',$userid) ;  // فنكشن

                                if($check > 0 ) { 

                                    $stmt = $con->prepare("DELETE FROM users WHERE userid= :userid ") ; 
                                    // bindparam لربط البراميتر
                                    $stmt->bindParam(":userid" , $userid) ; 
                                    $stmt->execute();  //تنفيد 

                                    $theMsg = "<div class='alert alert-success'>"  .   $stmt->rowCount() . ' Record delete </div>' ; // عدد الريكورد التي تمت 
                                    redircthome($theMsg ,'back') ; // فنكشن 
                                

                                } else { // اذا الاي دي مش موجود 
                                    $theMsg = "<div class='alert alert-danger'> this id is not exist </div> " ;
                                    redircthome($theMsg ,'back') ; // فنكشن 
                                }

                echo '</div>' ; 
            
        } elseif($do == 'activate') {

            echo  "<h1 class='text-center'> activate members </h1> " ; 
            echo  '<div class="container">' ;
                // activate member page

                        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) :  0  ; 
                            // بختار البيانات من الداتا بيس من الاي دي المعين
                    
                            $check =  checkitem('userid','users',$userid) ;  // فنكشن

                            if($check > 0 ) { 

                                $stmt = $con->prepare("UPDATE users SET regstatus = 1 WHERE userid = ?  ") ; 
                                // bindparam لربط البراميتر
                
                                $stmt->execute(array($userid));  //تنفيد 

                                $theMsg = "<div class='alert alert-success'>"  .   $stmt->rowCount() . ' Record updated </div>' ; // عدد الريكورد التي تمت 
                                redircthome($theMsg) ; // فنكشن 
                            
                            } else { // اذا الاي دي مش موجود 
                                $theMsg = "<div class='alert alert-danger'> this id is not exist </div> " ;
                                redircthome($theMsg) ; // فنكشن 
                            }
            echo '</div>' ;
                
        }



        
    include_once $tpl . 'footer.php' ; 

} 
        else { 

    // echo 'you are not authorized to view this page  ' ; 
    header('location: index.php') ;
    exit(); 

            }
ob_end_flush();
?> 