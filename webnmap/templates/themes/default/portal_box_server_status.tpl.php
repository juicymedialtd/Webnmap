<div class="mainmenu" xmlns="http://www.w3.org/1999/xhtml"
	xmlns:b="http://www.backbase.com/b"
	xmlns:s="http://www.backbase.com/s">
	<p style="padding:5px;">##PORTAL_BOX_SERVER_STATUS_TEXT1##	</p>
	<div align="center" style="padding-bottom:10px;">
		{if $page_data.load <= 0.2}
			<img src="themes/default/icons/header_icon_start.gif" alt="##PORTAL_BOX_SERVER_STATUS_VALUE1##" title="##PORTAL_BOX_SERVER_STATUS_VALUE1##" /><br /><strong>##PORTAL_BOX_SERVER_STATUS_VALUE1## @ </strong>
		{else}
			<img src="themes/default/icons/header_icon_stop.gif" alt="##PORTAL_BOX_SERVER_STATUS_VALUE2##" title="##PORTAL_BOX_SERVER_STATUS_VALUE2##" /><br /><strong>##PORTAL_BOX_SERVER_STATUS_VALUE2## @ </strong>
		{/if}
		{$page_data.load}
	</div>
</div>