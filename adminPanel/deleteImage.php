
<?php
  require_once "init.php";

  if ($_POST['imagePath']) {
      $dirPath =$_POST['imagePath'];
      if (file_exists($dirPath)) {
          unlink($dirPath);
        }

  }
?>