<?php
$statement = $con->prepare('SELECT * FROM users WHERE userid  = ?');
$statement->execute(array($_SESSION['ID']));
$row_user = $statement->fetch(PDO::FETCH_ASSOC);
?>
<!--  ناف بار الشمال -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
        <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
            <a class="sidebar-brand brand-logo" href="dashbord.php"> <?php echo lang('HOME_ADMIN') ; ?> </a>  
            <a class="sidebar-brand brand-logo-mini" href="dashbord.php">E</a>
        </div>
        <ul class="nav">
    <li class="nav-item profile">
    <div class="profile-desc">
        <div class="profile-pic">
            <div class="count-indicator">
                <img class="img-xs rounded-circle " src="../imageAdmin/<?=$row_user['image']?>" alt="<?=$row_user['image']?>">
                <span class="count bg-success"></span>
                </div>
                    <div class="profile-name">
                        <h5 class="mb-0 font-weight-normal"><?=$row_user['username']?></h5> 
                        <span>Admin</span>
                    </div>
                </div>


            <a href="#" id="profile-dropdown" data-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></a>
            <div class="dropdown-menu dropdown-menu-right sidebar-dropdown preview-list" aria-labelledby="profile-dropdown">
            </a> 
                <div class="dropdown-divider"></div>
                
                <a class="dropdown-item preview-item" href="members.php?do=edit&userid=<?php echo $_SESSION['ID']; ?> "> 
                        <div class="preview-thumbnail">
                        <div class="preview-icon bg-dark rounded-circle">
                            <i class="mdi mdi-settings text-success"></i>
                        </div>
                        </div>
                        <div class="preview-item-content">
                        <p class="preview-subject mb-1">Edit profile</p>
                        </div>
                </a>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                    <div class="preview-icon bg-dark rounded-circle">
                    <i class="mdi mdi-calendar-today text-success"></i>
                    </div>
                </div>

                <div class="preview-item-content"  >
                    <p class="preview-subject ellipsis mb-1 text-small">To-do list</p>
                </div>
                </a>
            </div>
            </div>
<!--  -->
        </li>
        <li class="nav-item nav-category">
            <span class="nav-link">Navigation</span>
        </li>
<!--  -->
        <li class="nav-item menu-items">
            <a class="nav-link" href="dashbord.php">
            <span class="menu-icon">
                <i class="mdi mdi-speedometer"></i>
            </span>
            <span class="menu-title">Dashboard</span>
            </a>
        </li>
<!--  -->
        <li class="nav-item menu-items">
            <a class="nav-link"  href="categories.php" >
            <span class="menu-icon">
                <i class="mdi mdi-laptop"></i>
            </span>
            <span class="menu-title"> <?php echo lang('category') ; ?> </span>
            </a>
        </li>
<!--  -->
        <li class="nav-item menu-items">
        <a class="nav-link" href="items.php">
            <span class="menu-icon">
                <i class="mdi mdi-playlist-play"></i>
            </span>
            <span class="menu-title"><?php echo lang('item') ; ?> </span>
            </a>
        </li>
<!--  -->
        <li class="nav-item menu-items">
            <a class="nav-link" href="members.php">
            <span class="menu-icon">
                <i class="mdi mdi-table-large"></i>
            </span>
            <span class="menu-title"><?php echo lang('members') ; ?></span>
            </a>
        </li>
<!--  -->
        <li class="nav-item menu-items">
            <a class="nav-link" href="comment.php">
            <span class="menu-icon">
                <i class="mdi mdi-chart-bar"></i>
            </span>
            <span class="menu-title"> <?php echo lang('comments') ; ?> </span>
            </a>
        </li>
<!--  -->
        <li class="nav-item menu-items">
            <a class="nav-link" href="#">
            <span class="menu-icon">
                <i class="mdi mdi-contacts"></i>
            </span>
            <span class="menu-title"><?php echo lang('logs') ; ?></span>
            </a>
        </li>
<!--  -->
        </ul>
    </nav>

<!-- ناف بار الفوقاني -->

<div class="container-fluid page-body-wrapper" style="margin-top: 73px;">
    <nav class="navbar p-0 fixed-top d-flex flex-row">
    <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
        <!-- <a class="navbar-brand brand-logo-mini" href="index.html"><img src="assets/images/logo-mini.svg" alt="logo" /></a> -->
    </div>

    <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
        <span class="mdi mdi-menu"></span>
        </button>

        <!-- البحث   -->
        <!-- <ul class="navbar-nav w-100">
        <li class="nav-item w-100">
            <form class="nav-link mt-2 mt-md-0 d-none d-lg-flex search">
            <input type="text" class="form-control" placeholder="Search products">
            </form>
        </li>
        </ul> -->
        <!-- البحث -->

    <ul class="navbar-nav navbar-nav-right">
    <li class="nav-item dropdown d-none d-lg-block">
        <a class="btn btn-success create-new-button"  href="items.php?do=add"> + Create New Item</a>
    </li>
<!--  -->
    <li class="nav-item dropdown">
    <a class="nav-link" id="profileDropdown" href="#" data-toggle="dropdown">
    <div class="navbar-profile">
        <img class="img-xs rounded-circle" src="../imageAdmin/<?=$row_user['image']?>" alt="<?=$row_user['image']?>">
        <p class="mb-0 d-none d-sm-block navbar-profile-name"><?=$row_user['username']?></p>
        <i class="mdi mdi-menu-down d-none d-sm-block"></i>
    </div>
    </a>
    <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="profileDropdown">
    <!--  -->
    <div class="dropdown-divider"></div>
            <a class="dropdown-item preview-item" href="../index.php"> 
                    <div class="preview-thumbnail">
                    <div class="preview-icon bg-dark rounded-circle">
                    <i class="mdi mdi-calendar-today text-success"></i>
                    </div>
                    </div>
                    <div class="preview-item-content">
                    <p class="preview-subject mb-1">visit shop</p>
                    </div>
            </a>
<!--  -->
    <div class="dropdown-divider"></div>
            <a class="dropdown-item preview-item" href="members.php?do=edit&userid=<?php echo $_SESSION['ID']; ?> "> 
                    <div class="preview-thumbnail">
                    <div class="preview-icon bg-dark rounded-circle">
                        <i class="mdi mdi-settings text-success"></i>
                    </div>
                    </div>
                    <div class="preview-item-content">
                    <p class="preview-subject mb-1">Edit profile</p>
                    </div>
            </a>
<!--  -->
        <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item" href="logout.php">
                    <div class="preview-thumbnail">
                    <div class="preview-icon bg-dark rounded-circle">
                        <i class="mdi mdi-logout text-danger"></i>
                    </div>
                    </div>
                    <div class="preview-item-content">
                    <p class="preview-subject mb-1">Log out</p>
                    </div>
                </a>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                <span class="mdi mdi-format-line-spacing"></span>
        </button>
    </div>
</nav>