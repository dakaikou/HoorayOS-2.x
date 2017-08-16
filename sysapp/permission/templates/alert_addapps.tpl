<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>添加应用</title>
<link rel="stylesheet" href="../../third_party/bootstrap/bootstrap.custom.by.hooray.css">
<link rel="stylesheet" href="css/global.css">
<link rel="stylesheet" href="css/default.css">
</head>

<body>
<div class="alert_addapps">
	{foreach from=$apps item=a}
	<div class="app" title="{$a.name}" appid="{$a.tbid}">
		<img src="../../{$a.icon}" alt="{$a.name}">
		<div class="name">{$a.name}</div>
		<span class="selected"></span>
	</div>
	{/foreach}
</div>
<input type="hidden" id="value_1">

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
	if(art.dialog.data('appsid') != ''){
		$('#value_1').val(art.dialog.data('appsid'));
		var appsid = art.dialog.data('appsid').split(',');
		$('.app').each(function(){
			for(var i=0; i<appsid.length; i++){
				if(appsid[i] == $(this).attr('appid')){
					$(this).addClass('act');
					break;
				}
			}
		});
	}
	$('.app').click(function(){
		if($(this).hasClass('act')){
			var appsid = $('#value_1').val().split(',');
			var newappsid = [];
			for(var i=0, j=0; i<appsid.length; i++){
				if(appsid[i] != $(this).attr('appid')){
					newappsid[j] = appsid[i];
					j++;
				}
			}
			$('#value_1').val(newappsid.join(','));
			$(this).removeClass('act');
		}else{
			if($('#value_1').val() != ''){
				var appsid = $('#value_1').val().split(',');
				appsid[appsid.length] = $(this).attr('appid');
				$('#value_1').val(appsid.join(','));
			}else{
				$('#value_1').val($(this).attr('appid'));
			}
			$(this).addClass('act');
		}
		$.dialog.data('appsid', $('#value_1').val());
	});
});
</script>
{/literal}
</body>
</html>
