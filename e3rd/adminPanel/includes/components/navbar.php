<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="#"><?php echo lang('HOME_ADMIN') ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav-app">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav-app">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="#"><?php echo lang('CATEGORIRES') ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><?php echo lang('ITEMS') ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><?php echo lang('MEMBERS') ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><?php echo lang('STATISTICS') ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><?php echo lang('LOGS') ?></a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navMenu" role="button" data-bs-toggle="dropdown">
            Mahmood
          </a>
          <ul class="dropdown-menu" aria-labelledby="navMenu">
            <li><a class="dropdown-item" href="members.php?do=Edit&userid=<?php echo $_SESSION['ID'] ?>">Edit profile</a></li>
            <li><a class="dropdown-item" href="#">Settings</a></li>
            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav> 