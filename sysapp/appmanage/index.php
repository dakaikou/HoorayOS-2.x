<?php
	require_once '../../global.php';
	require_once 'sysapp/common/inc/error_code.php';
	require_once 'sysapp/common/inc/app_type.php';
	require_once 'sysapp/common/inc/function.db.php';
	require_once 'third_party/php/Smarty-3.1.30/libs/Smarty.class.php';
	require_once 'dbase/db.class.php';
	require_once 'dbase/db.config.php';
	
	$db_appmanage = new HRDB($db_hoorayos_config);
	
	$smarty_appmanage = new Smarty();
	$smarty_appmanage->template_dir = 'sysapp/appmanage/templates/';
	$smarty_appmanage->compile_dir = 'cache/templates_c/';
	$smarty_appmanage->left_delimiter = "{";
	$smarty_appmanage->right_delimiter = "}";
	
	$smarty_appmanage->assign('errorcode', $errorcode);
	//验证是否登入
	if(!checkLogin()){
	    $smarty_appmanage->assign('code', $errorcode['noLogin']);
	    $smarty_appmanage->display('error.tpl');
		exit;
	}
	//验证是否为管理员
	else if(!checkAdmin($db_appmanage)){
	    $smarty_appmanage->assign('code', $errorcode['noAdmin']);
	    $smarty_appmanage->display('error.tpl');
		exit;
	}
	//验证是否有权限
	else if(!checkPermissions($db_appmanage, 1)){
	    $smarty_appmanage->assign('code', $errorcode['noPermissions']);
	    $smarty_appmanage->display('error.tpl');
		exit;
	}
	
	trim(@extract($_POST));
	if (empty($ac)) {
	    $ac = '';
	}
	
    switch($ac){
	case 'ajaxGetList':
		$orderby = 'dt desc limit '.$from.','.$to;
		if($search_1 != ''){
			$sqlwhere[] = 'name like "%'.$search_1.'%"';
		}
		if($search_2 != ''){
			$sqlwhere[] = 'kindid = '.$search_2;
		}
		$rs = $db_appmanage->select(0, 0, 'tb_app', '*', $sqlwhere, $orderby);
		if($rs == NULL){
			$c = $db_appmanage->select(0, 2, 'tb_app', 'tbid', $sqlwhere);
			echo $c.'<{|*|}>';
		}else{
			if($reset){
				$c = $db_appmanage->select(0, 2, 'tb_app', 'tbid', $sqlwhere);
				echo $c.'<{|*|}>';
			}else{
				echo '-1<{|*|}>';
			}	
		}
		foreach($rs as $v){
			echo '<li>
                    <span class="level" style="width:20px">&nbsp;</span>
                    <span class="name">'.$v['name'].'</span>
                    <span class="do"><a href="detail.php?appid='.$v['tbid'].'">编辑</a> | <a href="javascript:;" class="list_del" appid="'.$v['tbid'].'">删除</a></span><span class="count">'.$v['usecount'].'</span>
                    <span class="type">'.$apptype[$v['kindid']-1]['name'].'</span><span class="type">'.$v['type'].'</span>
                  </li>';
		}
		break;
	case 'ajaxDel':
	    $db_appmanage->delete(0,0,'tb_app','and tbid='.$appid);
		break;
	default:
	    $appcount = $db_appmanage->select(0, 2, 'tb_app', 'tbid');
	    $smarty_appmanage->assign('appcount', $appcount);
	    $smarty_appmanage->assign('apptype', $apptype);
	    $smarty_appmanage->display('index.tpl');
	    break;
    }
?>