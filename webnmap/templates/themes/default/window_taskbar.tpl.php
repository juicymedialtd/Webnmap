<b:taskbar id="taskbar" b:windowarea="id('windowarea')">
	<b:startbutton>
		<s:event b:on="command">
			<s:task b:action="select" b:target="id('menu')" />
			<s:task b:action="position" b:target="id('menu')" b:type="place" b:mode="before-start" />
		</s:event>
	</b:startbutton>
	<td class="b-taskbar-notification" id="clock">
		<s:event b:on="construct" b:action="js" b:value="runClock();" />
		00:00
	</td>
</b:taskbar>