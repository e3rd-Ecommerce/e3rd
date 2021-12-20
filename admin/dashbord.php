<?php 
ob_start();

session_start() ; 

if (isset($_SESSION['username'])) {

    $pageTitle= (isset($_SESSION['username']) ? $_SESSION['username']." Admin" : "Dashbord Admin"); 
    include_once 'init.php'; 

    // start dashborad page
    
    $numusers = 5; // number of the latest users
    
    $thelatestusers = getlatest("*" , "users"  , "userid" , $numusers) ; //فنكشن

    $numitems = 5; //number of the latest item

    $latestItem = getlatest("*" , "items"  , "item_id" , $numitems) ; // فنكشن


    ?>
        <div class="home-stats">
            <div class="container  text-center">
                <h1><?= $pageTitle; ?></h1>
                <div class="row text-center justify-content-center">
                    <div class="col-md-3">
                        <div class="stat st-members">
                            <i class="fa fa-users"></i>
                        <div class="info">
                        total members
                            <span> <a href="members.php"><?php echo countItems('userid' ,'users') ?></a></span>
                        </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat st-pending">
                        <i class="fa fa-user-plus"></i>
                        <div class="info">
                            pending members
                            <span><a href="members.php?do=manage&page=panding">
                                <?php echo checkitem("regstatus","users", 0 )?>
                            </a></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 ">
                        <div class="stat st-items">
                            <i class="fa fa-tag"></i>
                        <div class="info">
                            total items
                            <!-- فنكشن -->
                            <span> <a href="items.php"><?php echo countItems('item_id' ,'items') ?></a></span> 
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat st-comments">
                        <i class="fa fa-comments"></i>
                        <div class="info">
                            total comments
                            <span>0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="latest">
            <div class="container ">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card">
                            
                            <div class="card-header">
                                Latest <?php echo $numusers ; ?> Registerd Users
                                <span class="toggle-info float-end">
                                    <i class="fa fa-plus fa-lg"></i>
                                </span>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled latest-users">
                                    <?php 

                                    foreach($thelatestusers as $user ){


                                        echo '<li>' ;
                                        echo  $user['username'] ;
                                        echo '<a href="members.php?do=edit&userid=' . $user['userid'] . ' ">  ';
                                        echo '<span class="btn btn-success float-end" > Edit ' ;

                                            if($user['regstatus'] ==0 ){

                                                echo "<a href='members.php?do=activate&userid=" .$user['userid'] . "'class='btn btn-info  float-end'>activate</a>";
                
                                                }

                                        echo "<a href='members.php?do=delete&userid=" .$user['userid'] . "'class='btn btn-danger float-end confirm'>del</a>"; 


                                        echo '</span>';
                                        echo '</a>' ;
                                        echo '</li>' ; 
                                    }
                                    
                                    
                                    ?>
                            </ul>


                            </div> 
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                latest <?php echo $numitems;?> items 
                                <span class="toggle-info float-end">
                                    <i class="fa fa-plus fa-lg"></i>
                                </span>
                            </div>
                            <div class="card-body">
                            <ul class="list-unstyled latest-users">
                                    <?php 

                                    foreach($latestItem as $item ){


                                        echo '<li>' ;
                                        echo  $item['name'] ;
                                        echo '<a href="items.php?do=edit&itemid=' . $item['item_id'] . ' ">  ';
                                        echo '<span class="btn btn-success float-end" > Edit ' ;

                                            if($item['approve'] ==0 ){

                                                echo "<a href='items.php?do=approve&itemid=" .$item['item_id'] . "'class='btn btn-info  float-end'>approve</a>";
                
                                                }

                                        echo "<a href='items.php?do=delete&itemid=" .$item['item_id'] . "'class='btn btn-danger float-end confirm'>del</a>"; 


                                        echo '</span>';
                                        echo '</a>' ;
                                        echo '</li>' ; 
                                    }
                                    
                                    
                                    ?>
                            </ul>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>

    





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