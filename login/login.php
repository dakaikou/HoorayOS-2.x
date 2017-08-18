<?php
	require_once('../global.php');
	require_once 'inc/function.login.php';
	require_once 'inc/function.ip.php';
	require_once('third_party/Smarty-3.1.30/libs/Smarty.class.php');
	require_once 'dbase/db.class.php';
	require_once 'dbase/db.config.php';
	
	$db_login = new HRDB($db_hoorayos_config);
	
	if (!empty($_POST['ac'])){
    	    
	    $ac = $_POST['ac'];
	    $user_name = $_POST['user_name'];
	    $user_passwd = $_POST['user_passwd'];
	    
	    switch($ac){
    		//登入
    	    case 'login':
				$sqlwhere = array(
					"username = '$user_name'",
					"password = '".sha1($user_passwd)."'"
				);

				$row = $db_login->select(0, 1, 'tb_member', '*', $sqlwhere);
				if($row != NULL){
					$_SESSION['member']['id'] = $row['tbid'];
					$_SESSION['member']['name'] = $row['username'];
					$db_login->update(0, 0, 'tb_member', 'lastlogindt = now(), lastloginip = "'.getIp().'"', 'and tbid = '.$row['tbid']);
					echo 'success';
				}
				else {
				    echo '用户名或密码错误!';
				}
    			break;
    		//验证是否登入
    		case 'checkLogin':
    			if(checkLogin()){
    				echo 1;
    			}
    			break;
    		//注册
    		case 'reg':
    		    $isreg = $db_login->select(0, 1, 'tb_member', 'tbid', 'and username = "'.$user_name.'"');
    			if($isreg != NULL){
    				echo "用户'$user_name'已经存在。";
    			}else{
    				$set = array(
    					'username = "'.trim($user_name).'"',
    					'password = "'.sha1(trim($user_passwd)).'"',
    					'regdt = now()'
    				);
    				$db_login->insert(0, 0, 'tb_member', $set);
    				echo 'success';
    			}
    			break;
    		default:
    		    echo 'caonimabi';
    		    break;
    	}
	}
	else {
	    $smarty_login = new Smarty();
	    $smarty_login->template_dir = 'login/templates/';
	    $smarty_login->compile_dir = 'cache/templates_c/';
	    $smarty_login->left_delimiter = "{";
	    $smarty_login->right_delimiter = "}";
	    
	    $setting = $db_login->select(0, 1, 'tb_setting');
	    $smarty_login->assign('setting', $setting);
	    $smarty_login->display('login.tpl');
	}
?>