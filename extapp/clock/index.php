<?php
	require_once('../../global.php');
	require_once 'third_party/Smarty-3.1.30/libs/Smarty.class.php';
	
	$smarty_clock = new Smarty();
	$smarty_clock->template_dir = 'extapp/clock/templates/';
	$smarty_clock->compile_dir = 'cache/templates_c/';
	$smarty_clock->left_delimiter = "{";
	$smarty_clock->right_delimiter = "}";
	
	switch($ac){
		default:
		    $smarty_clock->display('index.tpl');
	}
?>