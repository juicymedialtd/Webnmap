<b:window
	id="window_statistics"
	style="left:50px;top:50px;width:540px;height:580px;"
	xmlns="http://www.w3.org/1999/xhtml"
	xmlns:b="http://www.backbase.com/b"
	xmlns:s="http://www.backbase.com/s">

	<b:windowhead b:icon="none">System and User Statistics</b:windowhead>
	<b:windowbody>

		<h2>Statistics Overview</h2>
		<b:detailviewer style="width: 400px;">
			<b:property b:label="##PORTAL_BOX_USERDETAIL_USER##">{$user_info.username}</b:property>
			<b:property b:label="##PORTAL_BOX_USERDETAIL_NAME##">{$user_info.name}</b:property>
			<b:property b:label="##PORTAL_BOX_USERDETAIL_EMAIL##">{$user_info.email}</b:property>
			<b:property b:label="##PORTAL_BOX_USERDETAIL_REG##">{$user_info.registerDate}</b:property>
			<b:property b:label="##PORTAL_BOX_USERDETAIL_LAST##">{$user_info.lastvisitDate}</b:property>
			<b:property b:label="##PORTAL_BOX_USERDETAIL_CREDITS##">{$user_info.credits}</b:property>
		</b:detailviewer>

		<br /><br />
		<h2>Execution Statistics</h2>
		<b:box>
			<b:barchart b:caption="Commands running for ##PORTAL_BOX_USERDETAIL_NAME## on this system." b:x-axis-label="Status" b:y-axis-label="Number of Executions" b:name="barchart" b:width="400" b:height="400" b:mode="normal">
			   <b:barchart-horizontal-values b:alignment="vertical">
			      <b:barchart-value b:value="##PORTAL_BOX_STATUSES_PENDING##" />
			      <b:barchart-value b:value="##PORTAL_BOX_STATUSES_ACTIVE##" />
			      <b:barchart-value b:value="##PORTAL_BOX_STATUSES_COMPLETED##" />
			      <b:barchart-value b:value="##PORTAL_BOX_STATUSES_RECURRING##" />
			      <b:barchart-value b:value="##PORTAL_BOX_STATUSES_CANCELLED##" />
			   </b:barchart-horizontal-values>
			   <b:barchart-series b:name="Commands by {$user_info.username}" b:color="#7C90CD">
			      <b:barchart-bar b:value="{$command_statuses.pending}" />
			      <b:barchart-bar b:value="{$command_statuses.active}" />
			      <b:barchart-bar b:value="{$command_statuses.completed}" />
			      <b:barchart-bar b:value="{$command_statuses.recurring}" />
			      <b:barchart-bar b:value="{$command_statuses.cancelled}" />
			   </b:barchart-series>
			</b:barchart>
		</b:box>

	</b:windowbody>
	<s:event b:on="close">
		<s:task b:action="remove"/>
	</s:event>
</b:window>