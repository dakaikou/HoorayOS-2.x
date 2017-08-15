<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>啊，出错了</title>
<link rel="stylesheet" href="../../img/ui/globle.css">
<link rel="stylesheet" href="../../img/ui/bootstrap/bootstrap.custom.by.hooray.css">
<link rel="stylesheet" href="../../js/HoorayLibs/hooraylibs.css">
<link rel="stylesheet" href="../../img/ui/sys.css">
</head>

<body>
	<script src="../../third_party/js/jquery/jquery-1.8.1.min.js"></script>
	<script src="../../third_party/js/artDialog4.1.6/jquery.artDialog.js?skin=default"></script>
	<script src="../../third_party/js/artDialog4.1.6/plugins/iframeTools.js"></script>
	<script src="../../core/js/HoorayLibs/hooraylibs.js"></script>
	<script>
	$(function(){
		$('.tip').colorTip();
	});
	</script>

{if $code == $errorcode.noLogin}
<script type="text/javascript">
$(function(){
	window.parent.ZENG.msgbox.show("对不起，您还没有登入！", 1, 2000);
});
</script>
{elseif $code == $errorcode.noAdmin}
<script type="text/javascript">
$(function(){
	window.parent.ZENG.msgbox.show("对不起，您不是管理员！", 1, 2000);
});
</script>
{elseif $code == $errorcode.noPermissions}
<script type="text/javascript">
$(function(){
	window.parent.ZENG.msgbox.show("对不起，您没有权限操作！", 1, 2000);
});
</script>
{/if}
</body>
</html>
