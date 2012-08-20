<b:window
	id="window_cmddetail_{$page_data.other}"
	style="left:10px;top:10px;width:650px;height:450px;"
	xmlns="http://www.w3.org/1999/xhtml"
	xmlns:b="http://www.backbase.com/b"
	xmlns:s="http://www.backbase.com/s">

	<b:windowhead b:icon="none">##WINDOW_COMMAND_OUTPUT_TITLE## {$page_data.other}</b:windowhead>
	<b:windowbody>
		<s:tasklist b:name="cancel_cmd_{$page_data.other}">
			<s:sequential>
				<s:task b:action="submit" b:target="id('cancelform_{$page_data.other}')" />
			</s:sequential>
		</s:tasklist>
		<s:tasklist b:name="start_cmd_{$page_data.other}">
			<s:sequential>
				<s:task b:action="submit" b:target="id('startform_{$page_data.other}')" />
			</s:sequential>
		</s:tasklist>
		<b:panelset b:rows="29px * 29px">
			<b:panel id="panel_top_{$page_data.other}" style="background-color: #fff; padding: 0px; overflow:hidden;">
				<strong>##WINDOW_COMMAND_OUTPUT_OVERVIEW##</strong>
			</b:panel>
			<b:panel id="panel_middle_{$page_data.other}" style="background-color: #fff; padding: 0px;">
				 <b:panelset b:cols="120px *">
				 	<b:panel id="panel_left_{$page_data.other}" style="background-color: #fff; padding: 0px;">
				 		<b:box style="width: 100%;height: 98%;overflow:hidden;">
							<div id="command_run_{$page_data.other}">##WINDOW_COMMAND_OUTPUT_LOADING##</div>
							<s:execute>
								<s:task b:action="load" b:url="data.php?process=cmd_output_list&amp;id={$page_data.other}" b:destination="id('command_run_{$page_data.other}')" b:mode="replacechildren" />
							</s:execute>
							<form action="data.php" method="POST" id="cancelform_{$page_data.other}" name="cancelform_{$page_data.other}" b:destination="." b:mode="replace">
								<input type="hidden" name="process" value="cancelcmd_{$page_data.other}" />
								<input type="hidden" name="id" value="{$page_data.other}" />
								<br /><div align="center"><b:button b:onclick="cancel_cmd_{$page_data.other}">##WINDOW_COMMAND_CANCEL##</b:button></div>
							</form>
						</b:box>
				 	</b:panel>
				 	<b:panel id="panel_right_{$page_data.other}" style="background-color: #fff; padding-left: 5px;padding-right: 5px;padding-top: 0px;">
						<div id="command_output_{$page_data.other}">##WINDOW_COMMAND_OUTPUT_LOADING##</div>
						<s:execute>
							<s:task b:action="load" b:url="data.php?process=cmd_output_detail&amp;id={$page_data.other}" b:destination="id('command_output_{$page_data.other}')" b:mode="replacechildren" />
						</s:execute>
				 	</b:panel>
				 </b:panelset>
		 	</b:panel>
		 	<b:panel id="panel_bottom_{$page_data.other}" style="background-color: #fff; padding: 5px; text-align: right;overflow:hidden;">
				<div align="right"><b:button b:action="trigger" b:event="close" b:target="id('window_cmddetail_{$page_data.other}')">##WINDOW_COMMAND_CLOSE##</b:button></div>
			</b:panel>
		</b:panelset>
	</b:windowbody>
	<s:event b:on="close">
		<s:task b:action="remove" b:target="id('window_cmddetail_{$page_data.other}')" />
	</s:event>
</b:window>