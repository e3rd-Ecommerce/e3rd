<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container">
    <a class="navbar-brand" href="dashbord.php"><?php echo lang('HOME_ADMIN') ; ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav-app">
    <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav-app">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="categories.php"><?php echo lang('category') ; ?></a> 
        </li>
        <li class="nav-item">
            <a class="nav-link" href="items.php"><?php echo lang('item') ; ?></a> 
        </li>
        <li class="nav-item">
            <a class="nav-link" href="members.php"><?php echo lang('members') ; ?></a> 
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#"><?php echo lang('statistics') ; ?></a> 
        </li>
        <li class="nav-item">
            <a class="nav-link" href="comment.php"><?php echo lang('comments') ; ?></a> 
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#"><?php echo lang('logs') ; ?></a> 
        </li>

    
        <li class="nav-item dropdown nav-color ">
            <a class="nav-link dropdown-toggle" href="#" id="navMenu" role="button" data-bs-toggle="dropdown">
            yazan
            </a>
            <ul class="dropdown-menu" aria-labelledby="navMenu">
                <li><a class="dropdown-item" href="members.php?do=edit&userid=<?php echo $_SESSION['ID']; ?> ">Edit profile</a></li>
                <li><a class="dropdown-item" href="#">Settings</a></li>
                <li><a class="dropdown-item" href="logout.php ">Logout</a></li>
            </ul>
        </li>

    </ul>
    </div>
    </div>
</nav> 