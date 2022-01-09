<?php



// get all function v2.0 
// function to get all from any database table 

function getallfrom($filed , $table , $where= null , $and = NULL ,$orderfiled  , $ordering = "DESC"  ){

    global $con  ; 

    // $$where = $where == null ? '' : $where ;  

    $getall = $con->prepare("SELECT $filed FROM $table $where $and  ORDER BY $orderfiled $ordering  ") ; 

    $getall->execute(); 

    $all = $getall->fetchAll(); 

    return $all ; 

}


// get all function v1.0 
// function to get all from any database table 

//function getallfrom($tablename , $id , $where = null ){
    // global $con  ; 
    // // $$where = $where == null ? '' : $where ;  
    // $getall = $con->prepare("SELECT * FROM $tablename $where ORDER BY $id ASC  ") ; 
    // $getall->execute(); 
    // $all = $getall->fetchAll(); 
    // return $all ; 
//}


// get category function v1.0 
// function to get categories from database 
function getcat(){

    global $con  ; 

    $getcat = $con->prepare("SELECT * FROM categories ORDER BY id ASC ") ; 

    $getcat->execute(); 

    $cat = $getcat->fetchAll(); 

    return $cat ; 

}


// get ad items function v2.0 
// function to get items from database 

function getitem($where , $value , $approve = null){

    global $con  ; 

    if($approve == null){
        $sql = 'AND approve = 1';
    }
    else{
        $sql = null ;
    }

    $getitems = $con->prepare("SELECT * FROM items WHERE $where = ?  $sql ORDER BY item_id DESC ") ; 

    $getitems->execute(array($value)); 

    $items = $getitems->fetchAll(); 

    return $items ;  
    
}


// check if user not activated 
// function to ckeck the reqstatus of the user 
function checkUserStatus($user){

        global $con ; 

        $stmtx = $con->prepare("SELECT
                                    username,regstatus 
                                FROM 
                                    users 
                                WHERE
                                    username = ?  
                                AND
                                    regstatus = 0 
                                ");

            $stmtx->execute(array($user));
            $status=$stmtx->rowCount(); 

            return $status ; 

}


// v1.0
// title function that echo the page title in case the page 
// has the variable $pagetitle and echo defult title for other pages 
function getTitle(){ 
    global $pageTitle ; 
    if(isset($pageTitle)){
        echo $pageTitle ; 
    }
    else { 
        echo 'default' ; 
    }
}




//home redirect function v2.0
//$theMsg = echo error message [error | success | warning]
//$url = the link want to redirect to 
//$seconds = seconds before redirecting
function redircthome($theMsg ,$url = null ,  $seconds = 3 ){

    if($url === null){
        $url = 'index.php' ; 
        $link = 'homepage' ; 
    }

    else {
        // اذا كان في صفحة انا جاي منها وبقدر ارجعلها 
        if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !=='' ){
            $url = $_SERVER['HTTP_REFERER'];
            
            $link ='previous page' ; 
        } 
        else{
            $url = 'index.php' ; 
            $link = 'homepage' ;
        }

    }

    echo $theMsg ; 

    echo "<div class='alert alert-info'> you will be redirected to $link after $seconds seconds .</div>";
    
    header("refresh: $seconds;url=$url") ; 
    exit() ; 
}




// function to check item in database [function accept parameters]
// $select = the item to select [ex : user , item , category ]
//$from = the table to select form [ex: users , items , categories]
//$value = the value of select [ex: yazan , box , elcetronics]
function checkitem($select,$from,$value){

    global $con ; 

    $statemnt = $con->prepare("SELECT $select FROM $from WHERE  $select= ? ") ; 
    $statemnt->execute(array($value)); 
    $count = $statemnt->rowCount(); 

    return $count ; 

}




// count number of items function v1.0
// function to count number of item rows
// $item = the item to count
// $table= the table to choose from 
function countItems($item , $table){

    global $con ;

    $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table"); 
    $stmt2->execute();

    return $stmt2->fetchColumn() ; 
}




// get latest records function v1.0 
// function to get latest item database [users , item , comments]
// $select =field to select
// $table = filed to table
//$limit = number of record to get
//$order = the desc ordering 
function getlatest($select , $table , $order , $limit = 5 ){

    global $con  ; 

    $getstmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC   LIMIT $limit ") ; 
    $getstmt->execute(); 

    $row = $getstmt->fetchAll(); 

    return $row ; 

}

