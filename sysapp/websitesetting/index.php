<?php
	require_once('../../global.php');
	require_once 'sysapp/common/inc/error_code.php';
	require_once 'sysapp/common/inc/function.db.php';
	require_once 'third_party/php/Smarty-3.1.30/libs/Smarty.class.php';
	require_once 'dbase/db.class.php';
	require_once 'dbase/db.config.php';
	
	$db_websetting = new HRDB($db_hoorayos_config);
	
	$smarty_websetting = new Smarty();
	$smarty_websetting->template_dir = 'sysapp/websitesetting/templates/';
	$smarty_websetting->compile_dir = 'cache/templates_c/';
	$smarty_websetting->left_delimiter = "{";
	$smarty_websetting->right_delimiter = "}";
	
	$smarty_websetting->assign('errorcode', $errorcode);
	
	//验证是否登入
	if(!checkLogin()){
	    $smarty_websetting->assign('code', $errorcode['noLogin']);
	    $smarty_websetting->display('error.tpl');
		exit;
	}
	//验证是否为管理员
	else if(!checkAdmin($db_websetting)){
	    $smarty_websetting->assign('code', $errorcode['noAdmin']);
	    $smarty_websetting->display('error.tpl');
		exit;
	}
	//验证是否有权限
	else if(!checkPermissions($db_websetting, 2)){
	    $smarty_websetting->assign('code', $errorcode['noPermissions']);
	    $smarty_websetting->display('error.tpl');
		exit;
	}
    	
	trim(@extract($_POST));
	if (empty($ac)) {
	    $ac = '';
	}
	
	switch($ac){
		case 'ajaxEdit':
		    $set = array(
				"title = '$val_title'",
				"keywords = '$val_keywords'",
				"description = '$val_description'"
			);
			$db_websetting->update(0, 0, 'tb_setting', $set);
			break;
		case 'getDonateList':
			//获取url地址
			$url = 'http://files.cnblogs.com/hooray/donate.xml';
			//取出远程url的xml文件
			$html = file_get_contents($url);
			if ($html == ""){
				echo 0;
			}else{
			    echo 'to-be-defined';
				//将文件装到一个数组当中
//    				$arr = simplexml_load_string($html);
    				//将属性循环出来
//    				foreach($arr as $value){
//    					echo '<div class="input-label"><label class="label-text">'.$value['name'].'：</label><span class="txt">'.$value['money'].' 元</span></div>';
//    				}
			}
			break;
		case 'checkVersion':
			//获取url地址
			$url = 'http://files.cnblogs.com/hooray/version.xml';
			//取出远程url的xml文件
			$html = file_get_contents($url);
			if($html == ""){
				echo 0;
			}else{
				//将文件装到一个数组当中
				$arr = simplexml_load_string($html);
				foreach($arr as $value){
					if($value['version'] == $version){
						echo 1;
					}else{
						echo $value['download'];
					}
					break;
				}
			}
			break;
		default:
		    $rs = $db_websetting->select(0, 1, 'tb_setting');
		    $smarty_websetting->assign('setinfo', $rs);
		    $smarty_websetting->display('index.tpl');
		    break;
	}
?>