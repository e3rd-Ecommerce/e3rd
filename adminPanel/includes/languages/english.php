<?php 

    function lang($phrase){
        
        static $lang = array(

            //NavBart Links
            'HOME_ADMIN'    => 'Home' ,
            'CATEGORIRES'   => 'Categories',
            'ITEMS'         => 'Items',
            'MEMBERS'       => 'Members',
            'STATISTICS'    => 'Statistics',
            'LOGS'          => 'Logs',
            'COMMENTS'      => 'Comments',
            '' => '',
            '' => '',
            '' => ''
        );
        return $lang[$phrase];
    }


?>