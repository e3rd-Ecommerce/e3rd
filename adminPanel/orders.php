<?php
/*
=======================================
== Template Page
=======================================
*/

ob_start(); //Output Buffering Start

session_start();

$pageTitle = '';

if(isset($_SESSION['Username'])){

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if($do == 'Manage'){
              
            //Select All Users Except Admins
            $stmt = $con->prepare("SELECT
                                        *
                                   FROM 
                                        order_infor
                                        
                                    ORDER BY 
                                        order_ID DESC
                                   ");

            //execute The Statement
            $stmt->execute();

            //Assign To Varaible
            $rows = $stmt->fetchAll();

            if(!empty($rows)){
        
            ?>

            <h2 class="text-center">Orders</h2>
            <div class="container"> 
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr> 
                            <td>#ID</td>
                            <td>From</td>
                            <td>To</td>
                            <td>Order Date</td>
                            <td>Description</td>
                            <td>Phone</td>
                            <td>control</td>
                        </tr> 

                        <?php
                            foreach($rows as $row){
                                echo "<tr>";
                                    echo "<td>". $row['order_ID'] . "</td>";
                                    echo "<td>". $row['from_user'] . "</td>";
                                    echo "<td>". $row['to_user'] . "</td>";
                                    echo "<td>". $row['order_date'] . "</td>";
                                    echo "<td>".$row['description']."</td>";
                                    echo "<td>".$row['phone']."</td>";
                                    echo "<td>
                                            <a href='orders.php?do=Delete&orderid=$row[order_ID]' class='btn btn-danger delete confirm'><i class='fa fa-close'></i> Delete </a>";  
                                                echo '<a href="orders.php?do=info&orderid='. $row['order_ID'] .'">';
                                                    echo '<span class="btn btn-info edit float-end">';
                                                        echo '<i class="fa fa-eye"></i> Show Order';
                                                    echo '</span>';
                                               echo '</a>';
                                            if ($row['approve'] == 0){
                                               echo "<a href='orders.php?do=Approve&orderid=" . $row['order_ID'] ."' class='btn btn-info activate'><i class='fas fa-check'></i> Approve</a>";
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
        
         
    }elseif ($do == "info") {
        
        //Check If Get Request comid Is Numeric Get The Integer Value Of it
        $orderid = isset($_GET['orderid']) && is_numeric($_GET['orderid']) ? intval($_GET['orderid']) : 0;
        $count = 1;
        $total =0;
        $order_date;
        //Select All Users Except Admins
        $stmt = $con->prepare("SELECT oni.from_user , oni.order_date ,
                                    oi.item_name , oi.number_of_item ,oi.price_item
                                FROM order_item oi 
                                INNER JOIN order_infor oni ON
                                    oni.order_ID = oi.order_ID 
                                WHERE oi.order_ID = ?");

                            //execute The Statement
                            $stmt->execute(array($orderid));

                            //Assign To Varaible
                            $rows = $stmt->fetchAll();

                            if(!empty($rows)){

                            ?>

                            <h2 class="text-center">Order <?php echo $rows[0]['from_user']; ?></h2>
                            <div class="container"> 
                            <div class="table-responsive">
                            <table class="main-table text-center table table-bordered">
                            <tr> 
                            <td>#ID</td>
                            <td>Item Name</td>
                            <td>Number Of Item</td>
                            <td>Price Item</td>
                            <!-- <td>Description</td>
                            <td>Phone</td>
                            <td>control</td> -->
                            </tr> 

                            <?php
                            $order_date = $rows[0]['order_date'];
                            foreach($rows as $row){
                            echo "<tr>";
                                echo "<td>". $count++ . "</td>";
                                echo "<td>". $row['item_name'] . "</td>";
                                echo "<td>". $row['number_of_item'] . "</td>";
                                echo "<td>". $row['price_item'] . "</td>";
                                $total += ($row['number_of_item'] * $row['price_item']);
                            echo "</tr>";
                            }
                            ?>

                            </table>

                            <?php 
                                echo "<div class='order-details d-lg-flex d-sm-block justify-content-between'>";
                                    echo '<div class="one">';
                                        echo "<h6 class='btn btn-outline-secondary'>Order Date :";
                                        echo "<span>". $order_date."</span></h6>";
                                    echo '</div>';

                                    echo '<div class="two">';
                                        echo "<h6 class='btn btn-outline-secondary'>Total :";
                                        echo "<span class=''>". $total." JD</span></h6SS>";
                                    echo '</div>';

                                echo "</div>";
                            ?>
                            </div>
                            </div>
                            <?php }else {

                            $theMsg='<div class="nice-message">Theres No Comments To show here</div>';
                            echo '<div class="seperator"></div>';
                            echo '<div class="container mt-3 text-center">';
                            echo $theMsg;
                            echo '</div>';
                            }
        
    } elseif ($do == 'Delete'){

        echo '<h1 class="text-center"> Delete Order</h1>';
        echo '<div class="container">';
        
            //Check If Get Request comid Is Numeric Get The Integer Value Of it
            $orderid = isset($_GET['orderid']) && is_numeric($_GET['orderid']) ? intval($_GET['orderid']) : 0;
        
            //Select All Data Depend On This Id
            $check = checkItem('order_ID', 'order_infor', $orderid);

            //If there record for this id show the form
            if( $check > 0) { 

                $stmt = $con->prepare("DELETE FROM order_infor WHERE order_ID = :zorder");

                $stmt->bindParam(":zorder",$orderid);

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
    } elseif ($do == 'Approve'){

        echo '<h1 class="text-center"> Approve order</h1>';
            echo '<div class="container">';
            
                //Check If Get Request comid Is Numeric Get The Integer Value Of it
                $orderid = isset($_GET['orderid']) && is_numeric($_GET['orderid']) ? intval($_GET['orderid']) : 0;
            
                //Select All Data Depend On This Id
                $check = checkItem('order_ID', 'order_infor', $orderid);

                //If there record for this id show the form
                if( $check > 0) { 

                    $stmt = $con->prepare("UPDATE order_infor SET approve = 1 WHERE order_ID = :zorder");

                    $stmt->bindParam(":zorder",$orderid);

                    $stmt->execute();

                    //echo Success Message

                        $theMsg = "<div class='alert alert-success'> order Approved <i class='fas fa-check-circle'></i></div>";
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

    }

    require_once $comp . 'footer.php';

}else {
     
    header('location: index.php');

    exit();
}

ob_end_flush(); // Release The Output

?>