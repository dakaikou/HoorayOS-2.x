<?php
	//文件地址处理
	function getFileInfo($str,$mode){
		if($str==""||is_null($str)) return "";
		switch($mode){
			case "path" : return dirname($str); break;
			case "name" : return basename($str,'.'.end(explode(".",$str))); break;
			case "ext" : return end(explode(".",$str)); break;
			case "simg" : return getFileInfo($str,"path")."/s_".getFileInfo($str,"name").".jpg"; break;
		}
	}

	//获取图片缩略图地址
	function getSimgSrc($string){
	    return preg_replace("#(\w*\..*)$#U", "s_\${1}", $string);
	}
	
?>