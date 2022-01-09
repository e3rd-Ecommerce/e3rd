<?php 
    session_start();

    $pageTitle = "Add new product";
    
    include 'init.php';

    if(isset($_SESSION['user'])){
        
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
           
            $formErrors = array();

            $name       = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $desc       = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
            $price      = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
            $country    = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
            $status     = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
            $category   = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
            $tags       = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);

            if(strlen($name) < 3 || strlen($name) > 20){
                $formErrors[] = 'The item Name must be between 4 and 20 characters';
            }

            if(strlen($desc) < 10 || strlen($desc) > 120){
                $formErrors[] = 'The item Descritpion must be between 10 and 120 characters';
            }

            if(strlen($country) < 3 || strlen($country) >20 ){
                $formErrors[] = 'The item countery must be between 3 and 20 characters';
            }

            if(empty($price) ){
                $formErrors[] = 'The item Price can\'t be empty';
            }

            if(empty($status) ){
                $formErrors[] = 'The item Status can\'t be empty';
            }

            if(empty($category) ){
                $formErrors[] = 'The item Category can\'t be empty';
            }

            //Check if there is No Error Procees The Update Opertion
            if(empty($formErrors)){

                //inser Item Info in DataBase
                $stmt = $con->prepare("INSERT INTO 
                                        items(name,description,price,country_made,added_date, status,  cat_ID, member_ID, tags)
                                        VALUES(:zname, :zdesc, :zprice, :zcountry, now(), :zstatus, :zcat, :zmember, :ztags)");

                $stmt->execute(array(

                    'zname'     => $name,
                    'zdesc'     => $desc,
                    'zprice'    => $price,
                    'zcountry'  => $country,
                    'zstatus'   =>$status,
                    'zcat'      => $category,
                    'zmember'   => $_SESSION['uid'],
                    'ztags'     => $tags
                ));

                if($stmt){
                    
                    $successMsg  = 'Item has been Added';
                    
                }
            }

        }

    ?>

<h2 class="text-center text-muted m-3"><?php echo $pageTitle;?></h2>
<div class="create-ad block">
    <div class="container">
        <div class="card">
            <div class="card-header bg-info  text-light fw-bold">
                <?php echo $pageTitle;?>
            </div>

            <div class="card-body">
                <div class="row">
                     <!-- Start Looping Throgh Errors -->
                    <?php 
                        if(!empty($formErrors)){
                            foreach($formErrors as $error){
                                echo '<div class="alert alert-danger my-2">'. $error. '</div>';
                            }
                        }
                        if(isset($successMsg)){
                            //echo Success Message
                            echo "<div class='alert alert-success'>".$successMsg.'</div>';
                        }
                    ?>
               <!-- End Looping Throug Errors -->
                </div>
               <div class="row">
                   <div class="col-md-8">
                        <div class="card bg-light">
                                <div class="card-titel text-muted">
                                    <h3 class="text-center py-4">Add New Item</h3>
                                </div>

                                <div class="card-body">
                                    <form class="main-form" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
                                        <input 
                                            pattern=".{3,}"
                                            title="This field requre At least 3 Characters"
                                            type="text" 
                                            name="name" 
                                            placeholder="Name of The Item" 
                                            class="form-control my-2 live" 
                                            required="required" 
                                            data-class=".live-name" /> 

                                        <input 
                                            pattern = ".{10,}"
                                            title="This field requre At least 10 Characters"
                                            type="text" 
                                            name="description" 
                                            placeholder="Description The Item" 
                                            class="form-control my-2 live" 
                                            required="required"
                                            data-class=".live-desc"/> 
                    
                                        <input 
                                            min="0" max="999" 
                                            type="number"
                                            onKeyUp="if(this.value>999){
                                                            this.value='999';
                                                      } else if(this.value<0)
                                                            {this.value='0';}" 
                                            name="price" 
                                            placeholder="Price of The Item" 
                                            class="form-control my-2 live" 
                                            required="required" 
                                            data-class=".live-price"> 

                                        <input 
                                            type="text" 
                                            name="country" 
                                            placeholder="Countery of Made" 
                                            class="form-control my-2" 
                                            required="required"> 

                                        <div class="select-box">
                                            <label for="status" >Select The Item Status: </label>
                                            <select required name="status" class="mb-2" id="status">
                                                <option value="">...</option>
                                                <option value="1">New</option>
                                                <option value="2">Like New</option>
                                                <option value="3">Used</option>
                                                <option value="4">Very Old</option>
                                            </select>
                                        </div>
                                        <div class="select-box">
                                            <label for="status">Select Category: </label>
                                            <select required name="category" class="mb-2" id="status">
                                                <option value="">...</option>
                                                <?php 
                                                     $cats = getAllFrom("*", "categories", "WHERE parent = 0","" ,"ID");
                                                     foreach($cats as $cat){
                                                         echo "<option value='".$cat['ID']."'>". $cat['name']. "</option>";   
                                                         $childCats = getAllFrom("*", "categories", "WHERE parent = {$cat['ID']}","" ,"ID");
                                                         foreach($childCats as $child){
                                                             echo '<optgroup label="Sub categories">';
                                                                 echo "<option class='ms-3' value='".$child['ID']."'>". '- '.$child['name']. "</option>";
                                                             echo '</optgroup>';
                                                          }
                                                     }
                                                ?>
                                            </select>
                                        </div>

                                        <!--Start Tags Feild-->
                                        <input 
                                            type="text" 
                                            name="tags" 
                                            placeholder="food, games, kids, lashes" 
                                            class="form-control my-2" 
                                        > 
                                        <!--End Tags Feild-->

                                        <button class="btn btn-success btn-sm" >Add Item</button>
                                    </form>
                                </div>
                            </div>
                   </div>
                   <div class="col-md-4">
                        <div class="card item-box live-preview">
                            <img src="OIP.png" alt="Avatar" class="img-fluid"/>
                            <div class="card-body caption">
                                <div class="price-tag">
                                   <span class="live-price ">0</span> JOD
                                </div>
                                <h3 class="card-title mb-3 live-name ">Product Name</h3>
                                <p class="card-text live-desc">
                                    Add Your description
                                </p>
                            </div>
                        </div>
                   </div>
               </div>
            </div>
        </div>
    </div>
</div>


<?php
    } else {
        header('Location:login.php');
    }

require_once $comp . "footer.php"; ?>