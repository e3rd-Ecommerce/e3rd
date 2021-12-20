<?php 

// manage comment page 
// you can   edit | delete | approve comment from here 

ob_start(); 
session_start() ; 

$pageTitle= 'comment' ; 

if (isset($_SESSION['username'])) {


    include_once 'init.php'; 

            $do= '' ; 

            if(isset($_GET['do'])) {

                $do=  $_GET['do']; 

            }else  {
                $do ='manage' ; // الصفحة الرئيسية

            }


            if($do == 'manage'){  //comment member page 



                $stmt = $con->prepare("SELECT
                                        comment.*, items.name AS item_name, 
                                        users.username AS member
                                        FROM 
                                        comment 
                                        INNER JOIN 
                                            items
                                        ON
                                            items.item_id=comment.item_id
                                        INNER JOIN 
                                        users
                                        ON
                                        users.userid= comment.user_id
                                        ")  ;  

                $stmt->execute(); 

                //assign to variable 
                $row = $stmt->fetchAll();
            
            
            ?>

                <h1 class="text-center"><?php echo lang('comment-members') ; ?></h1> 
                <div class="container">
                    <div class="tabel-responsive">
                        <table class="main-table text-center table table-bordered">
                            <tr>
                                <td>id</td>
                                <td>comment</td>
                                <td>item name</td>
                                <td>user name</td>
                                <td>added date</td>
                                <td>control</td>
                            </tr>

                            <?php 
                            foreach($row as $row) {

                                echo "<tr>" ; 
                                echo "<td>" . $row['c_id'] . "</td>" ;
                                echo "<td>" . $row['comment'] . "</td>" ;
                                echo "<td>" . $row['item_name'] . "</td>" ;  
                                echo "<td>" . $row['member'] . "</td>" ;
                                echo "<td>" . $row['comment_date'] . "</td>" ;

                                echo "<td>
                                <a href='comment.php?do=edit&comid=" .$row['c_id'] . "'class='btn btn-success'>edit</a>
                                <a href='comment.php?do=delete&comid=" .$row['c_id'] . "'class='btn btn-danger confirm'>del</a>"; 

                                if($row['status'] ==0 ){

                                echo "<a href='comment.php?do=approve&comid="
                                .$row['c_id'] . 
                                "'class='btn btn-info activate'>approve</a>";

                                }

                                echo  "</td>";
                                echo "</tr>" ; 


                            } 
                            ?>
                            
                        </table>
                        </div>
                    </div>

        

<?php
            } elseif($do == 'edit'){   //edit page

                // بتحطط من الجيت ريكويست انو اليوزر اي دي رقم و بجيب الانتجر فاليو 
            $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) :  0  ; 
                // بختار البيانات من الداتا بيس من الاي دي المعين
            $stmt = $con->prepare("SELECT
                                * 
                            FROM 
                                comment 
                            WHERE
                                c_id = ?  
                            ");
        
        $stmt->execute(array($comid));   // اكسكيوت للكويري

        $row = $stmt->fetch(); // جلب البيانات  
        
        $count=$stmt->rowCount();  // ان في حالة الرو كاونت اكبر من صفر معناها في يوزر بالرقم دا
                
                if( $count > 0 ) { ?>

                <h1 class="text-center"><?php echo lang('Edit-comment') ; ?></h1> 
                <div class="container">
                    <form action="?do=update" class="" method="POST">
                        <!-- اليوزر اي دي الي رح اختارو واحدث من خلالو عن الاي دي  -->
                        <input type="hidden" name="comid" value="<?php echo $comid ?>" >
                        <!-- start comment field -->
                        <div class=" row mb-3  ">
                            <label id="comment" class="col-sm-2 col-lg-1 col-form-label"><?php echo lang('comment') ; ?></label>
                            <div class="col-sm-10 col-md-4">
                                <textarea class="form-control" name="comment" id="comment">
                                    <?php echo $row['comment'] ?>
                                </textarea> 
                            </div>
                        </div>
                        <!-- end comment -->
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
        echo  "<h1 class='text-center'> <?php echo lang('update-comment') ; ?> </h1> " ; 
        echo '<div class="container">' ;
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                // get variables from the form
                $comid    = $_POST['comid'];
                $comment  = $_POST['comment'];
        
                    // update the database with this info 
                    $stmt = $con->prepare("UPDATE
                                                comment
                                            SET 
                                                comment = ? 
                                            WHERE 
                                                c_id = ? "); 

                    $stmt->execute(array($comment,$comid)) ; 
                    // echo success message
                    
                    $theMsg= "<div class='alert alert-success'>"  .   $stmt->rowCount() . ' Record Updated  </div>' ; // عدد الريكورد التي تمت 
                    redircthome($theMsg ,'back') ; // فنكشن 
                
                

                

            }
            else {

                $theMsg =  "<div class='alert alert-danger'> sorry you cant browes this page </div>" ;
                redircthome($theMsg) ; // فنكشن 


                }

            echo '</div>' ; 
    } 
        elseif ($do == 'delete') {
            

                echo  "<h1 class='text-center'> delete comment </h1> " ; 
                echo  '<div class="container">' ;
                    // delete member page

                            $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) :  0  ; 
                                // بختار البيانات من الداتا بيس من الاي دي المعين
                        
                                $check =  checkitem('c_id','comment',$comid) ;  // فنكشن

                                if($check > 0 ) { 

                                    $stmt = $con->prepare("DELETE FROM comment WHERE c_id= $comid ") ; 
                                    $stmt->execute();  //تنفيد 

                                    $theMsg = "<div class='alert alert-success'>"  .   $stmt->rowCount() . ' Record delete </div>' ; // عدد الريكورد التي تمت 
                                    redircthome($theMsg ,'back') ; // فنكشن 
                                

                                } else { // اذا الاي دي مش موجود 
                                    $theMsg = "<div class='alert alert-danger'> this id is not exist </div> " ;
                                    redircthome($theMsg ,'back') ; // فنكشن 
                                }

                echo '</div>' ; 
            
        } elseif($do == 'approve') {

            echo  "<h1 class='text-center'> approve members </h1> " ; 
            echo  '<div class="container">' ;
                // activate member page

                        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) :  0  ; 
                            // بختار البيانات من الداتا بيس من الاي دي المعين
                    
                            $check =  checkitem('c_id','comment',$comid) ;  // فنكشن

                            if($check > 0 ) { 

                                $stmt = $con->prepare("UPDATE comment SET status = 1 WHERE c_id = ?  ") ; 
                                // bindparam لربط البراميتر
                
                                $stmt->execute(array($comid));  //تنفيد 

                                $theMsg = "<div class='alert alert-success'>"  .   $stmt->rowCount() . ' Record approve </div>' ; // عدد الريكورد التي تمت 
                                redircthome($theMsg , 'back') ; // فنكشن 
                            
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