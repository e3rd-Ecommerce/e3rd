<?php
ob_start();
session_start();
include 'init.php';
if (! isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

?>

<?php
if (isset($_GET['buyIt'])) {
  
  $errors = [];
  $user_id = $_SESSION['uid'] ;
  $stmt = $con->prepare("SELECT UserName FROM users WHERE userID = ?");
  $stmt->execute(array($user_id));
  $row = $stmt->fetch();
  
  $from = $row['UserName'];
  
  $to_user = 'The Person Receiving Order';
  $address ='Address';
  $description = 'If You Add Any Description';
  $phone = '+962 7 XXXX XXXX';
  $order_date ;
  
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
      $to_user = $_POST['to_user'];
      $address = $_POST['address'];
      $phone = $_POST['phone'];
      $description = $_POST['description'] ?? null;
  
      if (!$to_user) {
          $errors[] = 'Please Enter Who is required...';
      }
      if (!$address) {
          $errors[] = 'Please Enter Address...';
      }
      if (!$phone) {
          $errors[] = 'Please Enter Phone...';
      }
  
      // to insert information in table order_info
      if (empty($errors)) {

          $order_date = date('Y-m-d H:i:s') ;
          $statement = $con->prepare("INSERT INTO order_infor (from_user, to_user, order_date, address, description ,phone)
                  VALUES (?,?,?,?,?,?)");
          $statement->execute(array($from,$to_user,$order_date,$address,$description,$phone));
  
          $statement = $con->prepare("SELECT order_ID FROM order_infor WHERE order_date =?");
          $statement->execute(array($order_date));
          $row = $statement->fetch();
          $order_id = $row['order_ID'];
  
          $statement = $con->prepare("INSERT INTO order_user (user_ID , order_ID ) VALUES (?,?)");
          $statement->execute(array($user_id ,$order_id));
  
          $statement = $con->prepare("SELECT i.name, i.price, ci.number_product 
                                          FROM cart_item AS ci
                                          INNER JOIN items AS i ON i.item_ID = ci.item_ID
                                          WHERE ci.user_id = ?");
          $statement->execute(array($user_id));
          $row =$statement->fetchAll();
          
          foreach ($row as $key => $value) {
              $statement = $con->prepare("INSERT INTO order_item(item_name,order_ID,number_of_item,price_item) 
                                          VALUES (?,?,?,?)");
              $statement->execute(array($value['name'],$order_id,$value['number_product'],$value['price']));
          }
  
          $statement = $con->prepare("DELETE FROM cart_item WHERE user_Id = ?");
          $statement->execute(array($user_id));
          header('Location: index.php');
      }
  
  }?>
      </br>
      <div class="container">
          <h1 class="text-center">Orders Information</h1>
  
          <?php if (!empty($errors)): ?>
              <div class="alert alert-danger">
                  <?php foreach ($errors as $error): ?>
                      <div><?php echo $error ?></div>
                  <?php endforeach; ?>
              </div>
          <?php endif; ?>
  
          <form method="post" enctype="multipart/form-data">
              <div class="form-group">
                  <label>To User </label>
                  <input type="text" name="to_user" class="form-control" placeholder="<?php echo $to_user ?>">
              </div>
              <div class="form-group">
                  <label>Phone </label>
                  <input type="text" name="phone" class="form-control" placeholder="<?php echo $phone ?>">
              </div>
              <div class="form-group">
                  <label>Address </label>
                  <input type="text" name="address" class="form-control" placeholder="<?php echo $address ?>">
              </div>
              <div class="form-group">
                  <label>Description (Optional)</label>
                  <textarea class="form-control" name="description" placeholder="<?php echo $description ?>"></textarea>
              </div>
              <br>
              <button type="submit" class="btn btn-lg btn-outline-primary">Add</button>
              <a href="cart.php" class="btn btn-lg btn-outline-danger">Cancel</a>
          </form>
      </div>


<?php }else{

  $pageTitle ="Show Cart";
  $user_id = $_SESSION['uid'];

  $stmt = $con->prepare("SELECT it.name ,it.price ,it.image , ct.number_product ,u.UserName ,
                          ct.item_ID ,ct.user_ID ,ct.cart_item_ID 
                          FROM cart_item AS ct
                          INNER JOIN users AS u ON u.userID = ct.user_ID 
                          INNER JOIN items AS it ON it.item_ID = ct.item_ID
                          WHERE ct.user_ID = ?");
  $stmt->execute(array($user_id));
  $row = $stmt->fetchAll();
  $total = 0;?>


<!-- ************* -->

<!-- Start All Title Box -->
<div class="all-title-box">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Cart</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Shop</a></li>
                        <li class="breadcrumb-item active">Cart</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- End All Title Box -->



<!-- ******************* -->
<div class="cart-box-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">

                    <div class="table-main table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">image</th>
                                <th scope="col">Username</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">price</th>
                                <th scope="col">Number</th>
                                <th scope="col">Total</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($row as $key => $value) {
                                    $total += ($value['price'] * $value['number_product']);
                                    $dirname = "imageItems/".$value['image'];
                                            if (is_dir($dirname)) {
                                                $images = glob($dirname ."/*");
                                            }
                                    ?>
                                <tr>
                                    
                                    <th scope="row"><?= $key + 1?></th>
                                    <td><img src="<?= $images[0] ?>" alt="" style="width: 100px;height: 60px;"></td>
                                    <td><?= $value['UserName'] ?></td>
                                    <td><?= $value['name'] ?></td>
                                    <td><?= $value['price'] ?></td>
                                    <td><input type="number" name="number_product" 
                                    value="<?= $value['number_product']?>" min="1" id="<?= $value['name']?>"
                                    onchange="onch(<?= $value['item_ID']?>,<?=$value['user_ID']?>,<?= $value['name']?>,<?=$value['price']?>)"></td>
                                    <td id="<?=$value['item_ID']?>"><?=$value['number_product'] * $value['price']?></td>
                                    
                                    <td><button class="btn btn-lg btn-danger" onclick="return confirm('Are you sure?')?window.location.href='servesCart.php?action=removeCartItem&id=<?=$value['cart_item_ID']; ?>':false;"><i class="fas fa-trash"></i></button> </td>
                                </tr>
                            <?php }?>
                            <tr>
                                <td colspan="3" class="text-center"><button class="btn btn-lg btn-danger" onclick="return confirm('Are you sure To Delete All Cart ?') ? deleteCart(<?=$user_id;?>) :false;">Delete All Cart</button></td>
                                <td colspan="3" class="text-center"><button class="btn btn-lg btn-success" onclick="return confirm('Are you sure?')?window.location.href='cart.php?buyIt':false;">Buy It</button></td>
                                <td>Total :</td>
                                <td colspan="3" id="total"><?=$total?> JD</td>
                            </tr>
                            </tbody>
                        </table>
                        </div>
                </div>
            </div>
     </div>
                                        </div>

<?php }?>

<?php
    
    require_once $comp . "footer.php"; 
    ob_end_flush();
?>

<script src="mainDesign/js/addToCart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>



