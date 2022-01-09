<?php 
ob_start();

session_start() ; 

if (isset($_SESSION['username'])) {

    $pageTitle= 'dashbord'; 
    include_once 'init.php'; 

    // start dashborad page
    
    $numusers = 5; // number of the latest users
    
    $thelatestusers = getlatest("*" , "users" , "WHERE groupid != 1" , "userid" , $numusers) ; //فنكشن

    $numitems = 5; //number of the latest item

    $numcomment = 5 ; // number of comment


    ?>
        <!--  -->
        <!--  -->
        <!--  -->
        <!--  -->
        <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
            <div class="col-12 grid-margin stretch-card">
                    <!-- // -->
            </div>
            </div>
            <div class="row">
                <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                    <div class="card">
                    <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <div class="d-flex align-items-center align-self-start">
                                    <h2 class="mb-0"> <a class="text-dashbord" href="members.php"><?php echo countItems('userid' ,'users' ) ?> </a> </h2>
                                    </div>
                                </div>
                            <div class="col-4">
                                <div class="icon icon-box-success ">
                                <i class="fa fa-users icon-dashbord"></i>
                                </div>
                            </div>
                            </div>
                        <h6 class="font-weight-normal"> <a class="text-dashbord" href="members.php"> Total Members </a></h6>
                        </div>
                    </div>
                </div>

            <!--  -->
                <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                    <div class="card">
                    <div class="card-body">
                        <div class="row">
                        <div class="col-9">
                            <div class="d-flex align-items-center align-self-start">
                            <h2 class="mb-0 "><a class="text-dashbord" href="members.php?do=manage&page=panding"><?php echo checkitem("regstatus","users", 0 )?></a></h2>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="icon icon-box-success">
                            <span><i class="fa fa-user-plus icon-dashbord"></i></span>
                            </div>
                        </div>
                        </div>
                        <h6 class="font-weight-normal"> <a class="text-dashbord" href="members.php?do=manage&page=panding"> Pending Members </a></h6>
                    </div>
                    </div>
                </div>
<!--  -->
                <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                    <div class="card">
                    <div class="card-body">
                        <div class="row">
                        <div class="col-8">
                            <div class="d-flex align-items-center align-self-start">
                            <h2 class="mb-0"><a class="text-dashbord" href="items.php"><?php echo countItems('item_id' ,'items') ?></a></h2>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="icon icon-box-danger">
                            <span><i class="fa fa-tag icon-dashbord"></i></span>
                            </div>
                        </div>
                        </div>
                        <h6 class="font-weight-normal"> <a class="text-dashbord" href="items.php"> Total Items </a></h6>
                    </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                    <div class="card">
                    <div class="card-body">
                        <div class="row">
                        <div class="col-8">
                            <div class="d-flex align-items-center align-self-start">
                            <h2 class="mb-0"><a class="text-dashbord" href="comment.php"><?php echo countItems('c_id' ,'comment') ?></a></h2>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="icon icon-box-success ">
                            <span><i class="fa fa-comments icon-dashbord"></i></span>
                            </div>
                        </div>
                        </div>
                        <h6 class="font-weight-normal text-dashbord"> <a class="text-dashbord" href="comment.php"> Total Comments </a></h6>
                    </div>
                    </div>
                </div>
                </div>
                </div>
                <!--  -->
                <!--  -->
                <!--  -->
                <!--  -->
            
        <div class="row ">
            <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body"> 
                <h4 class="card-title">Latest <span class="number-color"> <?php echo $numusers ; ?> </span> Registered Users</h4>
                <div class="table-responsive">
                    <table class="table">
                    <thead>
                        <tr>
                        
                        <th> Client Name </th>
                        <th> Full Name </th>
                        <th> Registerd Date </th>
                        <th> Control </th>
                        </tr>
                    </thead>

                    <tbody>
                

                    <?php  if(! empty($thelatestusers)){ 

                        foreach($thelatestusers as $user ){
                        
                        ?>
                        <tr>
                        
                        <td>
                            <img src="../imageAdmin/<?=$user['image']?>" alt="image" />
                            <span class="pl-2"> <?php echo $user['username'] ; ?></span>
                        </td>
                        <td> <?php echo $user['fullname'] ; ?>  </td>
                        <td> <?php echo $user['date'] ; ?> </td>
                        <td>
                            
                                <?php 
                                if($user['regstatus'] ==0 ){
                                    echo "<a href='members.php?do=activate&userid=" .$user['userid'] . "'><span class='badge badge-outline-success' > activate </span></a>";
                                    }

                                    echo '<a href="members.php?do=edit&userid=' . $user['userid'] . ' ">  ';
                                    echo '<span class="badge badge-outline-warning" > Edit ' ;
                                    echo '</span>';
                                    echo '</a>' ; 
                                    echo "<a href='members.php?do=delete&userid=" .$user['userid'] . "'class='badge badge-outline-danger ml-1'>del</a>"; 

                                ?>
                        </td>
                        </tr>
                <?php }
                            } else {
                                    echo '<p class="text-muted"> there\'s No member to show  </p> ';
                            }
                ?>
                    </tbody>
                    </table>
                </div>
                </div>
            </div>
            </div>
        </div>






        
            <!--  -->
            <!--  -->
            <!--  -->
            <!--  -->
            <div class="row">
                <div class="col-md-6 col-xl-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                    <div class="d-flex flex-row justify-content-between">
                        <h4 class="card-title"> Latest <span class="number-color"> <?php echo $numcomment ;?> </span>  comment </h4>
                    </div>

                <div class="preview-list">
                        
                        <!--  -->
                        <?php 
                                    $stmt = $con->prepare("SELECT
                                    comment.*, users.image , users.username AS member
                                    FROM 
                                    comment 
                                    INNER JOIN 
                                    users
                                    ON
                                    users.userid= comment.user_id
                                    ORDER BY c_id DESC
                                    limit $numcomment
                    
                                                            ")  ;  
                                    $stmt->execute(); 

                                    //assign to variable 
                                    $comments = $stmt->fetchAll();

                                    
                                    if(!empty($comments)){
                                        foreach($comments as $comment){
                        
                        ?>
                        <!--  -->

                        <div class="preview-item border-bottom">
                            <div class="preview-thumbnail">
                            <img src="../imageAdmin/<?=$comment['image']?>" alt="image" class="rounded-circle" />
                            </div>

                            <div class="preview-item-content d-flex flex-grow">
                            <div class="flex-grow">
                                <div class="d-flex d-md-block d-xl-flex justify-content-between">
                                <h6 class="preview-subject"><?php echo $comment['member'];  ?></h6>
                                <p class="text-muted text-small"><?php echo $comment['comment_date'] ;?></p>
                                </div>
                                <p class="text-muted"><?php echo $comment['comment'] ; ?></p>
                            </div>
                            </div>
                        </div>
                        

                        <?php
                                }
                            }else{
                                echo '<p class="text-muted"> there\'s No comment to show </p>';

                            }
                                    ?>
                    </div>
                    </div>
                </div>
                </div>

                <!--  -->
                <!--  -->
                <!--  -->
                <!--  -->
              <div class="col-md-12 col-xl-8 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">To do list</h4>
                    <div class="add-items d-flex">
                      <input type="text" class="form-control todo-list-input" placeholder="enter task..">
                      <button class="add btn btn-primary todo-list-add-btn">Add</button>
                    </div>
                    <div class="list-wrapper">
                      <ul class="d-flex flex-column-reverse text-white todo-list todo-list-custom">
                        <li>
                          <div class="form-check form-check-primary">
                            <label class="form-check-label">
                              <input class="checkbox" type="checkbox"> Create invoice </label>
                          </div>
                          <i class="remove mdi mdi-close-box"></i>
                        </li>
                        <li>
                          <div class="form-check form-check-primary">
                            <label class="form-check-label">
                              <input class="checkbox" type="checkbox"> Meeting with Alita </label>
                          </div>
                          <i class="remove mdi mdi-close-box"></i>
                        </li>
                        <li class="completed">
                          <div class="form-check form-check-primary">
                            <label class="form-check-label">
                              <input class="checkbox" type="checkbox" checked> Prepare for presentation </label>
                          </div>
                          <i class="remove mdi mdi-close-box"></i>
                        </li>
                        <li>
                          <div class="form-check form-check-primary">
                            <label class="form-check-label">
                              <input class="checkbox" type="checkbox"> Plan weekend outing </label>
                          </div>
                          <i class="remove mdi mdi-close-box"></i>
                        </li>
                        <li>
                          <div class="form-check form-check-primary">
                            <label class="form-check-label">
                              <input class="checkbox" type="checkbox"> Pick up kids from school </label>
                          </div>
                          <i class="remove mdi mdi-close-box"></i>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>

              </div>
            </div>

            <!--  -->
            <!--  -->
            <!--  -->
            <!--  -->
            <div class="row ">
            <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body"> 
                <h4 class="card-title">Latest <span class="number-color"> <?php echo $numitems;?> </span> Items </h4>
                <div class="table-responsive">
                    <table class="table">
                    <thead>
                        <tr>
                        <th> Item Name </th>
                        <th> price </th>
                        <th> Adding Date </th>
                        <th> Category </th>
                        <th> UserName </th>
                        <th> Control </th>
                        </tr>
                    </thead>

                    <tbody>
                

                    <?php  

                                        $stmt = $con->prepare("SELECT 
                                        items.*,
                                        categories.name AS catgory_name,
                                        users.username AS user_name 
                                        FROM 
                                            items 
                                        INNER JOIN 
                                            categories 
                                        ON 
                                            categories.id =items.cat_id 
                                        INNER JOIN 
                                            users 
                                        ON 
                                            users.userid = items.member_id
                                        ORDER BY item_id DESC 
                                        LIMIT $numitems")  ; //جلب البيانات مع عمل اينر جوين للتيبلات الثانية 

                                        $stmt->execute(); 

                                        //assign to variable 
                                        $items = $stmt->fetchAll();
                    
                    
                    if(!empty($items)){ 

                        foreach($items as $item){
                            $image ='';
                            $d = $item['images'];
                            $dirname = "../imageItems/$d/";
                            $images = glob($dirname."*.*");
                            for ($i=0; $i<count($images); $i++)
                            {
                                $image = $images[$i];
                                $supported_file = array(
                                        'gif',
                                        'jpg',
                                        'jpeg',
                                        'png',
                                        'jfif'
                                );
                                $ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));
                            }
                        ?>
                        <tr>
                        
                        <td>
                            <img src="<?=$image?>" alt="image" />
                            <span class="pl-2"> <?php echo $item['name'] ; ?></span>
                        </td>
                        
                        <td> <?php echo $item['price'] ; ?>  </td>
                        <td> <?php echo $item['add_date'] ; ?> </td>
                        <td> <?php echo $item['catgory_name']; ?> </td>
                        <td> <?php echo $item['user_name'] ; ?> </td>

                        <td>
                            
                                <?php 
                                if($item['approve'] ==0 ){

                                    echo "<a href='items.php?do=approve&itemid=" .$item['item_id'] . "'span class='badge badge-outline-success'>approve</a>";
    
                                    }

                                    
                                    echo '<a href="items.php?do=edit&itemid=' . $item['item_id'] . ' ">  ';
                                        echo '<span class="badge badge-outline-warning" > Edit ' ;
                                        echo '</span>';
                                    echo '</a>' ; 
                                    echo "<a href='items.php?do=delete&itemid=" .$item['item_id'] . "'class='badge badge-outline-danger ml-1'>del</a>"; 

                                    

                                ?>
                        </td>
                        </tr>
                <?php }
                            } else {
                                    echo '<p class="text-muted"> there\'s No member to show  </p> ';
                            }
                ?>
                    </tbody>
                    </table>
                </div>
                </div>
            </div>
            </div>
        </div>
            <!--  -->
            <!--  -->
            <!--  -->
            <!--  -->

            

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                  
                  
                      <div class="col-md-12">
                        <div id="audience-map" class="vector-map"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>



          <!-- content-wrapper ends -->
          
        </div>



       <!-- 
     -->
     <!--
      -->

    <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
        <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © E3RD 2020</span>
        </div>
    </footer>
    <?php
    // end dashborad page

    
    include_once $tpl . 'footer.php' ; 

} 
else { 

    // echo 'you are not authorized to view this page  ' ; 
    header('location: index.php') ;
    exit(); 

    }
ob_end_flush();

?> 