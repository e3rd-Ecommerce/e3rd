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
        echo "Welcome";
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
    } elseif ($do = "Insert"){

            //inser Member to The Page
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
                            redirectHome($theMsg,'back');
                        echo '</div>';
                }
            

            } else {

                $theMsg = "<div class='alert alert-danger'>You cant Browse This Page Directly</div>" ;
                echo '<div class="container mt-3 text-center">';
                    redirectHome($theMsg,'back',4);
                echo '</div>';
            }
        

    } elseif ($do == 'Edit') {
        
    } elseif ($do == 'Update') {
        
    } elseif ($do == 'Delete'){

    } 
    
    require_once $comp . 'footer.php';

}else {
     
    header('location: index.php');

    exit();
}

ob_end_flush(); // Release The Output

