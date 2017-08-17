<?php
	require_once('../../global.php');
	require_once 'sysapp/common/inc/error_code.php';
	require_once 'sysapp/common/inc/function.db.php';
	require_once('third_party/php/Smarty-3.1.30/libs/Smarty.class.php');
	require_once 'dbase/db.class.php';
	require_once 'dbase/db.config.php';
	
	$db_detail = new HRDB($db_hoorayos_config);
	
	$smarty_detail = new Smarty();
	$smarty_detail->template_dir = 'sysapp/member/templates/';
	$smarty_detail->compile_dir = 'cache/templates_c/';
	$smarty_detail->left_delimiter = "{";
	$smarty_detail->right_delimiter = "}";
	
	$smarty_detail->assign('errorcode', $errorcode);
	
	//验证是否登入
	if(!checkLogin()){
	    $smarty_detail->assign('code', $errorcode['noLogin']);
	    $smarty_detail->display('error.tpl');
		exit;
	}
	//验证是否为管理员
	else if(!checkAdmin($db_detail)){
	    $smarty_detail->assign('code', $errorcode['noAdmin']);
	    $smarty_detail->display('error.tpl');
		exit;
	}
	//验证是否有权限
	else if(!checkPermissions($db_detail, 3)){
	    $smarty_detail->assign('code', $errorcode['noPermissions']);
	    $smarty_detail->display('error.tpl');
		exit;
	}
	
	trim(@extract($_POST));
	trim(@extract($_GET));
	if (empty($ac)) {
	    $ac = '';
	}
	
	switch($ac){
		case 'ajaxEdit':
			$val_password = $val_password == '' ? $val_password : sha1($val_password);			
			if($value_1 == ''){
				$set = array(
					"username = '$val_username'",
					"password = '$val_password'",
					"type = $val_type"
				);
				if($value_4 == 1){
					$set[] = "permission_id = '$val_permission_id'";
				}
				$db_detail->insert(0, 0, 'tb_member', $set);
			}else{
				$set = array("type = $val_type");
				if($password != ''){
					$set[] = "password = '$val_password'";
				}
				if($value_4 == 1){
					$set[] = "permission_id = '$val_permission_id'";
				}else{
					$set[] = "permission_id = ''";
				}
				$db_detail->update(0, 0, 'tb_member', $set, "and tbid = $id");
			}
			break;
		default:
		    if($memberid != NULL){
		        $rs = $db_detail->select(0, 1, 'tb_member', '*', 'and tbid = '.$memberid);
		        $smarty_detail->assign('member', $rs);
		    }
		    $rs = $db_detail->select(0, 0, 'tb_permission', 'tbid,name');
		    $smarty_detail->assign('permission', $rs);
		    $smarty_detail->display('detail.tpl');
		    break;
	}
?>