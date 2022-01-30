 <?php


/*
** Get All Records Function v2.0
** Function To Get All Records From any DataBase table
*/

function getAllFrom($field, $table, $where = NULL, $and = NULL ,$orderfield, $ordering = "DESC"){

    global $con;

    $getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering");

    $getAll->execute();

    $records = $getAll->fetchAll();

    return $records;

}


/*
** Get Records Function v1.0
** Function To Get Categories From DataBase
*/

// function getCats(){

//     global $con;

//     $getCat = $con->prepare("SELECT * FROM categories ORDER BY ID ASC");

//     $getCat->execute();

//     $cats = $getCat->fetchAll();

//     return $cats;

// }

// /*
// ** Get AD's Items Function v1.0
// ** Function To Get AD's by Categories From DataBase
// */

// function getItems($where, $value,$approve = NULL ){

//     global $con;

//     $query ='';

//     if($approve == NULL){

//         $query = 'AND approve = 1';
//     }

//     $getItem = $con->prepare("SELECT * FROM items WHERE $where = ? $query ORDER BY item_ID DESC");

//     $getItem->execute(array($value));

//     $items = $getItem->fetchAll();

//     return $items;

// }

/*
** Check if User is not Activated
** Functioon To csheck The RegStatus Of the user
**
*/

function checkUserStatus($user){

    global $con;

    $query = $con->prepare("SELECT
                                UserName,RegStatus
                           FROM
                                users
                           WHERE 
                                UserName = ?
                           AND 
                                RegStatus = 0
                            ");

    $query->execute(array($user));

    $status = $query->rowCount();

    return $status;
}

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
 ** $custom = For Custom redirect page not index & not previous
*/

function redirectHome($theMsg, $url = null, $custom = null ,$seconds = 3){

    if($url === null && $custom == null){
        $url = 'index.php';
    }elseif (isset($custom) && $url == null){

        $url = $custom.'.php';
        $link = 'Previous Page';

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
