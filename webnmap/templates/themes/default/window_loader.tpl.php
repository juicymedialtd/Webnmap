{php}echo '<?xml version="1.0" ?>';{/php}
<b:window
	id="window_loader_{$page_data.other}"
	style="left:10px;top:10px;width:500px;height:290px;"
	xmlns="http://www.w3.org/1999/xhtml"
	xmlns:b="http://www.backbase.com/b"
	xmlns:s="http://www.backbase.com/s">

	<b:windowhead b:icon="none">Data Window {$page_data.other}</b:windowhead>
	<b:windowbody b:innerstyle="padding:0;overflow:hidden;">
		<iframe style="border-style:none;margin:0;padding:0;width:100%;height:100%" src="http://www.webnmap.co.uk/admin/commandsoutput.cgi?id={$page_data.other}"></iframe>
	</b:windowbody>
	<s:event b:on="close">
		<s:task b:action="remove"/>
	</s:event>
</b:window>