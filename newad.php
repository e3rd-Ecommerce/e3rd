
<?php 
ob_start();
session_start();
$pageTitle = 'create new item' ; 
include_once 'init.php' ;
if(isset($_SESSION['user'])){ 



            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $formerrors = array(); 
                
                $name     = filter_var($_POST['name'],FILTER_SANITIZE_STRING);
                $desc     = filter_var($_POST['description'],FILTER_SANITIZE_STRING);
                $price    = filter_var($_POST['price'],FILTER_SANITIZE_NUMBER_INT);
                $country  = filter_var($_POST['country'],FILTER_SANITIZE_STRING);
                $status   = filter_var($_POST['status'],FILTER_SANITIZE_NUMBER_INT);
                $category = filter_var($_POST['category'],FILTER_SANITIZE_NUMBER_INT);

                
                if(strlen($name) < 4){
                    $formerrors[] = 'item title must be at least 4 characters';
                }

                if(strlen($desc) < 8){
                    $formerrors[] = 'item description must be at least 8 characters';
                }

                if(strlen($country) < 2){
                    $formerrors[] = 'item country must be at least 2 characters';
                }
                if(empty($price)){
                    $formerrors[] = 'item price must be not empty';
                }
                if(empty($status)){
                    $formerrors[] = 'item status must be not empty';
                }
                if(empty($category)){
                    $formerrors[] = 'item category must be not empty';
                }

            // لو مفيش ايرور بالفالديشن كمل     
            if(empty($formerrors)){
            
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
                    'zmember' => $_SESSION['uid'] , 
                )) ; 

                if($stmt){
                     // echo success message
                    $successmsg =  'item added' ; 
                }
            
    
    }

            }
?>

<h1 class="text-center"><?php echo $pageTitle;  ?></h1>

    <div class="information block">
        <div class="container">
            <div class="card">
                <div class="card-heading bg-info text-white "><?php echo $pageTitle ; ?></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <!--  -->
                    <form action="<?php $_SERVER['PHP_SELF'] ?>" class="" method="POST">

                        <!-- start name field -->
                        <div class=" row mb-3  ">
                            <label class="col-sm-2 col-lg-2 col-form-label"><?php echo lang('name-catgory') ; ?></label>
                            <div class="col-sm-10 col-md-9"> 
                                <input 
                                pattern=".{4,}"
                                title="this field require at least 4 character"
                                type="text"
                                name="name"
                                class="form-control live-name"  
                                placeholder="name of the items"
                                data-class=".live-title"/>
                            </div>
                        </div>
                        <!-- end name -->

                        <!-- start description items -->
                        <div class=" row mb-3  ">
                            <label class="col-sm-2 col-lg-2 col-form-label"><?php echo lang('description-item') ; ?></label>
                            <div class="col-sm-10 col-md-9"> 
                                <input
                                type="text" 
                                name="description" 
                                class="form-control live-decs"  
                                placeholder="description of the item"
                                data-class=".live-desc"/>
                            </div>
                        </div>
                        <!-- end description -->

                        <!-- start price items -->
                        <div class=" row mb-3  ">
                            <label class="col-sm-2 col-lg-2 col-form-label"><?php echo lang('price-item') ; ?></label>
                            <div class="col-sm-10 col-md-9"> 
                                <input
                                type="text" 
                                name="price" 
                                class="form-control live-price"  
                                placeholder="price of the item"
                                data-class=".live-price"/>
                            </div>
                        </div>
                        <!-- end price -->

                        <!-- start country items -->
                        <div class=" row mb-3  ">
                            <label class="col-sm-2 col-lg-2 col-form-label"><?php echo lang('countryy-item') ; ?></label>
                            <div class="col-sm-10 col-md-9"> 
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
                            <label class="col-sm-2 col-lg-2 col-form-label"><?php echo lang('status-item') ; ?></label>
                            <div class="col-sm-10 col-md-9"> 
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

                        <!-- start category field -->
                        <div class=" row mb-3  ">
                            <label class="col-sm-2 col-lg-2 col-form-label"><?php echo lang('category-filed') ; ?></label>
                            <div class="col-sm-10 col-md-9"> 
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
                            <div class="col-sm-offset-3 col-sm-10 "> 
                                <input type="submit" value="add item" class="add-new-cat btn btn-info  " />
                            </div>
                        </div>
                        <!-- end submit-->
                        
                    </form>

                            <!--  -->
                        </div>
                        <div class="col-md-4">
                            <div class="card item-box live-preview"> 
                                <span class="price-tag live-price" > 0 </span>
                                <img class="img-img-fluid" src="../ecom/layout/images/img_avatar.png"  alt=""  />
                                <div class="caption">
                                <h3 class="live-title"> title  </h3> 
                                <p class="live-desc"> description</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- start looping error -->
                    <?php 

                    if(!empty($formerrors)){

                        foreach($formerrors as $error){
                            echo '<div class="alert alert-danger">' . $error . '<div> '; 
                        }
                    }
                    if(isset($successmsg)){
                        echo '<div class="alert alert-success" role="alert">' . $successmsg . '</div>'  ;
                    }

                    ?>
                    <!-- end loop error -->
                </div> 
            </div>
        </div>
    </div> 


    
<?php } else {
    header('location: login.php');
    exit();
}


include_once $tpl . 'footer.php' ;  
ob_end_flush();
?>