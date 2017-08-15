<?php
	//验证是否登入
	function checkLogin(){
	    
	    $login_state = false;
	    
	    if (isset($_SESSION)){
	        if (!empty($_SESSION['member'])){
    	        $login_state = true;
	        }
	    }
	        
		return $login_state;
	}

	//验证是否为管理员
	function checkAdmin($db){
	    $user = $db->select(0, 1, 'tb_member', 'type', 'and tbid='.$_SESSION['member']['id']);
	    return $user['type'] == 1 ? true : false;
	}
	
	//验证是否有权限
	function checkPermissions($db, $app_id){
		$isHavePermissions = false;
		$user = $db->select(0, 1, 'tb_member', 'permission_id', 'and tbid='.$_SESSION['member']['id']);
		if($user['permission_id'] != ''){
			$permission = $db->select(0, 1, 'tb_permission', 'apps_id', 'and tbid='.$user['permission_id']);
			if($permission['apps_id'] != ''){
				$apps = explode(',', $permission['apps_id']);
				if(in_array($app_id, $apps)){
					$isHavePermissions = true;
				}
			}
		}
		return $isHavePermissions;
	}
	
	//获取我的应用id数组
	function getMyAppListOnlyId($db){
	    $rs = $db->select(0, 1, 'tb_member', 'dock,desk1,desk2,desk3,desk4,desk5', 'and tbid='.$_SESSION['member']['id']);
	    if($rs['dock'] != ''){
	        $dock = explode(',', $rs['dock']);
	        foreach($dock as $v){
	            $tmp = explode('_', $v);
	            if($tmp[0] == 'app' || $tmp[0] == 'widget'){
	                $appid[] = $tmp[1];
	            }
	        }
	    }
	    for($i=1; $i<=5; $i++){
	        if($rs['desk'.$i] != ''){
	            $deskappid = explode(',', $rs['desk'.$i]);
	            foreach($deskappid as $v){
	                $tmp = explode('_', $v);
	                if($tmp[0] == 'app' || $tmp[0] == 'widget'){
	                    $appid[] = $tmp[1];
	                }
	            }
	        }
	    }
	    $rs = $db->select(0, 0, 'tb_folder', 'content', 'and content!="" and member_id='.$_SESSION['member']['id']);
	    if($rs != NULL){
	        foreach($rs as $v){
	            $rss = explode(',', $v['content']);
	            foreach($rss as $vv){
	                $tmp = explode('_', $vv);
	                if($tmp[0] == 'app' || $tmp[0] == 'widget'){
	                    $appid[] = $tmp[1];
	                }
	            }
	        }
	    }
	    if($appid != NULL){
	        return $appid;
	    }else{
	        return NULL;
	    }
	}
	//获取我的应用id数组
	function getMyAppList($db){
	    $rs = $db->select(0, 1, 'tb_member', 'dock,desk1,desk2,desk3,desk4,desk5', 'and tbid='.$_SESSION['member']['id']);
	    if($rs['dock'] != ''){
	        $dock = explode(',', $rs['dock']);
	        foreach($dock as $v){
	            $appid[] = $v;
	        }
	    }
	    for($i=1; $i<=5; $i++){
	        if($rs['desk'.$i] != ''){
	            $deskappid = explode(',', $rs['desk'.$i]);
	            foreach($deskappid as $v){
	                $appid[] = $v;
	            }
	        }
	    }
	    $rs = $db->select(0, 0, 'tb_folder', 'content', 'and content!="" and member_id='.$_SESSION['member']['id']);
	    if($rs != NULL){
	        foreach($rs as $v){
	            $rss = explode(',', $v['content']);
	            foreach($rss as $vv){
	                $appid[] = $vv;
	            }
	        }
	    }
	    if($appid != NULL){
	        return $appid;
	    }else{
	        return NULL;
	    }
	}
?>