<?php
  ob_start();
  $userIdInSession = $_SESSION['ID'];

  //Select User Info 
  $stmt = $con->prepare("SELECT * FROM users WHERE userID = ?");

  //execute The Statement
  $stmt->execute(array($userIdInSession));

  //Assign To Varaible
  $userRow = $stmt->fetch();

  // echo "<pre>";
  // var_dump($userRow);
  // echo "</pre>";die;
?>

<!--Start Admin SideBar-->
<div class="sidebar">
  <div class="logo-details">
    <i class="fas fa-store"></i>
    <span class="logo_name">E3rd</span>
  </div>

  <ul class="nav-links">
    <li>
      <a href="dashboard.php">
        <i class="fas fa-th-large"></i>
        <span class="link_name">Dashboard</span>
      </a>
    </li>
    <li>
      <a href="categories.php">
        <i class="fas fa-sitemap"></i>
        <span class="link_name"><?php echo lang('CATEGORIRES') ?></span>
      </a>
    </li>
    <li>
      <a href="items.php">
        <i class="fas fa-puzzle-piece"></i>
        <span class="link_name"><?php echo lang('ITEMS') ?></span>
      </a>
    </li>
    <li>
      <a href="members.php">
        <i class="fas fa-users"></i>
        <span class="link_name"><?php echo lang('MEMBERS') ?></span>
      </a>
    </li>
    <li>
      <a href="comments.php">
      <i class="fas fa-comments"></i>
      <span class="link_name"><?php echo lang('COMMENTS') ?></span>
      </a>
    </li>
    <li>
      <a href="orders.php">
      <i class="fas fa-receipt"></i>
      <span class="link_name"><?php echo 'Orders' ; ?></span>
      </a>
    </li>
    <li>
      <a href="../index.php">
        <i class="fas fa-eye"></i>
        <span class="link_name"> Visit Shop</span>
      </a>
    </li>

    <li>
      <a href="members.php?do=Edit&userid=<?php echo $_SESSION['ID'] ?>">
        <i class="fas fa-edit"></i>
        <span class="link_name">Edit profile</span>
      </a>
    </li>

    <li>
      <a href="logout.php">
        <i class="fas fa-sign-out-alt"></i>
        <span class="link_name">Log out</span>
      </a>
    </li>

  </ul>
</div>




<!--Home section-->
<section class="home-section">
  <nav>
    <div class="sidebar-button">
      <i class="fas fa-bars sidebarBtn"></i>
      <span class="dashboard">Dashboard</span>
    </div>

    <div class="searchbox">
      <input type="text" placeholder="search...">
      <i class="fas fa-search"></i>
    </div>

    <div class="profile-details">
      <?php 

        if (!empty($userRow['avatar'])){
          echo '<img src="../images/'. $userRow['avatar'].'" alt="No avatar">';

        }
        else {
          echo '<img src="../images/no_avatar.png" alt="No avatar">';

        }
      ?>
      <span class="admin_name">
        <?php 
          if(!empty($userRow['UserName']))
          {echo $userRow['UserName'];}
          else{echo "Admin";}
          
        ?>
      </span>
    </div>
  </nav>
<?php 
  ob_end_flush();
?>