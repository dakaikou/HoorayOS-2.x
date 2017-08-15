<?php
    require_once ('config_db.inc.php');
    //数据库连接配置信息
	$db_hoorayos_config = array(
		'dsn'=>DB_TYPE .':host=' .DB_HOST .';dbname=' .DB_NAME,
		'name'=>DB_USER,
		'password'=>DB_PASS
	);
	
//	echo $db_hoorayos_config['dsn'] .'<br/>';
//	echo $db_hoorayos_config['name'] .'<br/>';
//	echo $db_hoorayos_config['password'] .'<br/>';
?>