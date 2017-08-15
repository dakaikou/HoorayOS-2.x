<?php
    require_once('../global.php');
    require_once 'dbase/db.class.php';
    require_once 'dbase/db.config.php';
    require_once('third_party/php/Smarty-3.1.30/libs/Smarty.class.php');
	
	$smarty_core = new Smarty();
	$smarty_core->template_dir = 'core/templates/';
	$smarty_core->compile_dir = 'cache/templates_c/';
	$smarty_core->left_delimiter = "{";
	$smarty_core->right_delimiter = "}";

	$db_core = new HRDB($db_hoorayos_config);
	
	$setting = $db_core->select(0, 1, 'tb_setting');
	$smarty_core->assign('setting', $setting);
	$row = $db_core->select(0, 1, 'tb_member', 'skin', 'and tbid = '.$_SESSION['member']['id']);
	$smarty_core->assign('skin', $row['skin']);
	$smarty_core->display('index.tpl');
?>