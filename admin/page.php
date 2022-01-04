<?php 

//  categories => [manage | edit | update | add | insert | delete | stats ]

$do= '' ; 

if(isset($_GET['do'])) {

    $do=  $_GET['do']; 

}else  {
    $do ='manage' ; // الصفحة الرئيسية

}


// if the page is main page

if($do == 'manage') {
echo 'welcome you are in manage category page' ;

echo '<a href="page.php?do=add"> add new category </a>' ; 

}
elseif($do=='add'){
    echo 'you are in add category page';

}
elseif ($do == 'insert') {

    echo 'you are in insert category page';
}

else {
    echo 'Error there is no page with this name ' ; 
}

