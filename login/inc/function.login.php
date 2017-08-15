<?php
	//验证是否登入
	function checkLogin(){
	    
	    $login_state = false;
	    
	    if (isset($_SESSION)){
	        if (!empty($_SESSION['member'])){
    	        $login_state = true;
	        }
	    }
	        
		return $login_state;
	}

?>