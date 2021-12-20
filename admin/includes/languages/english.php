<?php 


    function lang($phrase){

        static $lang = array( 
        
            //dashbord page (navbar links)

            'HOME_ADMIN'            => 'E3RD' , 
            'category'              => 'categories' , 
            'item'                  => 'items' , 
            'members'               => 'members' , 
            'statistics'            => 'statistics' , 
            'logs'                  => 'logs' , 
            'username'              => 'UserName' , 
            'password'              => 'Password' , 
            'email'                 => 'Email' , 
            'full-name'             => 'FullName' , 
            'Edit-members'          => 'Edit members' , 
            'add-members'           => 'Add members' , 
            'mange-members'         => 'manage members' , 
            'delet-members'         => 'deletemembers' ,
            'add-new-catgory'       => 'add new catgory' , 
            'name-catgory'          => 'Name' ,
            'description-category'  => 'Description' ,
            'ordering-category'     => 'Ordering' ,
            'Visible-category'      => 'Visible' ,
            'commenting-category'   => 'allow commenting' ,
            'ads-catgory'           => 'allow ads' ,
            'add-category'          => 'insert category' ,
            'edit-catgory'          => 'Edit catgory' ,
            'update-category'       => 'Update category' ,
            'add-new-item' => 'add Items' ,
            'description-item' => 'Description' ,
            'price-item' => 'Price' ,
            'countryy-item' => 'Country' ,
            'status-item' => 'status' ,
            'rateing-item' => 'rateing' ,
            'add-items' => 'insert items' ,
            'member-item' => 'member' ,
            'category-filed' => 'Category' ,
            'mange-items' => 'mange items' ,
            'edit-item'=> 'Edit item' ,
            'update-item' => 'Update item' ,
            'comments' => 'comments' ,
            'comment-members' => 'manage comment ' ,
            'Edit-comment' => 'Edit comment' ,
            'comment' => 'comment' ,
            'update-comment' => 'update comment' ,
            '' => '' ,
            '' => '' ,
            '' => '' ,
            '' => '' ,
            '' => '' ,
            '' => '' ,
            '' => '' ,
            '' => '' ,
            '' => '' ,
            '' => '' ,
            '' => '' ,




        


            


        );

        return $lang[$phrase] ; 


    }

