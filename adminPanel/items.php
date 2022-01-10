<?php
/*
=======================================
== Items Page
=======================================
*/

ob_start(); //Output Buffering Start

session_start();

$pageTitle = 'Items';

if(isset($_SESSION['Username'])){

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if($do == 'Manage'){

            //Select All Users Except Admins
            $stmt = $con->prepare("SELECT 
                                        items.*, 
                                        categories.name AS category_name,
                                        users.UserName  AS Username
                                   FROM items
                                   INNER JOIN 
                                        categories 
                                   ON categories.ID = items.cat_ID
                                   INNER JOIN 
                                         users 
                                   ON 
                                        users.userID = items.member_ID
                                   ORDER BY 
                                        item_ID DESC 
                                    ");

            //execute The Statement
            $stmt->execute();

            //Assign To Varaible
            $items = $stmt->fetchAll();

            if(!empty($items)){
        ?>

            <h1 class="text-center">Manage Items</h1>
            <div class="container"> 
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr> 
                            <td>#ID</td>
                            <td>Image</td>
                            <td>Name</td>
                            <td>Description</td>
                            <td>Price</td>
                            <td>Added Date</td>
                            <td>Category</td>
                            <td>Username</td>
                            <td>Control</td>
                        </tr> 

                        <?php
                            foreach($items as $item){
                                echo "<tr>";
                                    echo "<td>". $item['item_ID'] . "</td>";
                                    //echo "<td><img src='../images/".$item['image']."' alt='Not Found'></td>";
                                    echo "<td>". $item['name'] . "</td>";
                                    echo "<td>". $item['description'] . "</td>";
                                    echo "<td>". $item['price'] . "</td>";
                                    echo "<td>".$item['added_date']."</td>";
                                    echo "<td>".$item['category_name']."</td>";
                                    echo "<td>".$item['Username']."</td>";
                                    echo "<td>
                                            <a href='items.php?do=Edit&itemid=$item[item_ID]' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                            <a href='items.php?do=Delete&itemid=$item[item_ID]' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";  
                                            if ($item['approve'] == 0){
                                                echo "<a href='?do=Approve&itemid=$item[item_ID]' class='btn btn-info activate'><i class='fas fa-check'></i> Approve</a>";
                                             }            
                                   echo "</td>";
                                echo "</tr>";
                            }
                        ?>
                        
                    </table>
                </div>
                <a href="items.php?do=Add" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Item</a>
            </div> 

<?php       } else{

                    
                $theMsg='<div class="nice-message">Theres No items To show here</div>';
                echo '<div class="container mt-3 text-center">';
                    echo $theMsg;
                    echo '<a href="items.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New item</a>';
                echo '</div>';
            }
        } elseif ($do == 'Add'){ ?>

            <div class="container">
                    <div class="row">
                        <div class="col-lg-6 m-auto">
                            <div class="card bg-light mt-3">
                                <div class="card-titel bg-info text-white">
                                    <h3 class="text-center py-4">Add New Item</h3>
                                </div>

                                <div class="card-body">
                                    <form action="?do=Insert" method="POST" enctype='multipart/form-data'>
                                        
                                        <input 
                                            type="text" 
                                            name="name" 
                                            placeholder="Name of The Item" 
                                            class="form-control my-2" 
                                            required="required" > 

                                        <input 
                                            type="text" 
                                            name="description" 
                                            placeholder="Description The Item" 
                                            class="form-control my-2" 
                                            required="required"> 
                    
                                        <input 
                                            type="text" 
                                            name="price" 
                                            placeholder="Price of The Item" 
                                            class="form-control my-2" 
                                            required="required" > 

                                        <input 
                                            type="text" 
                                            name="country" 
                                            placeholder="Countery of Made" 
                                            class="form-control my-2" 
                                            required="required"> 

                                        <div class="select-box">
                                            <label for="status">Select The Item Status: </label>
                                            <select name="status" class="mb-2" id="status">
                                                <option value="0">...</option>
                                                <option value="1">New</option>
                                                <option value="2">Like New</option>
                                                <option value="3">Used</option>
                                                <option value="4">Very Old</option>
                                            </select>
                                        </div>
                                        <div class="select-box">
                                            <label for="status">Select Member: </label>
                                            <select name="member" class="mb-2" id="status">
                                                <option value="0">...</option>
                                                <?php 
                                                    $users = getAllFrom("*", "users", "","" ,"userID");
                                                    foreach($users as $user){

                                                        echo "<option value='".$user['userID']."'>". $user['UserName']. "</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="select-box">
                                            <label for="status">Select Category: </label>
                                            <select name="category" class="mb-2" id="status">
                                                <option value="0">...</option>
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
                
                                        <!--Start User profile picture-->
                                        <div class="custom-container">
                                            <div class="button-wrap">
                                                <label class="button mb-3" for="upload">Upload File</label>
                                                <input id="upload" type="file" name="files[]" multiple>
                                            </div>
                                        </div>
                                        <!--End User Profile Picture-->

                                        <button class="btn btn-success btn-sm" >Add Item</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

    <?php

    } elseif ($do == "Insert"){

            //inser Item to The Page
            if($_SERVER['REQUEST_METHOD'] == 'POST'){

                echo '<h1 class="text-center"> Insert Item</h1>';
                echo '<div class="container">';

                    //Get Vraiables Form The Form
                    $name       = $_POST['name'];
                    $desc       = $_POST['description'];
                    $price      = $_POST['price'];
                    $country    = $_POST['country'];
                    $status     = $_POST['status'];
                    $member     = $_POST['member'];
                    $cat        = $_POST['category'];
                    $tags       = $_POST['tags'];

                    // File upload configuration
                    $dir = rand(0,1000000) . "_".$name;
                    if (! is_dir("../imageItems/$dir")) {
                        mkdir("../imageItems/$dir"); 
                    }

                    $targetDir = "../imageItems/$dir/"; 
                    $allowTypes = array('jpg','png','jpeg','gif','jfif'); 

                    //$statusMsg = $errorMsg = $insertValuesSQL = $errorUpload = $errorUploadType = '';
                    $fileNames = array_filter($_FILES['files']['name']); 
                        if(!empty($fileNames)){ 
                            foreach($_FILES['files']['name'] as $key=>$val){ 
                                // File upload path 
                                $fileName = basename($_FILES['files']['name'][$key]); 
                                $targetFilePath = $targetDir.$fileName; 

                                // Check whether file type is valid 
                                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
                                if(in_array($fileType, $allowTypes)){ 
                                    // Upload file to server 
                                    move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath);
                                }
                            }
                        }

                //Validate The Form
                $formErrors = array(); //Array To coolect Al Errors

                if(empty($name)){
                    $formErrors[] = "Name can't Be <strong>Empty</strong>";
                }

                if(empty($desc)){
                    $formErrors[] = "Description can't Be <strong>Empty</strong>";
                }

                if(empty($price)){
                    $formErrors[] =  "Price can't Be <strong>Empty</strong>";
                }
                if(empty($country)){
                    $formErrors[] =  "Country can't Be <strong>Empty</strong>";
                }
                if($status ==   0){
                    $formErrors[] = "You must choose the <strong>Status</strong>";
                }   
                if($member ==   0){
                    $formErrors[] = "You must choose the <strong>Member</strong>";
                } 
                if($cat ==   0){
                    $formErrors[] = "You must choose the <strong>Category</strong>";
                }            

                //Print All Errors
                foreach($formErrors as $error){
                    echo '<div class="alert alert-danger">' .$error .'</div>';
                }

                //Check if there is No Error Procees The Update Opertion
                if(empty($formErrors)){

                            //inser User Info in DataBase
                            $stmt = $con->prepare("INSERT INTO 
                                                    items(name,description,price,country_made,added_date, status, approve, cat_ID, member_ID, tags,image)
                                                    VALUES(:zname, :zdesc, :zprice, :zcountry, now(), :zstatus, 1, :zcat, :zmember, :ztags,:image)");

                            $stmt->execute(array(
                                'zname'     => $name,
                                'zdesc'     => $desc,
                                'zprice'    => $price,
                                'zcountry'  => $country,
                                'zstatus'   =>$status,
                                'zcat'      => $cat,
                                'zmember'   => $member,
                                'ztags'     => $tags ,
                                'image'     => $dir
                            ));

                            //echo Success Message
                            $theMsg="<div class='alert alert-success'>".$stmt->rowCount() ." ". 'Record Inserted </div>';
                            echo '<div class="container mt-3 text-center">';
                                redirectHome($theMsg,'back');
                            echo '</div>';
                }

            } else {

                $theMsg = "<div class='alert alert-danger'>You cant Browse This Page Directly</div>" ;
                echo '<div class="container mt-3 text-center">';
                    redirectHome($theMsg,4);
                echo '</div>';
            }
        echo '</div>';

    } elseif ($do == 'Edit') {

        //Check If Get Request itemid Is Numeric Get The Integer Value Of it
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
           
        //Select All Data Depend On This Id
        $stmt = $con->prepare("SELECT * FROM items WHERE item_ID = ?");

        //Execute Query
        $stmt->execute(array($itemid));

        //fetch The Data
        $item = $stmt -> fetch();

        //Check if there is a row like this data
        $count = $stmt->rowCount(); //هاي الرو كاونت فنكشن جاهزه بتجيبلي عدد الاسطر اللي لقاها 

        //If there record for this id show the form
        if($count > 0) { ?>
            
            <div class="container">
                    <div class="row">
                        <div class="col-lg-6 m-auto">
                            <div class="card bg-light mt-3">
                                <div class="card-titel bg-info text-white">
                                    <h3 class="text-center py-4">Edit Item</h3>
                                </div>

                                <div class="card-body">
                                    <form action="?do=Update" method="POST">
                                    <input 
                                            type="hidden" 
                                            name="itemid" 
                                            value="<?php echo $itemid;?>">
                                    <div class="container cat" style="display: contents">
                                        <?php
                                            if($item['image'] != ''){
                                                $image ='';
                                                $d = $item['image'];
                                                $dirname = "../imageItems/$d/";
                                                $images = glob($dirname."*");
                                                for ($i=0; $i<count($images); $i++)
                                                {
                                                    $image = $images[$i];
                                                    echo "<img src='".$image."' alt='Not Fund' />";
                                                }
                                            } ?>
                                    </div>
                                        <input 
                                            type="text" 
                                            name="name" 
                                            placeholder="Name of The Item" 
                                            class="form-control my-2" 
                                            required="required" 
                                            value = "<?php echo $item['name'] ?>" /> 

                                        <input 
                                            type="text" 
                                            name="desc" 
                                            placeholder="Description The Item" 
                                            class="form-control my-2" 
                                            required="required"
                                            value = "<?php echo $item['description'] ?>" /> 
                    
                                        <input 
                                            type="text" 
                                            name="price" 
                                            placeholder="Price of The Item" 
                                            class="form-control my-2" 
                                            required="required" 
                                            value = "<?php echo $item['price'] ?>" /> 

                                        <input 
                                            type="text" 
                                            name="country" 
                                            placeholder="Countery of Made" 
                                            class="form-control my-2" 
                                            required="required"
                                            value = "<?php echo $item['country_made'] ?>" /> 

                                        <div class="select-box">
                                            <label for="status">Select The Item Status: </label>
                                            <select name="status" class="mb-2" id="status">
                                                <option value="1" <?php if($item['status'] == 1){echo 'selected';} ?>>New</option>
                                                <option value="2" <?php if($item['status'] == 2){echo 'selected';} ?>>Like New</option>
                                                <option value="3" <?php if($item['status'] == 3){echo 'selected';} ?>>Used</option>
                                                <option value="4" <?php if($item['status'] == 4){echo 'selected';} ?>>Very Old</option>
                                            </select>
                                        </div>
                                        <div class="select-box">
                                            <label for="member">Select Member: </label>
                                            <select name="member" class="mb-2" id="member">
                                                <?php 
                                                    $users = getAllFrom("*", "users", "","" ,"userID");
                                                    foreach($users as $user){

                                                        echo "<option value='".$user['userID']."'";
                                                        if($item['member_ID'] == $user['userID']){echo 'selected';}
                                                        echo ">". $user['UserName']. "</option>";                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="select-box">
                                            <label for="status">Select Category: </label>
                                            <select name="category" class="mb-2" id="status">
                                                <?php 
                                                    $cats = getAllFrom("*", "categories", "WHERE parent = 0","" ,"ID");
                                                    foreach($cats as $cat){
                                                        echo "<option value='".$cat['ID']."'";
                                                            if($item['cat_ID'] == $cat['ID']){echo 'selected';}
                                                        echo ">". $cat['name']. "</option>";   
                                                        $childCats = getAllFrom("*", "categories", "WHERE parent = {$cat['ID']}","" ,"ID");
                                                        foreach($childCats as $child){
                                                            echo '<optgroup label="Sub categories">';
                                                                echo "<option class='ms-3' value='".$child['ID']."'";
                                                                    if($item['cat_ID'] == $child['ID']){echo 'selected';}
                                                                echo ">". '- '.$child['name']. "</option>";
                                                            echo '</optgroup>';
                                                         }
                                                    }

                                                      
                                                ?>
                                            </select>

                                            <!--Start Tags Feild-->
                                                <input 
                                                    type="text" 
                                                    name="tags" 
                                                    placeholder="food, games, kids, lashes" 
                                                    class="form-control my-2" 
                                                    value = "<?php echo $item['tags']; ?>"
                                                    > 
                                             <!--End Tags Feild-->

                                        </div>
                                        <button class="btn btn-success btn-sm" >Save Item</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

            <?php
            //Select All Comments For This itesm
            $stmt = $con->prepare("SELECT
                                    comments.*,
                                    users.UserName As member_name
                                FROM 
                                    comments
                                INNER JOIN 
                                    users 
                                ON 
                                    users.userID = comments.user_ID
                                WHERE
                                    item_ID = ?
                            ");

            //execute The Statement
            $stmt->execute(array($itemid));

            //Assign To Varaible
            $rows = $stmt->fetchAll();
            ?>

            <h1 class="text-center">Manage ( <?php echo $item['name']; ?> ) Comments</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr> 
                          
                            <td>Comment</td>
                            <td>User Name</td>
                            <td>Added Date</td>
                            <td>control</td>
                        </tr> 

                        <?php
                            foreach($rows as $row){
                                echo "<tr>";
                                    echo "<td>". $row['comment'] . "</td>";
                                    echo "<td>". $row['member_name'] . "</td>";
                                    echo "<td>".$row['comment_date']."</td>";
                                    echo "<td>
                                            <a href='comments.php?do=Edit&comid=". $row['c_ID'] ."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                            <a href='comments.php?do=Delete&comid=$row[c_ID]' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
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
    <?php

    //If there is no Such Id
    } else {

       $theMsg='<div class="alert alert-danger">Theres No Such ID</div>';
                    echo '<div class="container mt-3 text-center">';
                        redirectHome($theMsg,4);
                    echo '</div>';

        } 
        
    } elseif ($do == 'Update') {

        echo '<h1 class="text-center"> Update Item</h1>';
        echo '<div class="container">';
            if($_SERVER['REQUEST_METHOD'] == 'POST'){

                //Get Vraiables From The Form
                $id         = $_POST['itemid'];
                $name       = $_POST['name'];
                $desc       = $_POST['desc'];
                $price      = $_POST['price'];
                $country    = $_POST['country'];
                $status     = $_POST['status'];
                $member     = $_POST['member'];
                $category   = $_POST['category'];
                $tags       = $_POST['tags'];

                //Validate The Form
                $formErrors = array(); //Array To coolect Al Errors

                if(empty($name)){
                    $formErrors[] = "Name can't Be <strong>Empty</strong>";
                }

                if(empty($desc)){
                    $formErrors[] = "Description can't Be <strong>Empty</strong>";
                }

                if(empty($price)){
                    $formErrors[] =  "Price can't Be <strong>Empty</strong>";
                }
                if(empty($country)){
                    $formErrors[] =  "Country can't Be <strong>Empty</strong>";
                } 

                //Print All Errors
                foreach($formErrors as $error){
                    echo '<div class="alert alert-danger">' .$error .'</div>';
                }

                //Check if there is No Error Procees The Update Opertion
                if(empty($formErrors)){
                    //Update The DataBase With This Info
                    $stmt = $con->prepare("UPDATE 
                                                `items`
                                           SET  name         = ? , 
                                                description  = ? ,
                                                price        = ? ,
                                                country_made = ? ,
                                                status       = ? ,
                                                member_ID    = ? ,
                                                cat_ID       = ? ,
                                                tags         = ? 

                                           WHERE
                                                item_ID      = ?");
                                                
                    $stmt->execute(array($name,$desc,$price,$country,$status,$member,$category,$tags,$id));

                    //echo Success Message
                    $theMsg= "<div class='alert alert-success'>".$stmt->rowCount() ." ". 'Record Updated </div>';
                    echo '<div class="container mt-3 text-center">';
                        redirectHome($theMsg,null,'items',4);
                    echo '</div>';
                }

            } else {
                    $theMsg= "<div class='alert alert-danger'>Sorry You Cant Browse This Page Directly </div>";
                    echo '<div class="container mt-3 text-center">';
                        redirectHome($theMsg,4);
                    echo '</div>';
            }
        echo '</div>';
        
    } elseif ($do == 'Delete'){

         
        echo '<h1 class="text-center"> Delete Item</h1>';
        echo '<div class="container">';
        
            //Check If Get Request Itemid Is Numeric Get The Integer Value Of it
            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        
            //Select All Data Depend On This Id
            $check = checkItem('item_ID', 'items', $itemid);

            //If there's record for this id Delete it
            if( $check > 0) { 


                function deleteDir($dirPath) {
                    if (! is_dir($dirPath)) {
                        throw new InvalidArgumentException("$dirPath must be a directory");
                    }
                    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
                        $dirPath .= '/';
                    }
                    $files = glob($dirPath . '*', GLOB_MARK);
                    foreach ($files as $file) {
                        if (is_dir($file)) {
                            deleteDir($file);
                        } else {
                            unlink($file);
                        }
                    }
                    rmdir($dirPath);
                }
                
                $stmt1 = $con->prepare("SELECT * FROM items WHERE item_ID = ?");
                $stmt1->execute(array($itemid));
                $row = $stmt1->fetch();
                $dir = $row['image'];
                //echo "../imageItems/$dir";die;
                if (file_exists("../imageItems/$dir")) {
                    deleteDir("../imageItems/$dir");  
                }
                

                $stmt = $con->prepare("DELETE FROM items WHERE item_ID = :zitem");

                $stmt->bindParam(":zitem",$itemid);

                $stmt->execute();

                //echo Success Message
                 $theMsg = "<div class='alert alert-success'>".$stmt->rowCount() ." ". 'Record Deleted </div>';
                    echo '<div class="container mt-3 text-center">';
                        redirectHome($theMsg,'back',4);
                    echo '</div>';

            }else {
                $theMsg= "<div class='alert alert-danger'> This Id Is Not Exist </div>";
                    echo '<div class="container mt-3 text-center">';
                        redirectHome($theMsg,4);
                    echo '</div>';
            }
        echo '</div>';


    } elseif ($do == 'Approve'){

        echo '<h1 class="text-center"> Approve Item</h1>';
            echo '<div class="container">';
            
                //Check If Get Request itemid Is Numeric  Get The Integer Value Of it
                $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
            
                //Select All Data Depend On This Id
                $check = checkItem('item_ID', 'items', $itemid);

                //If there record for this id show the form
                if( $check > 0) { 

                    $stmt = $con->prepare("UPDATE items SET approve = 1 WHERE item_ID = ?");

                    $stmt->bindParam(":zitem",$itemid);

                    $stmt->execute(array($itemid));

                    //echo Success Message
                     $theMsg = "<div class='alert alert-success'> Item Approved <i class='fas fa-check-circle'></i></div>";
                        echo '<div class="container mt-3 text-center">';
                            redirectHome($theMsg,'back',4);
                        echo '</div>';

                }else {
                    $theMsg= "<div class='alert alert-success'> This Id Is Not Exist </div>";
                        echo '<div class="container mt-3 text-center">';
                            redirectHome($theMsg,4);
                        echo '</div>';
                }
            echo '</div>';

    }

    require_once $comp . 'footer.php';

}else {
     
    header('location: index.php');

    exit();
}

ob_end_flush(); // Release The Output

?>