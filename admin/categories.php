<?php 
// category page
ob_start(); 
session_start() ; 

$pageTitle= 'category' ; 


if (isset($_SESSION['username'])) {

    include_once 'init.php'; 

    $do= '' ; 

    if(isset($_GET['do'])) {

        $do=  $_GET['do']; 

    }else  {
        $do ='manage' ; // الصفحة الرئيسية

    }

    if($do == 'manage'){

        $sort = 'ASC' ;  // السورت الرئيسي الديفلت 

        $sort_array= array('ASC' , 'DESC')  ; // اريه للخيارات تصاعدي او تنازلي
        if(isset($_GET['sort']) && in_array($_GET['sort'],$sort_array)){// منشيك اذا كان في سورت بالاريه في المتغير سورت_اريه
            $sort = $_GET['sort'] ; 
        }


        $stmt2 = $con->prepare("SELECT * FROM categories WHERE parent= 0  ORDER BY ordering $sort "); 
        $stmt2->execute();
        $cats= $stmt2->fetchAll() ; ?>

                        <div class="row mt-5">
                        <h2 class="card-title mt-5 text-center text-muted">Manage Comment</h2>
                        <div class="container categories mt-5">
                <?php
                
                foreach($cats as $cat){
                    echo "<div class='cat'>" ;
                    echo '<div class="hidden-buttons">' ; 
                    echo "<a href='categories.php?do=edit&catid=".$cat['id']."' class='btn btn-primary '> Edit </a>" ;
                    echo "<a href='categories.php?do=delete&catid=".$cat['id']."' class='btn btn-danger '> del </a>" ;
                    echo "</div>";
                        echo '<h5>' . $cat['name'] .'</h5>'; 
                        echo "<div class='full-view'>" ;
                            echo "<p>" ;
                            if($cat['description'] == ''){
                                echo "this category has no description" ; 
                            } else {
                                echo  $cat['description'] ;
                            }
                                echo "</p>" ;
                                if($cat['visibility']==1){ echo '<span class="visibility">Hidden</span>' ;} ;

                                if($cat['allow_comment']==1){ echo '<span class="commenting">comment disabled</span>' ;} ;

                                if($cat['allow_ads']==1){ echo '<span class="advertises"> ads disabled </span>' ;} ;

                                // parent category
                        $childcats = getallfrom("*" , "categories" , "where parent= {$cat['id']}" , "" , "id" , "ASC") ; // فنكشن 
                        if(!empty($childcats)) {
                        echo '<h6 class="child-head"> child categories </h6>' ; 
                        echo "<ul class='list-unstyled child-cats'>" ; 
                        foreach($childcats as $c){ 
                            echo  "<li class='child-link'> 
                                        <a href='categories.php?do=edit&catid=".$c['id']."' >"  .  $c['name']  .  "</a> 
                                        <a href='categories.php?do=delete&catid=".$c['id']."' class='badge badge-outline-danger '> delete </a>
                                </li>"  ;
                        }
                        echo "</ul>"; 
                        }
                        echo "</div>" ;

                    echo "</div>";
                        echo '<hr>' ; 


                }

                ?>
            </div> 
            <a class="add-category btn btn-primary" href="categories.php?do=add">add new category</a>

                    </div>
                    </div>







<?php
    } elseif ($do == 'add') { ?>

                <div class="container adddd">
                <h1 class=""><?php echo lang('add-new-catgory') ; ?></h1> <hr>

                    <form action="?do=insert" class="" method="POST">

                        <!-- start name field -->
                        <div class=" row mb-3  ">
                            <label class="col-sm-2 col-md-2 col-lg-2 col-form-label"><?php echo lang('name-catgory') ; ?></label>
                            <div class="col-sm-10 col-md-4"> 
                                <input type="text" name="name" class="form-control"  autocomplete="off" required="required" placeholder="name of the catgory "/>
                            </div>
                        </div>
                        <!-- end name -->

                        <!-- start description field -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-md-2  col-lg-2 col-form-label"><?php echo lang('description-category') ; ?></label>
                            <div class="col-sm-10 col-md-4"> 
                                <input type="text" name="description" class="form-control"  placeholder="Descrip the category"/>
                            </div>
                        </div>
                        <!-- end description -->

                            <!-- start ordering field -->
                            <div class=" row mb-3">
                            <label class="col-sm-2 col-md-2 col-lg-2 col-form-label"><?php echo lang('ordering-category') ; ?></label>
                            <div class="col-sm-10 col-md-4"> 
                                <input type="text" name="ordering"  class="form-control"  placeholder="Number to arrange the categories"  />
                            </div>
                        </div>
                        <!-- end ordering -->
                        <!-- start category parent  -->
                            <div class=" row mb-3">
                                <label class="col-sm-2 col-md-2 col-lg-2 col-form-label">category type</label>
                                <div class="col-sm-10 col-md-4"> 
                                    <select name="parent" id="">
                                        <option value="0">none</option>
                                        <?php 
                                        $allcats  = getallfrom('*' , 'categories' , 'WHERE parent = 0' , '' , 'id' , 'ASC'  );
                                        foreach($allcats as $cat){
                                            echo "<option value='" . $cat['id']  ."'> " .$cat['name'] . " </option>"  ;
                                        }
                                        
                                        
                                        ?>
                                    </select>
                                </div>
                            </div>

                        <!-- end category parent  -->

                        <!-- start Visibility field -->
                            <div class="row mb-3 ">
                            <label class="col-sm-2 col-md-2 col-lg-2 col-form-label"> <?php echo lang('Visible-category') ; ?></label>
                            <div class="col-sm-10 col-md-4"> 
                                <div>
                                    <input id="vis-yes" type="radio" name="visibility" value="0" checked>
                                    <label for="vis-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="vis-no" type="radio" name="visibility" value="1" >
                                    <label for="vis-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- end Visibility  -->

                        <!-- start commenting field -->
                        <div class="row mb-3 ">
                            <label class="col-sm-2 col-md-2 col-lg-2 col-form-label"> <?php echo lang('commenting-category') ; ?></label>
                            <div class="col-sm-10 col-md-4"> 
                                <div>
                                    <input id="com-yes" type="radio" name="commenting" value="0" checked>
                                    <label for="com-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="com-no" type="radio" name="commenting" value="1" >
                                    <label for="com-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- end commenting  -->

                        <!-- start ads field -->
                        <div class="row mb-3 ">
                            <label class="col-sm-2 col-md-2 col-lg-2 col-form-label"> <?php echo lang('ads-catgory') ; ?></label>
                            <div class="col-sm-10 col-md-4"> 
                                <div>
                                    <input id="ads-yes" type="radio" name="ads" value="0" checked>
                                    <label for="ads-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="ads-no" type="radio" name="ads" value="1" >
                                    <label for="ads-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- end ads -->

                        <!-- start submit field -->
                        <div class="mt-3">
                            <div class="col-sm-offset-2 col-sm-10 "> 
                                <input type="submit" value="add category" class="add-new-cat btn btn-info  " />
                            </div>
                        </div>
                        <!-- end submit-->
                        
                    </form>
                </div>


    <?php 
    }elseif ($do == 'insert') { 

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
                    
            echo  "<h1 class='text-center'> <?php echo lang('add-category') ; ?> </h1> " ; 
            echo '<div class="container">' ;

            // get variables from the form

            $name     = $_POST['name'];
            $desc     = $_POST['description'];
            $parent   = $_POST['parent'] ; 
            $order    = $_POST['ordering'];
            $vasible  = $_POST['visibility'];
            $comment  = $_POST['commenting'];
            $ads      = $_POST['ads'];


                // check if category exist in database
            $check = checkitem("name","categories",$name) ; 
            if($check == 1){
                echo "<div class='container'>" ;
                $theMsg="<div class='alert alert-danger' sorry this category is exist </div>" ; 
                redircthome($theMsg ,'back') ; // فنكشن 
                echo '</div>' ;
            }else {
        
                // insert category info in database
                
                $stmt = $con->prepare("INSERT INTO 
                                        categories(name,description,parent,ordering,visibility, allow_comment ,allow_ads )
                                        VALUE(:zname ,:descr,:zparent,:ordering ,:visibility ,:comment ,:allow_ads )
                                        ") ; 
                $stmt->execute(array(
                    'zname' => $name ,
                    'descr' => $desc , 
                    'zparent' => $parent,
                    'ordering' =>$order , 
                    'visibility'=> $vasible ,
                    'comment' => $comment ,
                    'allow_ads' => $ads ,
                )) ; 
            

                
                // echo success message
                echo "<div class='container'>" ;
                $theMsg = "<div class='alert alert-success'>"  .   $stmt->rowCount() . ' Record inserted  </div>' ; // عدد الريكورد التي تمت 
                redircthome($theMsg ,'back') ; // فنكشن 
                echo '</div>' ;

        }
            

        }
        else {

            echo "<div class='container'>" ;
            $theMsg = '<div class="alert alert-danger"> sorry you cant browes this page </div>' ;
            redircthome($theMsg ,'back') ; // فنكشن 
            echo "</div>" ;

            }
            
        echo '</div>' ; 

        
    }elseif ($do == 'edit') {


     // بتحقق من الجيت ريكويست انو الكات اي دي رقم و بجيب الانتجر فاليو 
            $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) :  0  ; 
                // بختار البيانات من الداتا بيس من الاي دي المعين
            $stmt = $con->prepare("SELECT
                                * 
                            FROM 
                                categories
                            WHERE
                                id = ?  
                            ");
        
        $stmt->execute(array($catid));   // اكسكيوت للكويري

        $cat = $stmt->fetch(); // جلب البيانات  
        
        $count=$stmt->rowCount();  // ان في حالة الرو كاونت اكبر من صفر معناها في يوزر بالرقم دا
                
                if( $count > 0 ) { ?>

            <h1 class="text-center"><?php echo lang('edit-catgory') ; ?></h1> 
                <div class="container">
                    <form action="?do=update" class="" method="POST">
                        <input type="hidden" name="catid" value=<?php echo $catid ;  ?>>

                        <!-- start name field -->
                        <div class=" row mb-3  ">
                            <label class="col-sm-2 col-lg-1 col-form-label"><?php echo lang('name-catgory') ; ?></label>
                            <div class="col-sm-10 col-md-4"> 
                                <input type="text" name="name" class="form-control"   required="required" placeholder="name of the catgory" value="<?php echo $cat['name'] ?>"/>
                            </div>
                        </div>
                        <!-- end name -->

                        <!-- start description field -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-lg-1 col-form-label"><?php echo lang('description-category') ; ?></label>
                            <div class="col-sm-10 col-md-4"> 
                                <input type="text" name="description" class="form-control"  placeholder="Descrip the category" value="<?php echo $cat['description'] ?>" />
                            </div>
                        </div>
                        <!-- end description -->

                            <!-- start ordering field -->
                            <div class=" row mb-3">
                            <label class="col-sm-2 col-lg-1 col-form-label"><?php echo lang('ordering-category') ; ?></label>
                            <div class="col-sm-10 col-md-4"> 
                                <input type="text" name="ordering"  class="form-control"  placeholder="Number to arrange the categories"  value="<?php echo $cat['ordering'] ?>"  />
                            </div>
                        </div>
                        <!-- end ordering -->

                        <!-- start category parent  -->
                        <div class=" row mb-3">
                                <label class="col-sm-2 col-lg-1 col-form-label">category type </label>
                                <div class="col-sm-10 col-md-4"> 
                                    <select name="parent" id="">
                                        <option value="0">none</option>
                                        <?php 
                                        $allcats  = getallfrom('*' , 'categories' , 'WHERE parent = 0' , '' , 'id' , 'ASC'  );
                                        foreach($allcats as $c){
                                            echo "<option value='" . $c['id'] ."'";
                                            if($cat['parent'] == $c['id']) {
                                                echo 'selected';
                                            }
                                            echo ">" .$c['name'] . " </option>"  ;
                                        }
                                        
                                        ?>
                                    </select>
                                </div>
                            </div>

                        <!-- end category parent  -->

                        <!-- start Visibility field -->
                            <div class="row mb-3 ">
                            <label class="col-sm-2 col-lg-1 col-form-label"> <?php echo lang('Visible-category') ; ?></label>
                            <div class="col-sm-10 col-md-4"> 
                                <div>
                                    <input id="vis-yes" type="radio" name="visibility" value="0"  <?php if($cat['visibility'] == 0){ echo 'checked' ; } ?> >
                                    <label for="vis-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="vis-no" type="radio" name="visibility" value="1" <?php if($cat['visibility'] == 1){ echo 'checked' ; } ?> >
                                    <label for="vis-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- end Visibility  -->

                        <!-- start commenting field -->
                        <div class="row mb-3 ">
                            <label class="col-sm-2 col-lg-1 col-form-label"> <?php echo lang('commenting-category') ; ?></label>
                            <div class="col-sm-10 col-md-4"> 
                                <div>
                                    <input id="com-yes" type="radio" name="commenting" value="0" <?php if($cat['allow_comment'] == 0){ echo 'checked' ; } ?> >
                                    <label for="com-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="com-no" type="radio" name="commenting" value="1" <?php if($cat['allow_comment'] == 1){ echo 'checked' ; } ?>  >
                                    <label for="com-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- end commenting  -->

                        <!-- start ads field -->
                        <div class="row mb-3 ">
                            <label class="col-sm-2 col-lg-1 col-form-label"> <?php echo lang('ads-catgory') ; ?></label>
                            <div class="col-sm-10 col-md-4"> 
                                <div>
                                    <input id="ads-yes" type="radio" name="ads" value="0" <?php if($cat['allow_ads'] == 0){ echo 'checked' ; } ?> >
                                    <label for="ads-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="ads-no" type="radio" name="ads" value="1" <?php if($cat['allow_ads'] == 1){ echo 'checked' ; } ?> >
                                    <label for="ads-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- end ads -->

                        <!-- start submit field -->
                        <div class="mt-3">
                            <div class="col-sm-offset-2 col-sm-10 "> 
                                <input type="submit" value="Update" class="btn btn-info  " />
                            </div>
                        </div>
                        <!-- end submit-->
                        
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
        
    }elseif ($do == 'update') {   //UPDATE CATEGORY
        
        echo  "<h1 class='text-center'> <?php echo lang('update-category') ; ?> </h1> " ; 
        echo '<div class="container">' ;
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                // get variables from the form
                $id      = $_POST['catid'];
                $name    = $_POST['name'];
                $desc    = $_POST['description'];
                $parent   = $_POST['parent'];
                $order   = $_POST['ordering'];
                $vasible = $_POST['visibility']; 
                $comment = $_POST['commenting'];
                $ads     = $_POST['ads'];


//منشيك اذا الكاتيغوري موجود نفسها او لا 

                    $cat2 = $con->prepare("SELECT
                                            * 
                                            FROM 
                                            categories
                                            WHERE 
                                            name = ?
                                            AND 
                                            id != ? "); 
                    $cat2->execute(array($name,$id));
                    $count=$cat2->rowCount(); 

                
                    
                    if($count == 1) {

                        $theMsg = "<div class='alert alert-danger'> sorry this catgory is exist </div>";
                        redircthome($theMsg , 'back') ; // فنكشن 
 // نهاااااية التشييك
                    } else {
                    
            

                    // update the database with this info 
                    $stmt = $con->prepare("UPDATE
                                                categories
                                            SET 
                                                name           = ? ,
                                                description    = ? ,
                                                parent         = ? , 
                                                ordering       = ? ,
                                                visibility     = ? ,
                                                allow_comment  = ? , 
                                                allow_ads      = ?  
                                                WHERE 
                                                id= ? "); 

                    $stmt->execute(array($name,$desc,$parent,$order,$vasible,$comment,$ads,$id)) ; 

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

        echo  "<h1 class='text-center'> delete category </h1> " ; 
                echo  '<div class="container">' ;
                    // delete member page

                            $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) :  0  ; 
                                // بختار البيانات من الداتا بيس من الاي دي المعين
                        
                                $check =  checkitem('id','categories',$catid) ;  // فنكشن

                                if($check > 0 ) { 

                                    $stmt = $con->prepare("DELETE FROM categories WHERE id= $catid ") ; 
                                    // bindparam لربط البراميتر

                                    // $stmt->bindParam(":catid" , $catid) ;
                                    
                                    $stmt->execute();  //تنفيد 
                                    $theMsg = "<div class='alert alert-success'>"  .   $stmt->rowCount() . ' Record delete </div>' ; // عدد الريكورد التي تمت 
                                    redircthome($theMsg ,'back') ; // فنكشن 
                                
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

        ob_end_flush();

        ?>
