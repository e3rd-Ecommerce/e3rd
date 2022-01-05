<?php 
// items page
ob_start(); 
session_start() ; 

$pageTitle= 'Items' ; 


if (isset($_SESSION['username'])) {

    include_once 'init.php';

    $do= '' ; 

    if(isset($_GET['do'])) {

        $do=  $_GET['do']; 

    }else  {
        $do ='manage' ; // الصفحة الرئيسية

    }

    if($do == 'manage'){ 

                $stmt = $con->prepare("SELECT 
                                        items.*,
                                        categories.name AS catgory_name,
                                        users.username AS user_name
                                        FROM 
                                            items 
                                        INNER JOIN 
                                            categories 
                                        ON 
                                            categories.id =items.cat_id 
                                        INNER JOIN 
                                            users 
                                        ON 
                                            users.userid = items.member_id
                                        ORDER BY item_id DESC")  ; //جلب البيانات مع عمل اينر جوين للتيبلات الثانية 

                $stmt->execute(); 

                //assign to variable 
                $items = $stmt->fetchAll();
            
            
            ?>

                        <div class="row mt-5">
                        <div class="col-12 grid-margin">
                        <div class="card">
                        <div class="card-body"> 
                        <h2 class="card-title mt-5 text-center text-muted">manage items</h2>
                        <div class="table-responsive">
                        <table class="table">
                        <thead>
                            <tr>
                                <td>#id</td>
                                <td>name</td>
                                <td>desciption</td>
                                <td>price</td>
                                <td>adding date</td>
                                <td>Category</td>
                                <td>Username</td>
                                <td>control</td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php 
                            foreach($items as $item) {

                                echo "<tr>" ; 
                                echo "<td>" . $item['item_id'] . "</td>" ;
                                echo "<td>" . $item['name'] . "</td>" ; 
                                echo "<td>" . $item['description'] . "</td>" ;
                                echo "<td>" . $item['price'] . "</td>" ;
                                echo "<td>" . $item['add_date'] . "</td>" ; 
                                echo "<td>" . $item['catgory_name'] . "</td>" ; 
                                echo "<td>" . $item['user_name'] . "</td>" ; 
                                echo "<td>
                                <a href='items.php?do=edit&itemid=" .$item['item_id'] . "'class='badge badge-outline-warning'>edit</a>
                                <a href='items.php?do=delete&itemid=" .$item['item_id'] . "'class='badge badge-outline-danger mr-1'>del</a>"; 

                                if($item['approve'] ==0 ){

                                    echo "<a 
                                    href='items.php?do=approve&itemid=" .$item['item_id'] . "'
                                    class='badge badge-outline-success'>Approve</a>";
    
                                    }
                                echo  "</td>";
                                echo "</tr>" ; 


                            } 
                            ?>
                            </tbody>
                        </table>
                        </div>

                        <a href="items.php?do=add" class="btn btn-primary m-3"> new item </a> 
                    </div>
                    </div>
                    </div>
                    </div>

                

<?php
    } elseif ($do == 'add') {    ?>

                <div class="container adddd">
                <h1 class=""><?php echo lang('add-new-item') ; ?></h1> <hr>

                    <form action="?do=insert" class="" method="POST">

                        <!-- start name field -->
                        <div class=" row mb-3  ">
                            <label class="col-sm-2 col-md-2 col-lg-2 col-form-label"><?php echo lang('name-catgory') ; ?></label>
                            <div class="col-sm-10 col-md-4"> 
                                <input 
                                type="text"
                                name="name"
                                class="form-control"  
                                placeholder="name of the items"/>
                            </div>
                        </div>
                        <!-- end name -->

                        <!-- start description items -->
                        <div class=" row mb-3  ">
                            <label class="col-sm-2 col-md-2 col-lg-2 col-form-label"><?php echo lang('description-item') ; ?></label>
                            <div class="col-sm-10 col-md-4"> 
                                <input
                                type="text" 
                                name="description" 
                                class="form-control"  
                                placeholder="description of the item"/>
                            </div>
                        </div>
                        <!-- end description -->

                        <!-- start price items -->
                        <div class=" row mb-3  ">
                            <label class="col-sm-2 col-md-2 col-lg-2 col-form-label"><?php echo lang('price-item') ; ?></label>
                            <div class="col-sm-10 col-md-4"> 
                                <input
                                type="text" 
                                name="price" 
                                class="form-control"  
                                placeholder="price of the item"/>
                            </div>
                        </div>
                        <!-- end price -->

                        <!-- start country items -->
                        <div class=" row mb-3  ">
                            <label class="col-sm-2 col-md-2 col-lg-2 col-form-label"><?php echo lang('countryy-item') ; ?></label>
                            <div class="col-sm-10 col-md-4"> 
                                <input
                                type="text" 
                                name="country" 
                                class="form-control"  
                                placeholder="country of the item"/>
                            </div>
                        </div>
                        <!-- end country -->

                        <!-- start status items -->
                        <div class=" row mb-3  ">
                            <label class="col-sm-2 col-md-2 col-lg-2 col-form-label"><?php echo lang('status-item') ; ?></label>
                            <div class="col-sm-10 col-md-4"> 
                                <select name="status" id="" class="">
                                    <option value="0"></option>
                                    <option value="1">new</option>
                                    <option value="2">like new</option>
                                    <option value="3">used</option>
                                    <option value="4">old</option>
                                </select>
                            </div>
                        </div>
                        <!-- end status -->

                        <!-- start member field -->
                        <div class=" row mb-3  ">
                            <label class="col-sm-2 col-md-2 col-lg-2 col-form-label"><?php echo lang('member-item') ; ?></label>
                            <div class="col-sm-10 col-md-4"> 
                                <select name="member" id="" class="">
                                    <option value="0"></option>
                                    <?php 

                                        $stmt = $con->prepare("SELECT * FROM users");
                                        $stmt->execute();
                                        $users= $stmt->fetchAll();
                                        foreach($users as $user){
                                            echo "<option value='".$user['userid']."'>".$user['username']."</option>";
                                        }
                                
                                    ?>
                                    
                                </select>
                            </div>
                        </div>
                        <!-- end member -->

                        <!-- start category field -->
                        <div class=" row mb-3  ">
                            <label class="col-sm-2 col-md-2 col-lg-2 col-form-label"><?php echo lang('category-filed') ; ?></label>
                            <div class="col-sm-10 col-md-4"> 
                                <select name="category" id="" class="">
                                    <option value="0"></option>
                                    <?php 

                                        $stmt2 = $con->prepare("SELECT * FROM categories");
                                        $stmt2->execute();
                                        $cats= $stmt2->fetchAll();
                                        foreach($cats as $cat){
                                            echo "<option value='".$cat['id']."'>".$cat['name']."</option>";
                                        }
                                
                                    ?>
                                    
                                </select>
                            </div>
                        </div>
                        <!-- end category -->
                        

                        <!-- start submit field -->
                        <div class="mt-3">
                            <div class="col-sm-offset-2 col-sm-10 "> 
                                <input type="submit" value="add item" class="add-new-cat btn btn-info  " />
                            </div>
                        </div>
                        <!-- end submit-->
                        
                    </form>
                </div>


    <?php 

        
    }elseif ($do == 'insert') {

        // insert items page

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
                    
            echo  "<h1 class='text-center'> <?php echo lang('add-items') ; ?> </h1> " ; 
            echo '<div class="container">' ;

            // get variables from the form

            $name    = $_POST['name'];
            $desc    = $_POST['description'];
            $price   = $_POST['price'];
            $country = $_POST['country'];
            $status = $_POST['status'];
            $member = $_POST['member'];
            $category = $_POST['category'];


            

            
            // فالديشكن للفورم 
            $formErros = array() ; 
            if(empty($name)){
                $formErros[] = 'name cant be <strong> empty </strong>' ;
            }
            if(empty($desc)){
                $formErros[] = ' description cant be <strong> empty </strong>';
            }
            if(empty($price)){
                $formErros[] = 'price  cant be  <strong> empty </strong> ';
            }
            if(empty($country)){
                $formErros[] = 'country  cant be <strong> empty </strong> ' ;
            }
            if($status == 0 ){
                $formErros[] = ' you must choose the  <strong> status </strong> ' ;
            }
            if($member == 0 ){
                $formErros[] = ' you must choose the  <strong> member </strong> ' ;
            }
            if($category == 0 ){
                $formErros[] = ' you must choose the  <strong> category </strong> ' ;
            }

            
            // loop into error array and echo it 
            foreach($formErros as $error) {
                $theMsg = '<div class="alert alert-danger">' . $error . '</div>' ; 
                redircthome($theMsg , 'back') ; // فنكشن 

            }


            // لو مفيش ايرور بالفالديشن كمل     
            if(empty($formErros)){
            
                        // insert user info in database
                        $stmt = $con->prepare("INSERT INTO 
                                        items(name,description,price,country_made,status ,cat_id ,member_id,add_date)
                                        VALUE(:zname , :zdesc , :price  ,:country , :zstatus, :zcat , :zmember, now() )
                                        ") ; 
                        $stmt->execute(array(
                            'zname'   => $name ,
                            'zdesc'   => $desc , 
                            'price'   =>$price , 
                            'country' => $country ,
                            'zstatus' => $status ,
                            'zcat'    => $category ,
                            'zmember' => $member 
                        )) ; 
                        // echo success message
                        echo "<div class='container'>" ;
                        $theMsg = "<div class='alert alert-success'>"  .   $stmt->rowCount() . ' Record inserted  </div>' ; // عدد الريكورد التي تمت 
                        redircthome($theMsg ,'back') ; // فنكشن 
                        echo '</div>' ;

            
            }
        }
        else {

            $theMsg =  '<div class="alert alert-danger"> sorry you cant browes this page </div>' ;
            redircthome($theMsg) ; // فنكشن 
            }
        echo '</div>' ; 

      //// //// //// //// //// //// //// 

    }elseif ($do == 'edit') {

          // بتحطط من الجيت ريكويست انو الايتيم اي دي رقم و بجيب الانتجر فاليو 
            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) :  0  ; 
                // بختار البيانات من الداتا بيس من الاي دي المعين
            $stmt = $con->prepare("SELECT
                                * 
                            FROM 
                                items
                            WHERE
                                item_id = ?  
                            ");
        
        $stmt->execute(array($itemid));   // اكسكيوت للكويري

        $item = $stmt->fetch(); // جلب البيانات  
        
        $count=$stmt->rowCount();  // ان في حالة الرو كاونت اكبر من صفر معناها في يوزر بالرقم دا
                
        if( $count > 0 ) { ?>

                
            <!-- <div class="container okmmm"> -->
            <div class="container adddd">
                
                <form action="?do=update" class="mt-7" method="POST">
                <h1>Edit item</h1><hr>
                <input type="hidden" name="itemid" value="<?php echo $itemid ?>" >

                <!-- start name field -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-lg-2 col-form-label"><?php echo lang('name-catgory') ; ?></label>
                    <div class="col-sm-10 col-md-4"> 
                        <input 
                        type="text"
                        name="name"
                        class="form-control"  
                        required="required"
                        placeholder="name of the items"
                        value="<?php echo $item['name'] ?>"/>
                    </div>
                </div>
                <!-- end name -->
                <!-- start description items -->
                <div class=" row mb-3  ">
                    <label class="col-sm-2 col-lg-2 col-form-label"><?php echo lang('description-item') ; ?></label>
                    <div class="col-sm-10 col-md-4"> 
                        <input
                        type="text" 
                        name="description" 
                        class="form-control"
                        required="required"  
                        placeholder="description of the item"
                        value="<?php echo $item['description'] ?>"/>
                    </div>
                </div>
                <!-- end description -->
                <!-- start price items -->
                <div class=" row mb-3  ">
                    <label class="col-sm-2 col-lg-2 col-form-label"><?php echo lang('price-item') ; ?></label>
                    <div class="col-sm-10 col-md-4"> 
                        <input
                        type="text" 
                        name="price" 
                        class="form-control"
                        required="required"  
                        placeholder="price of the item"
                        value="<?php echo $item['price'] ?>"/>
                    </div>
                </div>
                <!-- end price -->
                <!-- start country items -->
                <div class=" row mb-3  ">
                    <label class="col-sm-2 col-lg-2 col-form-label"><?php echo lang('countryy-item') ; ?></label>
                    <div class="col-sm-10 col-md-4"> 
                        <input
                        type="text" 
                        name="country" 
                        class="form-control" 
                        placeholder="country of the item"
                        value="<?php echo $item['country_made'] ?>"/>
                    </div>
                </div>
                <!-- end country -->
                <!-- start status items -->
                <div class=" row mb-3  ">
                    <label class="col-sm-2 col-lg-2 col-form-label"><?php echo lang('status-item') ; ?></label>
                    <div class="col-sm-10 col-md-4"> 
                        <select name="status" id="" class="">
                            <option value="0"></option>
                            <option value="1" <?php if($item['status']== 1){echo 'selected' ;} ?>>new</option>
                            <option value="2" <?php if($item['status']== 2){echo 'selected' ;} ?>>like new</option>
                            <option value="3" <?php if($item['status']== 3){echo 'selected' ;} ?>>used</option>
                            <option value="4" <?php if($item['status']== 4){echo 'selected' ;} ?>>old</option>
                        </select>
                    </div>
                </div>
                <!-- end status -->
                <!-- start member field -->
                <div class=" row mb-3  ">
                    <label class="col-sm-2 col-lg-2 col-form-label"><?php echo lang('member-item') ; ?></label>
                    <div class="col-sm-10 col-md-4"> 
                        <select name="member" id="" class="">
                            <option value="0"></option>
                            <?php 
                                $stmt = $con->prepare("SELECT * FROM users");
                                $stmt->execute();
                                $users= $stmt->fetchAll();
                                foreach($users as $user){
                                    echo "<option value='".$user['userid']."' "; 
                                    if($item['member_id']== $user['userid']){echo 'selected' ;}
                                    echo  ">".$user['username']."</option>";
                                }
                            ?>
                            
                        </select>
                    </div>
                <!-- </div> -->
                <!-- end member -->

                <!-- start category field -->
                <div class=" row mb-3  ">
                    <label class="col-sm-2 col-lg-2 col-form-label"><?php echo lang('category-filed') ; ?></label>
                    <div class="col-sm-10 col-md-4"> 
                        <select name="category" id="" class="">
                            <option value="0"></option>
                            <?php 
                                $stmt2 = $con->prepare("SELECT * FROM categories");
                                $stmt2->execute();
                                $cats= $stmt2->fetchAll();
                                foreach($cats as $cat){
                                    echo "<option value='".$cat['id']."' ";
                                    if($item['cat_id'] == $cat['id']){echo 'selected' ;}
                                    echo ">".$cat['name']."</option>";
                                }
                        
                            ?>
                            
                        </select>
                    </div>
                </div>
                <!-- end category -->
                

                <!-- start submit field -->
                <div class="m-3">
                    <div class="col-sm-offset-2 col-sm-10 "> 
                        <input type="submit" value="save item" class="add-new-cat btn btn-info  " />
                    </div>
                </div>
                <!-- end submit-->
                
            </form>
        
<?php
//comment التحت الايتيم   
                $stmt = $con->prepare("SELECT
                            comment.*, users.username AS member
                            FROM 
                            comment 
                            INNER JOIN 
                            users
                            ON
                            users.userid= comment.user_id
                            WHERE
                            item_id = $itemid")  ;  
                $stmt->execute(); 

                //assign to variable 
                $row = $stmt->fetchAll();

                if(!empty($row)){ // اذا كان عندي تعليقات اطبعهم واذا فش اخفيه كامل 

                
            
            ?>


                
                    <div class="tabel-responsive">
                        <table class="text-center table table-bordered">
                            <tr>
                                <td>comment</td>
                                <td>user name</td>
                                <td>added date</td>
                                <td>control</td>
                            </tr>

                            <?php 
                            foreach($row as $row) {

                                echo "<tr>" ; 
                                echo "<td>" . $row['comment'] . "</td>" ;
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
                    <?php } ?>
                        </div>



<!-- end comment التحت الايتم  -->

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
                
            }elseif ($do == 'update') {

                echo  "<h1 class='text-center'> <?php echo lang('update-item') ; ?> </h1> " ; 
                echo '<div class="container">' ;

                    if($_SERVER['REQUEST_METHOD'] == 'POST'){
                        // get variables from the form
                        $id       = $_POST['itemid'];
                        $name     = $_POST['name'];
                        $desc     = $_POST['description'];
                        $price    = $_POST['price'];
                        $country  = $_POST['country'];
                        $status   = $_POST['status'];
                        $member   = $_POST['member'];
                        $category = $_POST['category'];

                        // فالديشكن للفورم 
                    $formErros = array() ; 
                    if(empty($name)){
                        $formErros[] = 'name cant be <strong> empty </strong>' ;
                    }
                    if(empty($desc)){
                        $formErros[] = ' description cant be <strong> empty </strong>';
                    }
                    if(empty($price)){
                        $formErros[] = 'price  cant be  <strong> empty </strong> ';
                    }
                    if(empty($country)){
                        $formErros[] = 'country  cant be <strong> empty </strong> ' ;
                    }
                    if($status == 0 ){
                        $formErros[] = ' you must choose the  <strong> status </strong> ' ;
                    }
                    if($member == 0 ){
                        $formErros[] = ' you must choose the  <strong> member </strong> ' ;
                    }
                    if($category == 0 ){
                        $formErros[] = ' you must choose the  <strong> category </strong> ' ;
                    }

                    
                    // loop into error array and echo it 
                    foreach($formErros as $error) {
                        $theMsg = '<div class="alert alert-danger">' . $error . '</div>' ; 
                        redircthome($theMsg , 'back') ; // فنكشن 

                    }


                        // لو مفيش ايرور بالفالديشن كمل جيب البيانات من داتا بيس
                        if(empty($formErros)){
                            // update the database with this info 
                            $stmt = $con->prepare("UPDATE   
                                                        items
                                                    SET 
                                                        name         = ?,
                                                        description  = ?,
                                                        price        = ?,
                                                        country_made = ?,
                                                        status       = ?,
                                                        member_id    = ?,
                                                        cat_id       = ? 
                                                        
                                                    WHERE 
                                                        item_id = ?  "); 

                            $stmt->execute(array($name,$desc,$price,$country,$status,$member,$category,$id)) ; 
                            // echo success message
                            
                            $theMsg= "<div class='alert alert-success'>"  .   $stmt->rowCount() . ' Record Updated  </div>' ; // عدد الريكورد التي تمت 
                            redircthome($theMsg ,'back') ; // فنكشن 
                        
                        

                        }

                    }
                    else {

                        $theMsg =  "<div class='alert alert-danger'> sorry you cant browes this page </div>" ;
                        redircthome($theMsg) ; // فنكشن 


                        }

                    echo '</div>' ; 
        
        

    }elseif ($do == 'delete') {

        
        echo  "<h1 class='text-center'> delete item </h1> " ; 
        echo  '<div class="container">' ;
            // delete member page

                    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) :  0  ; 
                        // بختار البيانات من الداتا بيس من الاي دي المعين
                
                        $check =  checkitem('item_id ','items',$itemid) ;  // فنكشن

                        if($check > 0 ) { 

                            $stmt = $con->prepare("DELETE FROM items WHERE item_id= $itemid ") ; 
                            // bindparam لربط البراميتر
                            // $stmt->bindParam(":itemid" , $itemid) ; 
                            $stmt->execute();  //تنفيد 

                            $theMsg = "<div class='alert alert-success'>"  .   $stmt->rowCount() . ' Record delete </div>' ; // عدد الريكورد التي تمت 
                            redircthome($theMsg ,'back') ; // فنكشن 
                        

                        } else { // اذا الاي دي مش موجود 
                            $theMsg = "<div class='alert alert-danger'> this id is not Exist </div> " ;
                            redircthome($theMsg ,'back') ; // فنكشن 
                        }

        echo '</div>' ; 

        
    }elseif ($do == 'approve') {    


        echo  "<h1 class='text-center'> approve item </h1> " ; 
            echo  '<div class="container">' ;
                // activate member page

                        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) :  0  ; 
                            // بختار البيانات من الداتا بيس من الاي دي المعين
                    
                            $check =  checkitem('item_id ','items',$itemid) ;  // فنكشن

                            if($check > 0 ) { 

                                $stmt = $con->prepare("UPDATE items SET approve = 1 WHERE item_id = ?  ") ; 
                                
                
                                $stmt->execute(array($itemid));  //تنفيد 

                                $theMsg = "<div class='alert alert-success'>"  .   $stmt->rowCount() . ' Record approve </div>' ; // عدد الريكورد التي تمت 
                                redircthome($theMsg, 'back') ; // فنكشن 
                            
                            } else { // اذا الاي دي مش موجود 
                                $theMsg = "<div class='alert alert-danger'> this id is not exist </div> " ;
                                redircthome($theMsg ,'back') ; // فنكشن 
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
