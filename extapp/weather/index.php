<?php
	require_once('../../global.php');
	require_once 'third_party/php/Smarty-3.1.30/libs/Smarty.class.php';
	
	$smarty_weather = new Smarty();
	$smarty_weather->template_dir = 'extapp/weather/templates/';
	$smarty_weather->compile_dir = 'cache/templates_c/';
	$smarty_weather->left_delimiter = "{";
	$smarty_weather->right_delimiter = "}";
	
	switch($ac){
		default:
		    $smarty_weather->display('index.tpl');
	}
?>