<?php
	require_once('../../global.php');
	require_once('third_party/php/Smarty-3.1.30/libs/Smarty.class.php');
	require_once 'dbase/db.class.php';
	require_once 'dbase/db.config.php';
	
	$db_alert = new HRDB($db_hoorayos_config);

	$smarty_alert = new Smarty();
	$smarty_alert->template_dir = 'sysapp/permission/templates/';
	$smarty_alert->compile_dir = 'cache/templates_c/';
	$smarty_alert->left_delimiter = "{";
	$smarty_alert->right_delimiter = "}";
	
	$apps = $db_alert->select(0, 0, 'tb_app', 'tbid,name,icon', 'and kindid = 1');
	$smarty_alert->assign('apps', $apps);
	$smarty_alert->display('alert_addapps.tpl');
?>