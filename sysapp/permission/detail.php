<?php
	require_once('../../global.php');
	require_once 'sysapp/common/inc/error_code.php';
	require_once 'sysapp/common/inc/function.db.php';
	require_once('third_party/php/Smarty-3.1.30/libs/Smarty.class.php');
	require_once 'dbase/db.class.php';
	require_once 'dbase/db.config.php';
	
	$db_detail = new HRDB($db_hoorayos_config);
	
	$smarty_detail = new Smarty();
	$smarty_detail->template_dir = 'sysapp/permission/templates/';
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
	else if(!checkPermissions($db_detail, 4)){
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
			if($tbid == ''){
				$set = array(
					"name = '$val_name'",
					"apps_id = '$val_apps_id'"
				);
				$db_detail->insert(0, 0, 'tb_permission', $set);
			}else{
				$db_detail->update(0, 0, 'tb_permission', "apps_id='$val_apps_id'", "and tbid = $id" );
			}
			break;
		case 'updateApps':
			$appsrs = $db_detail->select(0, 0, 'tb_app', 'tbid,name,icon', 'and tbid in ('.$appsid.')');
			foreach($appsrs as $a){
				echo '<div class="app" appid="'.$a['tbid'].'"><img src="../../'.$a['icon'].'" alt="'.$a['name'].'" title="'.$a['icon'].'"><span class="del">删</span></div>';
			}
			break;
		default:
			if($permissionid != NULL){
				$rs = $db_detail->select(0, 1, 'tb_permission', '*', 'and tbid = '.$permissionid);
				if($rs['apps_id'] != ''){
					$appsrs = $db_detail->select(0, 0, 'tb_app', 'tbid,name,icon', 'and tbid in ('.$rs['apps_id'].')');
					$rs['appsinfo'] = $appsrs;
				}
				$smarty_detail->assign('permission', $rs);
			}
			$smarty_detail->display('detail.tpl');
			break;
	}
?>