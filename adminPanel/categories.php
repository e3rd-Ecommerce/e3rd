<?php
/*
=======================================
== Categories Page
=======================================
*/

ob_start(); //Output Buffering Start

session_start();

$pageTitle = 'Categories';

if(isset($_SESSION['Username'])){

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if($do == 'Manage'){

        $sort = 'ASC';

        $sort_array=array('ASC','DESC');

        if(isset($_GET['sort']) && in_array($_GET['sort'],$sort_array)){

            $sort = $_GET['sort'];
        }

        $stmt2 = $con->prepare("SELECT * FROM categories ORDER BY ordering $sort");
        $stmt2->execute();
        $cats = $stmt2->fetchAll();//Cats => Categories ?>

        <h1 class="text-center">Manage Categories</h1>
        <div class="container categories">
                    <a href="categories.php?do=Add" class="btn btn-primary mb-2"><i class="fa fa-plus"></i> New Category</a>
                    <div class="card" >
                        <div class="card-header">
                            <i class="fas fa-tools"></i> Manage Categories
                            <div class="options float-end">
                                <i class="fas fa-sort"></i> Ordering: [
                                <a class="<?php if($sort == 'ASC'){echo 'active';}?>" href="?sort=ASC">Asc</a> |
                                <a class="<?php if($sort == 'DESC'){echo 'active';}?>" href="?sort=DESC">Desc</a>]
                                <i class="far fa-eye"></i> View:[
                                <span class="active" data-view="full">Full</span> |
                                <span data-view="classic">Classic</span>]
                            </div>
                        </div>

                        <div class="card-body">
                            <?php
                                foreach($cats as $cat){
                                    echo "<div class='cat'>";
                                        echo "<div class='hidden-buttons'>";
                                            echo "<a href='categories.php?do=Edit&catid=".$cat['ID']."' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</a>";
                                            echo "<a href='categories.php?do=Delete&catid=".$cat['ID']. "' class='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i> Delete</a>";
                                        echo "</div>";
                                        echo "<h3>".$cat['name'] . "</h3>";
                                        echo "<div class='full-view'>";
                                            echo "<p class='text-muted'>"; 
                                                if($cat['description'] == ''){echo "No description For This Category";}
                                                else {echo $cat['description'];} 
                                            echo "</p>";
                                            if($cat ['visibility'] == 1){echo "<span class='visibility global-span'><i class='far fa-eye-slash'></i> Hidden</span>";}
                                            if($cat ['allow_comment'] == 1){echo "<span class='commenting global-span'><i class='far fa-window-close'></i> Comments Disabled</span>";}
                                            if($cat ['allow_ads'] == 1){echo "<span class='advertises global-span'><i class='fas fa-minus-circle'></i> Ads Disabled</span>";}
                                        echo "</div>";
                                    echo "</div>";
                                    echo "<hr>";
                                }
                            ?>
                        </div>
                    </div>
        </div>
    <?php

    } elseif ($do == 'Add'){ ?>

        <div class="container">
                    <div class="row">
                        <div class="col-lg-6 m-auto">
                            <div class="card bg-light mt-3">
                                <div class="card-titel bg-info text-white">
                                    <h3 class="text-center py-4">Add New Category</h3>
                                </div>

                                <div class="card-body">
                                    <form action="?do=Insert" method="POST">
                                        <input type="text" name="name" placeholder="Enter category name" class="form-control my-2" autocomplete="off" required="required" >
                                        <input type="text" name="description" class="form-control my-2" placeholder="Descripe the category" >
                                        <input type="number" name="ordering" placeholder="Number to arrange the categories" class="form-control my-2">
                                        <div>
                                            <input type="radio" name="visibility" value="0" checked />
                                            <label for="">Visible</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="visibility" value="1" />
                                            <label for="">Hidden</label>
                                        </div>
                                        <hr/>
                                        <div>
                                            <input type="radio" name="commenting" value="0" checked />
                                            <label for="">Allow Comments</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="commenting" value="1" />
                                            <label for="">Don't Allow Comments</label>
                                        </div>
                                        <hr/>
                                        <div>
                                            <input type="radio" name="ads" value="0" checked />
                                            <label for="">Allow Ads</label>
                                        </div>
                                        <div class="last-radio">
                                            <input type="radio" name="ads" value="1" />
                                            <label for="">Don't Allow Ads</label>
                                        </div>
                                        <button class="btn btn-success" >Add Category</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                </div>
            </div>

    <?php
    } elseif ($do == "Insert"){

            //inser category to The Page
            if($_SERVER['REQUEST_METHOD'] == 'POST'){

                echo '<h1 class="text-center">Insert Category</h1>';
                echo '<div class="container">';

                //Get Vraiables Form The Form
                $name = $_POST['name'];
                $desc = $_POST['description'];
                $order = $_POST['ordering'];
                $visible = $_POST['visibility'];
                $comment = $_POST['commenting'];
                $ads = $_POST['ads'];
                
                //Check If Category Exist In DataBase
                $check = checkItem("name", "categories", $name);

                if($check == 1){
                    $theMsg="<div class='alert alert-danger'>Sorry This Category Is Exist</div>";
                        echo '<div class="container mt-3 text-center">';
                            redirectHome($theMsg,'back');
                        echo '</div>';
                } else {
                        //inser Categories Info in DataBase
                        $stmt = $con->prepare("INSERT INTO 
                                                categories(name,description,ordering,visibility, allow_comment,allow_ads)
                                                VALUES(:zname, :zdesc, :zorder, :zvisible, :zcomment, :zads)");

                        $stmt->execute(array(
                            
                            'zname'     => $name,
                            'zdesc'     => $desc,
                            'zorder'    => $order,
                            'zvisible'  => $visible,
                            'zcomment'  => $comment,
                            'zads'      => $ads
                        ));

                        //echo Success Message
                    $theMsg="<div class='alert alert-success'>".$stmt->rowCount() ." ". 'Record Inserted </div>';
                        echo '<div class="container mt-3 text-center">';
                            redirectHome($theMsg,null,'categories');
                        echo '</div>';
                }
            

            } else {

                $theMsg = "<div class='alert alert-danger'>You cant Browse This Page Directly</div>" ;
                echo '<div class="container mt-3 text-center">';
                    redirectHome($theMsg,'back',4);
                echo '</div>';
            }
        

    } elseif ($do == "Edit") {

        //Check If Get Request catid Is Numeric Get The Integer Value Of it
        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
           
        //Select All Data Depend On This Id
        $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ? ");

        //Execute Query
        $stmt->execute(array($catid));

        //fetch The Data
        $cat = $stmt -> fetch();

        //Check if there is a row like this data
        $count = $stmt->rowCount(); //هاي الرو كاونت فنكشن جاهزه بتجيبلي عدد الاسطر اللي لقاها 

        //If there record for this id show the form
        if($count > 0) { ?>
            
            <div class="container">
                    <div class="row">
                        <div class="col-lg-6 m-auto">
                            <div class="card bg-light mt-3">
                                <div class="card-titel bg-info text-white">
                                    <h3 class="text-center py-4">Edit Category</h3>
                                </div>

                                <div class="card-body">
                                    <form action="?do=Update" method="POST">
                                         <input type="hidden" name="catid" value="<?php echo $catid;?>">
                                        <input type="text" name="name" placeholder="Enter category name" class="form-control my-2" required="required" value="<?php echo $cat['name']; ?>" >
                                        <input type="text" name="description" class="form-control my-2" placeholder="Descripe the category" value="<?php echo $cat['description']; ?>">
                                        <input type="number" name="ordering" placeholder="Number to arrange the categories" class="form-control my-2" value="<?php echo $cat['ordering']; ?>">
                                        <div>
                                            <input type="radio" name="visibility" value="0" <?php if($cat['visibility'] == 0){echo 'checked';} ?> />
                                            <label for="">Visible</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="visibility" value="1" <?php if($cat['visibility'] == 1){echo 'checked';} ?>/>
                                            <label for="">Hidden</label>
                                        </div>
                                        <hr/>
                                        <div>
                                            <input type="radio" name="commenting" value="0" <?php if($cat['allow_comment'] == 0){echo 'checked';} ?>/>
                                            <label for="">Allow Comments</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="commenting" value="1" <?php if($cat['allow_comment'] == 1){echo 'checked';} ?> />
                                            <label for="">Don't Allow Comments</label>
                                        </div>
                                        <hr/>
                                        <div>
                                            <input type="radio" name="ads" value="0" <?php if($cat['allow_ads'] == 0){echo 'checked';} ?> />
                                            <label for="">Allow Ads</label>
                                        </div>
                                        <div class="last-radio">
                                            <input type="radio" name="ads" value="1" <?php if($cat['allow_ads'] == 1){echo 'checked';} ?>  />
                                            <label for="">Don't Allow Ads</label>
                                        </div>
                                        <button class="btn btn-success" >Save updates</button>
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
                        echo '<div class="container mt-3 text-center">';
                            redirectHome($theMsg,4);
                        echo '</div>';

            } 

    } elseif ($do == 'Update') {

        echo '<h1 class="text-center"> Update Category</h1>';
        echo '<div class="container">';
            if($_SERVER['REQUEST_METHOD'] == 'POST'){

                //Get Vraiables Form The Form
                $id         = $_POST['catid'];
                $name       = $_POST['name'];
                $desc       = $_POST['description'];
                $order      = $_POST['ordering'];

                $visible    = $_POST['visibility'];
                $comment    = $_POST['commenting'];
                $ads        = $_POST['ads'];
                
                if(strlen(trim($name)) == 0){

                    $theMsg = '<div class="alert alert-danger">The name Cant be empty</div>';
                    redirectHome($theMsg,'back',null,4);
                    exit();

                } else{

                    //Update The DataBase With This Info
                    $stmt = $con->prepare("UPDATE 
                                            categories 
                                       SET
                                            name = ? , 
                                            description = ?,
                                            ordering = ?,
                                            visibility = ?,
                                            allow_comment = ?,
                                            allow_ads = ? 
                                        WHERE 
                                            ID = ?");

                    $stmt->execute(array($name,$desc,$order,$visible,$comment,$ads,$id));

                    //echo Success Message
                    $theMsg= "<div class='alert alert-success'>".$stmt->rowCount() ." ". 'Record Updated </div>';
                    echo '<div class="container mt-3 text-center">';
                        redirectHome($theMsg,null,'categories',4);
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

            echo '<h1 class="text-center"> Delete Category</h1>';
            echo '<div class="container">';
            
                //Check If Get Request catId Numeric Get The Integer Value Of it
                $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
            
                //Select All Data Depend On This Id
                $check = checkItem('ID', 'categories', $catid);

                //If there record for this id Delete it from Data Base
                if( $check > 0) { 

                    $stmt = $con->prepare("DELETE FROM categories WHERE ID = :zcat");

                    $stmt->bindParam(":zcat",$catid);

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
    } 
    
    require_once $comp . 'footer.php';

}else {
     
    header('location: index.php');

    exit();
}

ob_end_flush(); // Release The Output

