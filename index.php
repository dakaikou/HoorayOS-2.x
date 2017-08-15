<?php
    require_once('global.php');

    $file_to_check = "config_db.inc.php";
		    
    if(!is_file($file_to_check)){
        header('Location:install/index.php');
    }
    else {
        include_once 'login/inc/function.login.php';
        
        if (checkLogin()){
    		header('Location:core/index.php');
//    		header('Location:core.php');
        }else{
    		header('Location:login/login.php');
    	}
	}
?>