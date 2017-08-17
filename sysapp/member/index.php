<?php
	require_once('../../global.php');
	require_once 'sysapp/common/inc/error_code.php';
	require_once 'sysapp/common/inc/function.db.php';
	require_once 'third_party/php/Smarty-3.1.30/libs/Smarty.class.php';
	require_once 'dbase/db.class.php';
	require_once 'dbase/db.config.php';
	
	$db_member = new HRDB($db_hoorayos_config);
	
	$smarty_member = new Smarty();
	$smarty_member->template_dir = 'sysapp/member/templates/';
	$smarty_member->compile_dir = 'cache/templates_c/';
	$smarty_member->left_delimiter = "{";
	$smarty_member->right_delimiter = "}";
	
	$smarty_member->assign('errorcode', $errorcode);
	//验证是否登入
	if(!checkLogin()){
	    $smarty_member->assign('code', $errorcode['noLogin']);
	    $smarty_member->display('error.tpl');
		exit;
	}
	//验证是否为管理员
	else if(!checkAdmin($db_member)){
	    $smarty_member->assign('code', $errorcode['noAdmin']);
	    $smarty_member->display('error.tpl');
		exit;
	}
	//验证是否有权限
	else if(!checkPermissions($db_member, 3)){
	    $smarty_member->assign('code', $errorcode['noPermissions']);
	    $smarty_member->display('error.tpl');
		exit;
	}
	
	trim(@extract($_POST));
	if (empty($ac)) {
	    $ac = '';
	}
	
	switch($ac){
		case 'ajaxGetList':
		    $orderby = 'tbid desc limit '.$from.','.$to;
			if($search_1 != ''){
				$sqlwhere[] = 'username like "%'.$search_1.'%"';
			}
			if($search_2 != ''){
				$sqlwhere[] = 'type = '.$search_2;
			}
			$rs = $db_member->select(0, 0, 'tb_member', '*', $sqlwhere, $orderby);
			if($rs == NULL){
			    $c = $db_member->select(0, 2, 'tb_member', 'tbid', $sqlwhere);
				echo $c.'<{|*|}>';
			}else{
				if($reset){
				    $c = $db_member->select(0, 2, 'tb_member', 'tbid', $sqlwhere);
					echo $c.'<{|*|}>';
				}else{
					echo '-1<{|*|}>';
				}	
			}
			foreach($rs as $v){
				$type = $v['type'] == 1 ? '管理员' : '普通会员';
				echo '<li>
                        <span class="level" style="width:20px">&nbsp;</span>
                        <span class="name">'.$v['username'].'</span>
                        <span class="do">
                            <div class="input-prepend">
                                <a class="btn btn-mini btn-info" href="detail.php?memberid='.$v['tbid'].'">
                                    <i class="icon-edit"></i> 编辑
                                </a>
                                <a class="list_del btn btn-mini" href="javascript:;" memberid="'.$v['tbid'].'">
                                    <i class="icon-remove"></i> 删除
                                </a>
                            </div>
                        </span>
                        <span class="type">'.$type.'</span>
                    </li>';
			}
			break;
		case 'ajaxDel':
		    $db_member->delete(0, 0, 'tb_member', 'and tbid='.$memberid);
			break;
		default:
		    $membercount = $db_member->select(0, 2, 'tb_member', 'tbid');
		    $smarty_member->assign('membercount', $membercount);
		    $smarty_member->display('index.tpl');
		    break;
	}
?>