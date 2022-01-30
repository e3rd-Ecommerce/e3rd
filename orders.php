<?php

ob_start(); //Output Buffering Start

session_start();

$pageTitle = 'My Orders';

if(isset($_SESSION['uid'])){

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if($do == 'Manage'){
        
        echo '<div class="all-title-box">';
            echo '<div class="container">';
                echo '<div class="row">';
                   echo '<div class="col-lg-12">';
                        echo '<h2>Shop Detail</h2>';
                        echo '<ul class="breadcrumb">';
                            echo '<li class="breadcrumb-item"><a href="index.php">Shop</a></li>';
                            echo '<li class="breadcrumb-item active">Shop Detail </li>';
                        echo '</ul>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        echo '</div>';    

        $stmt = $con->prepare("SELECT oi.*
                                FROM order_infor oi 
                                INNER JOIN order_user ou ON 
                                   ou.order_ID = oi.order_ID 
                                   WHERE ou.user_ID = ? ");

                            //execute The Statement
                            $stmt->execute(array($_SESSION['uid']));

                            //Assign To Varaible
                            $rows = $stmt->fetchAll();

                            if(!empty($rows)){

                            ?>

                            <div class="m-4">
                                <h2 class="text-center">Orders</h2>
                                <div class="container"> 
                                    <div class="table-responsive">
                                        <table class="main-table text-center table table-bordered">
                                        <tr> 
                                        <td>#ID</td>
                                        <td>To User</td>
                                        <td>Address</td>
                                        <td>Phone</td>
                                        <td>Order Date</td>
                                        <td>Approve</td>
                                        <td>Action</td>
                                        </tr> 

                                        <?php
                                        foreach($rows as $row){
                                            echo "<tr>";
                                                echo "<td>". $count++ . "</td>";
                                                echo "<td>". $row['to_user'] . "</td>";
                                                echo "<td>". $row['address'] . "</td>";
                                                echo "<td>". $row['phone'] . "</td>";
                                                echo "<td>". $row['order_date'] . "</td>";
                                                echo "<td>";
                                                if($row['approve'] == 1){echo "<span class='alert alert-success'>Done order</span>";}else{echo "<span class='alert alert-danger'>Waiting Approve</span>";} 
                                                echo "</td>";  
                                                echo "<td>";
                                                        echo '<a href="orders.php?do=info&orderid='. $row['order_ID'] .'">';
                                                            echo '<span class="btn btn-info edit float-end">';
                                                                echo '<i class="fa fa-eye"></i> Show Order';
                                                            echo '</span>';
                                                        echo '</a>';
                                                echo "</td>";        
                                            echo "</tr>";
                                        }
                                        ?>

                                        </table>
                                    </div>
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
                            
                            <div class="m-4">
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
                            </div>
                            <?php }else {

                            $theMsg='<div class="nice-message">Theres No Comments To show here</div>';
                            echo '<div class="seperator"></div>';
                            echo '<div class="container mt-3 text-center">';
                            echo $theMsg;
                            echo '</div>';
                            }
        
    }

    require_once $comp . 'footer.php';

}else {
     
    header('location: index.php');

    exit();
}

ob_end_flush(); // Release The Output

?>