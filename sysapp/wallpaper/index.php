<?php
	require_once('../../global.php');
    require_once 'sysapp/common/inc/function.file.php';
	require_once 'third_party/Smarty-3.1.30/libs/Smarty.class.php';
	require_once 'dbase/db.class.php';
	require_once 'dbase/db.config.php';
	
	$db_wallpaper = new HRDB($db_hoorayos_config);
	
	$smarty_wallpaper = new Smarty();
	$smarty_wallpaper->template_dir = 'sysapp/wallpaper/templates/';
	$smarty_wallpaper->compile_dir = 'cache/templates_c/';
	$smarty_wallpaper->left_delimiter = "{";
	$smarty_wallpaper->right_delimiter = "}";
	
	trim(@extract($_POST));
	if (empty($ac)) {
	    $ac = '';
	}
	
	switch($ac){
		default:
			$rs = $db_wallpaper->select(0, 0, 'tb_wallpaper', '*', '', 'tbid asc');
			foreach($rs as &$v){
				$v['s_url'] = getFileInfo($v['url'], 'simg');
			}
			$smarty_wallpaper->assign('wallpaperList', $rs);
			$rs = $db_wallpaper->select(0, 1, 'tb_member', 'wallpapertype', 'and tbid='.$_SESSION['member']['id']);
			$smarty_wallpaper->assign('wallpaperType', $rs['wallpapertype']);
			$smarty_wallpaper->display('index.tpl');
			break;
	}
?>