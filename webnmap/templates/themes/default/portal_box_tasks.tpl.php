<div
	xmlns="http://www.w3.org/1999/xhtml"
	xmlns:b="http://www.backbase.com/b"
	xmlns:s="http://www.backbase.com/s">
	{literal}
		<script language="JavaScript">
		//<![CDATA[
			var intervalId; // global interval handle/reference
			var interval = 60 * 1000;// every 60 sec
			// method that reloads page
			function reloadPage()
			{
				var cmd = '<s:task b:action="load" b:destination="id(\'tasks_data\')" ';
				    cmd += 'b:mode="replacechildren" ';
				    cmd += 'b:url="data.php?process=tasklist&amp;id={/literal}{$user_info.id}{literal}"/>';
				bpc.execute(cmd);
				var cmdd = '<s:task b:action="load" b:destination="id(\'box-server-status\')" ';
				    cmdd += 'b:mode="replacechildren" ';
				    cmdd += 'b:url="main.php?process=portal&amp;id=portal_box_server_status"/>';
				bpc.execute(cmdd);
			}
			// start reloading in intervals
			function startReload()
			{
				intervalId = setInterval("reloadPage()", interval);
			}

			// stop interval reloading
			function stopReload()
			{
				clearInterval(intervalId);
			}
		//]]>
		</script>
	{/literal}

	<s:behavior b:name="fx-color">
		<s:event b:on="mouseenter">
			<s:fxstyle b:background-color="#b5b5b5" b:color="#ffffff" b:time="250" />
		</s:event>
		<s:event b:on="mouseleave">
			<s:fxstyle b:background-color="#ffffff" b:color="#000000" b:time="1000" />
		</s:event>
	</s:behavior>
	<s:behavior b:name="fx-main-color">
		<s:event b:on="mouseenter">
			<s:fxstyle b:background-color="#E5FFBF" b:color="#ffffff" b:time="250" />
		</s:event>
		<s:event b:on="mouseleave">
			<s:fxstyle b:background-color="#ffffff" b:color="#000000" b:time="500" />
		</s:event>
	</s:behavior>
	<s:execute>
		<s:task b:action="js" b:value="startReload()" />
	</s:execute>

	<p style="padding-left:5px;overflow:visible;">
		<img align="right" src="themes/{$domain_ext_info._site_templatedir}/icons/header_icon_db.gif" alt="##PORTAL_BOX_TASKS_DESC##" title="##PORTAL_BOX_TASKS_DESC##" border="0" />
		##PORTAL_BOX_TASKS_DESC##
		<br /><br />##PORTAL_BOX_WELCOME_COUNT1## {$command_statuses.total} ##PORTAL_BOX_WELCOME_COUNT2##
	</p>

	<div style="padding-top:10px;" id="tasks_data">##PORTAL_BOX_TASKS_LOADING##</div>

    <s:execute>
      	<s:task b:action="load" b:url="data.php?process=tasklist&amp;id={$user_info.id}" b:destination="id('tasks_data')" b:mode="replacechildren" />
    </s:execute>

    <p style="padding-left:5px;clear:left;">
		<a>
			<s:event b:on="command">
				<s:task b:action="load" b:url="main.php?process=window&amp;id=window_pane_schedule" b:test="not(id('window_schedule'))" b:destination="id('windowarea')" b:mode="aslastchild" />
				<s:task b:action="trigger" b:event="open" b:target="id('window_schedule')" />
				<s:task b:action="focus" b:event="open" b:target="id('window_schedule')" />
			</s:event>
			##PORTAL_BOX_TASKS_LINK##
		</a>
	</p>
</div>