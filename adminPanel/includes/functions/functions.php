 <?php

/*
    **Title Function That Echo The Page Title In Case The Page
    ** Has The Variable $pageTitle And Echo Default Title For Other Page
*/

function printTitle(){

    global $pageTitle;

    if(isset($pageTitle)){
        echo $pageTitle;
    } else {
        echo 'Default';
    }

}

/*
 ** Home Redirect Function [This Function Accept Parameters] V2.0
 ** $theMsg = echo The  Message [Error | Success |Warining]
 ** Url = The Link You Want To redirect
 ** $seconds = Seconds Before Directing
*/

function redirectHome($theMsg, $url = null ,$seconds = 3){

    if($url === null){

        $url = 'index.php';
    } else {

        if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '' ){
            
            $url = $_SERVER['HTTP_REFERER']; //المكان اللي انا جاي منه
            $link = 'Previous Page';

        } else {

            $url = 'index.php';
            $link = 'Homepage';
        }

    }

    echo $theMsg;

    echo "<div class='alert alert-info'>You Will Be Redirected to $link Atfer $seconds Seconds</div>";

    header("refresh:$seconds; URL=$url");

    exit();

};

/*
 ** Check Items Function V1.0
 ** Function To Check In DataBase(يعني بإختصار هذا فنكشن الاستعلام بشكل دايناميك عشان اشي على اي اشي اذا موجود بالداتابيز او لا)
 ** This function Accept Parameters :
    ** $select = The Item To select [ممكن تكون user / item / category]
    ** $from = The Table To Select From [Example : users , Items, categories] 
    ** $value = The Value Of Select [Example : osama, box, elecrtonics]
 **
*/

function checkItem($select, $from, $value){

    global $con;

    $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
    $statement->execute(array($value));

    $count = $statement->rowCount();//هذا السطر اللي راح اعتمد عليه في معرفة ما اذا كان الاشي موجود بالداتا بيز او لا

    return $count;

};

/*  
    ** Count Number Of Items(Users, Products, etc...)(فنكشن بتعدلي اي اشي بحتاجه)
    ** Function To count Number Of Items Rows
    ** Item = The Item To count
    ** Table = the Table To choose from
*/

function countItems($item , $table){

    global $con;
    
    $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");

    $stmt2->execute();

    return $stmt2->fetchColumn();
}

/*
** Get Latest Records Function v1.0
** Function To Get Latest Items From DataBase[Users,Items,comments]
** $select = Field To select 
** $table = The Table To Choose From
** $order = The Variable to order the returned row by it.
** $limit = Number Of records To Get
*/

function getLatest($select, $table, $order, $limit = 5){

    global $con;

    $getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");

    $getStmt->execute();

    $rows = $getStmt->fetchAll();

    return $rows;

}