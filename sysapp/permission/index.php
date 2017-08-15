<?php
	require_once('../../global.php');
    require_once 'sysapp/common/inc/error_code.php';
	require_once 'sysapp/common/inc/function.db.php';
	require_once 'third_party/php/Smarty-3.1.30/libs/Smarty.class.php';
	require_once 'dbase/db.class.php';
	require_once 'dbase/db.config.php';
	
	$db_permission = new HRDB($db_hoorayos_config);
	
	$smarty_permission = new Smarty();
	$smarty_permission->template_dir = 'sysapp/permission/templates/';
	$smarty_permission->compile_dir = 'cache/templates_c/';
	$smarty_permission->left_delimiter = "{";
	$smarty_permission->right_delimiter = "}";
	
	$smarty_permission->assign('errorcode', $errorcode);
	//验证是否登入
	if(!checkLogin()){
	    $smarty_permission->assign('code', $errorcode['noLogin']);
	    $smarty_permission->display('error.tpl');
		exit;
	}
	//验证是否为管理员
	else if(!checkAdmin($db_permission)){
	    $smarty_permission->assign('code', $errorcode['noAdmin']);
	    $smarty_permission->display('error.tpl');
		exit;
	}
	//验证是否有权限
	else if(!checkPermissions($db_permission, 4)){
	    $smarty_permission->assign('code', $errorcode['noPermissions']);
	    $smarty_permission->display('error.tpl');
		exit;
	}
	
	trim(@extract($_POST));
	if (empty($ac)) {
	    $ac = '';
	}
	
	switch($ac){
		case 'ajaxGetList':
			$orderby = 'tbid desc limit '.$from.','.$to;
			if($search_1 != ''){
				$sqlwhere[] = 'name like "%'.$search_1.'%"';
			}
			$rs = $db_permission->select(0, 0, 'tb_permission', '*', $sqlwhere, $orderby);
			if($rs==NULL){
			    $c = $db_permission->select(0, 2, 'tb_permission', 'tbid', $sqlwhere);
				echo $c.'<{|*|}>';
			}else{
				if($reset){
				    $c = $db_permission->select(0, 2, 'tb_permission', 'tbid', $sqlwhere);
					echo $c.'<{|*|}>';
				}else{
					echo '-1<{|*|}>';
				}	
			}
			foreach($rs as $v){
				echo '<li>
                        <span class="level" style="width:20px">&nbsp;</span>
                        <span class="name">'.$v['name'].'</span>
                        <span class="do"><a href="detail.php?permissionid='.$v['tbid'].'">编辑</a> | <a href="javascript:;" class="list_del" permissionid="'.$v['tbid'].'">删除</a></span>
                    </li>';
			}
			break;
		case 'ajaxDel':
		    $db_permission->delete(0, 0, 'tb_permission', 'and tbid='.$permissionid);
			break;
		default:
		    $permissionscount = $db_permission->select(0, 2, 'tb_permission', 'tbid');
			$smarty_permission->assign('permissioncount', $permissionscount);
			$smarty_permission->display('index.tpl');
			break;
	}
?>