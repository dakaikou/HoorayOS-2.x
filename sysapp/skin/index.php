<?php
	require_once '../../global.php';
	require_once 'third_party/php/Smarty-3.1.30/libs/Smarty.class.php';
	require_once 'dbase/db.class.php';
	require_once 'dbase/db.config.php';
	
    $db_skin = new HRDB($db_hoorayos_config);

    trim(@extract($_POST));
    if (empty($ac)) {
        $ac = '';
    }
    
	switch($ac){
		case 'update':
			$db_skin->update(0, 0, 'tb_member', "skin = '$skin'", 'and tbid = '.$_SESSION['member']['id']);
			break;
		default:
		    $smarty_skin = new Smarty();
		    $smarty_skin->template_dir = 'sysapp/skin/templates/';
		    $smarty_skin->compile_dir = 'cache/templates_c/';
		    $smarty_skin->left_delimiter = "{";
		    $smarty_skin->right_delimiter = "}";
		    
		    $smarty_skin->display('index.tpl');
		    break;
	}
?>