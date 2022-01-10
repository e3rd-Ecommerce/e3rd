
<?php
require_once "init.php";

if ($_POST['imagePath']) {
    $item_id = $_POST['item_id'];
    $dirPath =$_POST['imagePath'];
    if (file_exists($dirPath)) {
        unlink($dirPath);
      }

}
?>