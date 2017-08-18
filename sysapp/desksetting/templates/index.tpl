<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>桌面设置</title>
<link rel="stylesheet" href="../../third_party/bootstrap/bootstrap.custom.by.hooray.css">
<link rel="stylesheet" href="css/global.css">
<link rel="stylesheet" href="css/default.css">
</head>

<body>
	<div class="title">应用码头位置</div>
	<div class="dock_setting">
		<table>
			<tr>
				<td colspan="3">
					<div class="set_top"><label class="radio"><input type="radio" name="dockpos" value="top" {if $dock == 'top'}checked{/if}>顶部</label></div>
				</td>
			</tr>
			<tr>
				<td width="75">
					<div class="set_left"><label class="radio"><input type="radio" name="dockpos" value="left" {if $dock == 'left'}checked{/if}>左部</label></div>
				</td>
				<td class="set_view set_view_{$dock}"></td>
				<td width="75">
					<div class="set_right"><label class="radio"><input type="radio" name="dockpos" value="right" {if $dock == 'right'}checked{/if}>右部</label></div>
				</td>
			</tr>
		</table>
	</div>
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
	$('input[name="dockpos"]').change(function(){
		var pos = $('input[name="dockpos"]:checked').val();
		$('.set_view').removeClass('set_view_top').removeClass('set_view_left').removeClass('set_view_right');
		$('.set_view').addClass('set_view_'+pos);
		window.parent.HROS.dock.updatePos(pos,function(){
			//更新码头位置
			window.parent.HROS.dock.setPos();
			//更新图标位置
			window.parent.HROS.deskTop.appresize();
			//更新滚动条
			window.parent.HROS.app.getScrollbar();
		});
	});
});
</script>
{/literal}
</body>
</html>