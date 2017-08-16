<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>皮肤设置</title>
<link rel="stylesheet" href="../../third_party/bootstrap/bootstrap.custom.by.hooray.css">
<link rel="stylesheet" href="css/global.css">
<link rel="stylesheet" href="css/default.css">
</head>

<body>
	<div class="title">
		<ul>
			<li><a href="../wallpaper/index.php">壁纸设置</a></li>
			<li class="focus">皮肤设置</li>
		</ul>
	</div>
	<ul class="skin">
		<li skin="default"><img src="../../img/ui/skin_default.png"></li>
		<li skin="qq"><img src="../../img/ui/skin_qq.png"></li>
		<li skin="mac"><img src="../../img/ui/skin_mac.png"></li>
		<li skin="chrome"><img src="../../img/ui/skin_chrome.png"></li>
		<li skin="ext"><img src="../../img/ui/skin_ext.png"></li>
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
		$('.skin li').on('click',function(){
			var skin = $(this).attr('skin');
			$.ajax({
				type : 'POST',
				url : 'index.php',
				data : 'ac=update&skin=' + skin,
				success : function(){
					window.parent.ZENG.msgbox.show("设置成功，如果没有更新请刷新页面", 4, 2000);
					window.parent.HROS.base.getSkin();
				}
			});
		});
	});
	</script>
	{/literal}
</body>
</html>