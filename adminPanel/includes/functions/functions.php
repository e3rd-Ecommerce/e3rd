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