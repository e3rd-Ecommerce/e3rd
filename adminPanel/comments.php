<?php

/*
===================================================
== Manage Comments Page
== You can  Edit | Delete |  Approve From Here
===================================================
*/

    ob_start();

    session_start();

    $pageTitle = "comments" ;

    if(isset($_SESSION['ID'])){ //هون بشيك اذا فيه سشين موجوده ولا لأ
        
        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage' ;

        // Start Manage Page
        if($do == 'Manage'){ //Manage Page 

           
            //Select All Users Except Admins
            $stmt = $con->prepare("SELECT
                                        comments.*,
                                        items.name AS item_name,
                                        users.UserName As member_name
                                   FROM 
                                        comments
                                   INNER JOIN 
                                        items 
                                    ON 
                                        items.item_ID = comments.item_ID
                                    INNER JOIN 
                                        users 
                                    ON 
                                        users.userID = comments.user_ID
                                    ORDER BY 
                                        c_ID DESC
                                   ");

            //execute The Statement
            $stmt->execute();

            //Assign To Varaible
            $rows = $stmt->fetchAll();

            if(!empty($rows)){
        
            ?>

            <h2 class="text-center">Manage Comments</h2>
            <div class="container"> 
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr> 
                            <td>#ID</td>
                            <td>Comment</td>
                            <td>Item Name</td>
                            <td>User Name</td>
                            <td>Added Date</td>
                            <td>control</td>
                        </tr> 

                        <?php
                            foreach($rows as $row){
                                echo "<tr>";
                                    echo "<td>". $row['c_ID'] . "</td>";
                                    echo "<td>". $row['comment'] . "</td>";
                                    echo "<td>". $row['item_name'] . "</td>";
                                    echo "<td>". $row['member_name'] . "</td>";
                                    echo "<td>".$row['comment_date']."</td>";
                                    echo "<td>
                                            <a href='comments.php?do=Edit&comid=". $row['c_ID'] ."' class='btn btn-info edit'><i class='fa fa-edit'></i> Edit</a>
                                            <a href='comments.php?do=Delete&comid=$row[c_ID]' class='btn btn-danger delete confirm'><i class='fa fa-close'></i> Delete </a>";
                                            if ($row['status'] == 0){
                                               echo "<a href='?do=Approve&comid=" . $row['c_ID'] ."' class='btn btn-info activate'><i class='fas fa-check'></i> Approve</a>";
                                            }         
                                   echo "</td>";
                                echo "</tr>";
                            }
                        ?>
                        
                    </table>
                </div>
            </div>
    <?php }else {

                $theMsg='<div class="nice-message">Theres No Comments To show here</div>';
                echo '<div class="seperator"></div>';
                echo '<div class="container mt-3 text-center">';
                    echo $theMsg;
                echo '</div>';
          }
        
         }elseif ($do == 'Edit'){//Edit Page 

            //Check If Get Request userid Is Numeric 7 Get The Integer Value Of it
            $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
           
            //Select All Data Depend On This Id
            $stmt = $con->prepare("SELECT * FROM comments WHERE c_ID = ? ");

            //Execute Query
            $stmt->execute(array($comid));

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
                                        <h3 class="text-center py-4">Edit Comment</h3>
                                    </div>

                                    <div class="card-body">
                                        <form action="?do=Update" method="POST">
                                            <input type="hidden" name="comid" value="<?php echo $comid;?>">
                                            <textarea class="form-control mb-2" name="comment" required="required"><?php echo $row['comment'];?></textarea>
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

            echo '<h1 class="text-center"> Update comment</h1>';
            echo '<div class="container">';
                if($_SERVER['REQUEST_METHOD'] == 'POST'){

                    //Get Vraiables Form The Form
                    $comid = $_POST['comid'];
                    $comment = $_POST['comment'];
                                      
                    //Update The DataBase With This Info
                    $stmt = $con->prepare("UPDATE comments SET comment = ? WHERE c_ID = ?");
                    $stmt->execute(array($comment,$comid));

                    //echo Success Message
                    $theMsg= "<div class='alert alert-success'>".$stmt->rowCount() ." ". 'Record Updated </div>';
                    echo '<div class="seperator"></div>';
                    echo '<div class="container mt-3 text-center">';
                        redirectHome($theMsg,'back',4);
                    echo '</div>';

                } else {
                        $theMsg= "<div class='alert alert-danger'>Sorry You Cant Browse This Page Directly </div>";
                        echo '<div class="seperator"></div>';
                        echo '<div class="container mt-3 text-center">';
                            redirectHome($theMsg,4);
                        echo '</div>';
                }
            echo '</div>';
        
        } elseif($do == 'Delete'){//Delete Member Page

            echo '<h1 class="text-center"> Delete comment</h1>';
            echo '<div class="container">';
            
                //Check If Get Request comid Is Numeric Get The Integer Value Of it
                $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
            
                //Select All Data Depend On This Id
                $check = checkItem('c_ID', 'comments', $comid);

                //If there record for this id show the form
                if( $check > 0) { 

                    $stmt = $con->prepare("DELETE FROM comments WHERE c_ID = :zcomment");

                    $stmt->bindParam(":zcomment",$comid);

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
                            redirectHome($theMsg,null,'comments',4);
                        echo '</div>';
                }
            echo '</div>';

        } elseif($do == 'Approve'){//Approve Comment Page

            echo '<h1 class="text-center"> Approve Comment</h1>';
            echo '<div class="container">';
            
                //Check If Get Request comid Is Numeric Get The Integer Value Of it
                $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
            
                //Select All Data Depend On This Id
                $check = checkItem('c_ID', 'comments', $comid);

                //If there record for this id show the form
                if( $check > 0) { 

                    $stmt = $con->prepare("UPDATE comments SET status = 1 WHERE c_ID = :zcomment");

                    $stmt->bindParam(":zcomment",$comid);

                    $stmt->execute();

                    //echo Success Message

                        $theMsg = "<div class='alert alert-success'> Comment Approved <i class='fas fa-check-circle'></i></div>";
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

        } elseif ($do == 'Custom'){ //Custom comment Page 

             //Check If Get Request comid Is Numeric Get The Integer Value Of it
             $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
            
             //Select All Data Depend On This Id
             $check = checkItem('c_ID', 'comments', $comid);
           
            if($check > 0) {

                    //Select The comment to edit or delete or Approve it
                    $stmt = $con->prepare("SELECT
                                            comments.*,
                                            items.name AS item_name,
                                            users.UserName As member_name
                                        FROM 
                                            comments
                                        INNER JOIN 
                                             items 
                                        ON 
                                            items.item_ID = comments.item_ID
                                        INNER JOIN 
                                            users 
                                        ON 
                                            users.userID = comments.user_ID
                                        WHERE c_ID = ? 
                    ");

                    //execute The Statement
                    $stmt->execute(array($comid));

                    //Assign To Varaible
                    $rows = $stmt->fetchAll();

                    ?>

                    <h2 class="text-center">Manage Comment</h2>
                    <div class="container"> 
                        <div class="table-responsive">
                            <table class="main-table text-center table table-bordered">
                            <tr> 
                            <td>#ID</td>
                            <td>Comment</td>
                            <td>Item Name</td>
                            <td>User Name</td>
                            <td>Added Date</td>
                            <td>control</td>
                            </tr> 

                            <?php
                                foreach($rows as $row){
                                    echo "<tr>";
                                        echo "<td>". $row['c_ID'] . "</td>";
                                        echo "<td>". $row['comment'] . "</td>";
                                        echo "<td>". $row['item_name'] . "</td>";
                                        echo "<td>". $row['member_name'] . "</td>";
                                        echo "<td>".$row['comment_date']."</td>";
                                        echo "<td>
                                                <a href='comments.php?do=Edit&comid=". $row['c_ID'] ."' class='btn btn-info edit'><i class='fa fa-edit'></i> Edit</a>
                                                <a href='comments.php?do=Delete&comid=$row[c_ID]' class='btn btn-danger delete'><i class='fa fa-close'></i> Delete </a>";
                                                if ($row['status'] == 0){
                                                echo "<a href='?do=Approve&comid=" . $row['c_ID'] ."' class='btn btn-info activate'><i class='fas fa-check'></i> Approve</a>";
                                                }         
                                    echo "</td>";
                                    echo "</tr>";
                                    }
                            ?>

                            </table>
                        </div>
                    </div>
    <?php  } else{
                 $theMsg= "<div class='alert alert-success'> This Id Is Not Exist </div>";
                 echo '<div class="seperator"></div>';
                 echo '<div class="container mt-3 text-center">';
                     redirectHome($theMsg,4);
                 echo '</div>';
          }
            
        
    }
    
            include $comp . 'footer.php';
        }else {
            header('location:index.php');
            exit();
        }   

ob_end_flush();

?>