<?php
	require_once('../../global.php');
	require_once 'sysapp/common/inc/function.db.php';
	require_once 'sysapp/common/inc/app_type.php';
	require_once 'third_party/Smarty-3.1.30/libs/Smarty.class.php';
	require_once 'dbase/db.class.php';
	require_once 'dbase/db.config.php';
	
	$db_market = new HRDB($db_hoorayos_config);
	
	$smarty_market = new Smarty();
	$smarty_market->template_dir = 'sysapp/appmarket/templates/';
	$smarty_market->compile_dir = 'cache/templates_c/';
	$smarty_market->left_delimiter = "{";
	$smarty_market->right_delimiter = "}";
	
	trim(@extract($_POST));
	if (empty($ac)) {
	    $ac = '';
	}
	
	switch($ac){
		case 'ajaxGetList':
		    $mytype = $db_market->select(0, 1, 'tb_member', 'type', 'and tbid='.$_SESSION['member']['id']);
		    $myapplist = getMyAppListOnlyId($db_market);
			if($search_1 == -1){
				$sqlwhere[] = 'tbid in('.implode(',', $myapplist).')';
			}else if($search_1 != 0){
				if($search_1 == 1 && $mytype['type'] == 1){
					$sqlwhere[] = 'kindid = '.$search_1;
				}else{
					$sqlwhere[] = 'kindid = '.$search_1;
				}
			}else if($search_1 == 0 && $mytype['type'] == 0){
				$sqlwhere[] = 'kindid != 1';
			}
			if($search_3 != ''){
				$sqlwhere[] = 'name like "%'.$search_3.'%"';
			}
			switch($search_2){
				case '1':
					$orderby = 'dt desc';
					break;
				case '2':
					$orderby = 'usecount desc';
					break;
				case '3':	//未做
					$orderby = 'dt desc';
					break;
			}
			$orderby .= ' limit '.$from.','.$to;
			$rs = $db_market->select(0, 0, 'tb_app', '*', $sqlwhere, $orderby);
			if($rs == NULL){
			    $c = $db_market->select(0, 2, 'tb_app', 'tbid', $sqlwhere);
				echo $c.'<{|*|}>';
			}else{
				if($reset){
				    $c = $db_market->select(0, 2, 'tb_app', 'tbid', $sqlwhere);
					echo $c.'<{|*|}>';
				}else{
					echo '-1<{|*|}>';
				}
				foreach($rs as $v){
					echo '<li>
                            <a href="javascript:;"><img src="../../'.$v['icon'].'"></a>
                            <a href="javascript:;"><span class="app-name">'.$v['name'].'</span></a>
                            <span class="app-desc">'.$v['remark'].'</span>
                            <span class="star-box"><i style="width:'.($v['starnum']*20).'%;"></i></span>
                            <span class="star-num">'.floor($v['starnum']).'</span>
                            <span class="app-stat">'.$v['usecount'].' 人正在使用</span>
                            <a href="javascript:;" app_id="'.$v['tbid'].'" app_type="'.$v['type'].'" class="';
					if($myapplist != ''){
						if(in_array($v['tbid'], $myapplist)){
							if($search_1 == -1){
								echo 'btn-run-s" style="right:35px">打开应用</a>';
								echo '<a href="javascript:;" app_id="'.$v['tbid'].'" app_type="'.$v['type'].'" class="btn-remove-s" style="right:10px">删除应用</a>';
							}else{
								echo 'btn-run-s">打开应用</a>';
							}
						}else{
							echo 'btn-add-s">添加应用</a>';
						}
					}else{
						echo 'btn-add-s">添加应用</a>';
					}
					echo '</li>';
				}
			}
			break;
		default:
		    $mytype = $db_market->select(0, 1, 'tb_member', 'type', 'and tbid='.$_SESSION['member']['id']);
			$smarty_market->assign('membertype', $mytype['type']);
			$smarty_market->assign('apptype', $apptype);
			$appcount = $db_market->select(0, 2, 'tb_app', 'tbid');
			$smarty_market->assign('appcount', $appcount);
			$smarty_market->display('index.tpl');
			break;
	}
?>