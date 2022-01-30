<?php

require_once 'adminPanel/connect.php';

if (isset($_POST['prodect_id']) && isset($_POST['user_id']) 
                && isset($_POST['name_prodcur']) && $_POST['process'] == 'number_product') {
        $id_product=$_POST['prodect_id'];
        $user_id = $_POST['user_id'];
        $number_product = $_POST['name_prodcur'];
        $stmt = $con->prepare("UPDATE cart_item 
                                SET number_product = ?
                                WHERE user_ID = ? AND item_ID = ?");
        $stmt->execute(array($number_product,$user_id,$id_product));
        
        $total = 0;
        $user_id = $_POST['user_id'];
        $stmt = $con->prepare("SELECT i.price,it.number_product 
                        FROM cart_item AS it
                        INNER JOIN items AS i ON i.item_ID = it.item_ID
                        WHERE it.user_ID = ?");
        $stmt->execute(array($user_id));
        $row = $stmt->fetchAll();

        foreach ($row as $key => $value) {
                $total += ($value['number_product'] * $value['price']);
        }
        echo $total == '' ? '00.00' : "$total JD";

}

elseif (isset($_POST['item_id']) && $_POST['process'] == 'AddToCart') {
        $id_product=$_POST['item_id'];
        $user_id = $_POST['user_id'];
        $number_product = 1;
        $stmt = $con->prepare("INSERT INTO cart_item(user_ID,item_ID,number_product) VALUE (?,?,?)");
        $stmt->execute(array($user_id,$id_product,$number_product));
}

elseif (isset($_GET['id'])) { 
        $cart_product_id=$_GET['id'];
        $stmt = $con->prepare("DELETE FROM cart_item WHERE cart_item_ID = ?");
        $stmt->execute(array($cart_product_id));
        header("Location: cart.php"); 
        exit();
}

elseif ($_POST['process'] == 'deleteCart' && isset($_POST['user_id'])) { 
        $user_id=$_POST['user_id'];
        $stmt = $con->prepare("DELETE FROM cart_item WHERE user_ID = ?");
        $stmt->execute(array($user_id));
        header("Location: index.php"); 
        exit();
}

else { 
        header("Location: index.php"); 
        exit();
}
?>