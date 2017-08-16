<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>壁纸设置</title>
<link rel="stylesheet" href="../../third_party/bootstrap/bootstrap.custom.by.hooray.css">
<link rel="stylesheet" href="css/global.css">
<link rel="stylesheet" href="css/default.css">
</head>

<body>
	<div class="title">
		<ul>
			<li class="focus">壁纸设置</li>
			<li><a href="../skin/index.php">皮肤设置</a></li>
		</ul>
	</div>
	<div class="wallpapertype form-inline">
		<div class="input-prepend fl">
			<a class="btn disabled">系统壁纸</a><a class="btn" href="custom.php">自定义</a>
		</div>
		<div class="input-prepend fr">
			<span class="add-on">显示方式</span><select name="wallpapertype" id="wallpapertype">
				<option value="tianchong"{if $wallpaperType=='tianchong'} selected{/if}>填充</option>
				<option value="shiying"{if $wallpaperType=='shiying'} selected{/if}>适应</option>
				<option value="pingpu"{if $wallpaperType=='pingpu'} selected{/if}>平铺</option>
				<option value="lashen"{if $wallpaperType=='lashen'} selected{/if}>拉伸</option>
				<option value="juzhong"{if $wallpaperType=='juzhong'} selected{/if}>居中</option>
			</select>
		</div>
	</div>
	<ul class="wallpaper">
		{foreach from=$wallpaperList key=k item=wp}
		<li{if $k%3==2} class="three"{/if} wpid="{$wp.tbid}">
			<img src="../../{$wp.s_url}">
			<div>{$wp.title}</div>
		</li>
		{/foreach}
	</ul>
	<script src="../../third_party/jquery/jquery-1.8.1.min.js"></script>
	<script src="../../third_party/artDialog4.1.6/jquery.artDialog.js?skin=default"></script>
	<script src="../../third_party/artDialog4.1.6/plugins/iframeTools.js"></script>
	<script src="../../core/js/HoorayLibs/hooraylibs.js"></script>
	<script>
	$(function(){
		$('.tip').colorTip();
	});
	</script>
{literal}
<script>
$(function(){
	$("#wallpapertype").on('change',function(){
		window.parent.HROS.wallpaper.update(1, $('#wallpapertype').val(),'');
	});
	$('.wallpaper li').on('click',function(){
		window.parent.HROS.wallpaper.update(1, $('#wallpapertype').val(),$(this).attr('wpid'));
	});
});
</script>
{/literal}
</body>
</html>