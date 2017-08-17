<?php
	require_once('../../global.php');
	require_once 'third_party/php/Smarty-3.1.30/libs/Smarty.class.php';
	require_once 'dbase/db.class.php';
	require_once 'dbase/db.config.php';
	
	$db_desksetting = new HRDB($db_hoorayos_config);
	
	$smarty_desksetting = new Smarty();
	$smarty_desksetting->template_dir = 'sysapp/desksetting/templates/';
	$smarty_desksetting->compile_dir = 'cache/templates_c/';
	$smarty_desksetting->left_delimiter = "{";
	$smarty_desksetting->right_delimiter = "}";
	
	trim(@extract($_POST));
	if (empty($ac)) {
	    $ac = '';
	}
	
	switch($ac){
		default:
			$dockpos = $db_desksetting->select(0, 1, 'tb_member', 'dockpos', 'and tbid='.$_SESSION['member']['id']);
			$smarty_desksetting->assign('dock', $dockpos['dockpos']);
			$smarty_desksetting->display('index.tpl');
			break;
	}
?>