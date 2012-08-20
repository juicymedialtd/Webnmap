<b:contextmenu id="menu">
	<b:contextmenurow b:label="##PORTAL_BOX_MENU_ITEM1##">
		<s:event b:on="command">
			<s:task b:action="load" b:url="main.php?process=window&amp;id=window_pane_schedule" b:test="not(id('window_schedule'))" b:destination="id('windowarea')" b:mode="aslastchild" />
			<s:task b:action="trigger" b:event="open" b:target="id('window_schedule')" />
			<s:task b:action="focus" b:event="open" b:target="id('window_schedule')" />
		</s:event>
	</b:contextmenurow>
	<b:contextmenurow b:label="##PORTAL_BOX_MENU_ITEM2##">
		<s:event b:on="command">
			<s:render b:destination="/*" b:mode="aslastchild">
				<b:modal b:state="selected">
					<b:modalhead>##POPUP_UNAVAILABLE_TITLE##</b:modalhead>
					<b:modalbody><div>##POPUP_UNAVAILABLE_BODY##</div><br/><br/><br/>##POPUP_UNAVAILABLE_FOOTER##<br /><br /><center>
						<b:button b:action="trigger" b:event="close" b:target="ancestor::b:modal[1]">##POPUP_UNAVAILABLE_CLOSE##</b:button></center>
					</b:modalbody>
				</b:modal>
			</s:render>
		</s:event>
	</b:contextmenurow>
	<b:contextmenurow b:label="##PORTAL_BOX_MENU_ITEM3##">
		<s:event b:on="command">
			<s:render b:destination="/*" b:mode="aslastchild">
				<b:modal b:state="selected">
					<b:modalhead>##POPUP_UNAVAILABLE_TITLE##</b:modalhead>
					<b:modalbody><div>##POPUP_UNAVAILABLE_BODY##</div><br/><br/><br/>##POPUP_UNAVAILABLE_FOOTER##<br /><br /><center>
						<b:button b:action="trigger" b:event="close" b:target="ancestor::b:modal[1]">##POPUP_UNAVAILABLE_CLOSE##</b:button></center>
					</b:modalbody>
				</b:modal>
			</s:render>
		</s:event>
	</b:contextmenurow>
	<b:contextmenurow b:label="##PORTAL_BOX_MENU_ITEM4##">
		<s:event b:on="command">
			<s:task b:action="load" b:url="main.php?process=window&amp;id=window_pane_stats" b:test="not(id('window_statistics'))" b:destination="id('windowarea')" b:mode="aslastchild" />
			<s:task b:action="trigger" b:event="open" b:target="id('window_statistics')" />
			<s:task b:action="focus" b:event="open" b:target="id('window_statistics')" />
		</s:event>
	</b:contextmenurow>
</b:contextmenu>