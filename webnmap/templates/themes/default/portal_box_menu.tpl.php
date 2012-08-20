<div class="mainmenu" xmlns="http://www.w3.org/1999/xhtml"
	xmlns:b="http://www.backbase.com/b"
	xmlns:s="http://www.backbase.com/s">
	<a>
		<s:event b:on="dblclick">
			<s:task b:action="load" b:url="main.php?process=window&amp;id=window_pane_schedule" b:test="not(id('window_schedule'))" b:destination="id('windowarea')" b:mode="aslastchild" />
			<s:task b:action="trigger" b:event="open" b:target="id('window_schedule')" />
			<s:task b:action="focus" b:event="open" b:target="id('window_schedule')" />
		</s:event>
		<img src="themes/{$domain_ext_info._site_templatedir}/icons/icon_schedule.gif" alt="##PORTAL_BOX_MENU_ITEM1##" title="##PORTAL_BOX_MENU_ITEM1##" border="0" />
		<br />
		##PORTAL_BOX_MENU_ITEM1##
	</a>
	<a>
		<s:event b:on="dblclick">
			<s:render b:destination="/*" b:mode="aslastchild">
				<b:modal b:state="selected">
					<b:modalhead>##POPUP_UNAVAILABLE_TITLE##</b:modalhead>
					<b:modalbody><div>##POPUP_UNAVAILABLE_BODY##</div><br/><br/><br/>##POPUP_UNAVAILABLE_FOOTER##<br /><br /><center>
						<b:button b:action="trigger" b:event="close" b:target="ancestor::b:modal[1]">##POPUP_UNAVAILABLE_CLOSE##</b:button></center>
					</b:modalbody>
				</b:modal>
			</s:render>
		</s:event>
		<img src="themes/{$domain_ext_info._site_templatedir}/icons/icon_profile.gif" alt="##PORTAL_BOX_MENU_ITEM2##" title="##PORTAL_BOX_MENU_ITEM2##" border="0" />
		<br />
		##PORTAL_BOX_MENU_ITEM2##
	</a>
	<a>
		<s:event b:on="dblclick">
			<s:render b:destination="/*" b:mode="aslastchild">
				<b:modal b:state="selected">
					<b:modalhead>##POPUP_UNAVAILABLE_TITLE##</b:modalhead>
					<b:modalbody><div>##POPUP_UNAVAILABLE_BODY##</div><br/><br/><br/>##POPUP_UNAVAILABLE_FOOTER##<br /><br /><center>
						<b:button b:action="trigger" b:event="close" b:target="ancestor::b:modal[1]">##POPUP_UNAVAILABLE_CLOSE##</b:button></center>
					</b:modalbody>
				</b:modal>
			</s:render>
		</s:event>
		<img src="themes/{$domain_ext_info._site_templatedir}/icons/icon_feedback.gif" alt="##PORTAL_BOX_MENU_ITEM3##" title="##PORTAL_BOX_MENU_ITEM3##" border="0" />
		<br />
		##PORTAL_BOX_MENU_ITEM3##
	</a>
	<a>
		<s:event b:on="dblclick">
			<s:task b:action="load" b:url="main.php?process=window&amp;id=window_pane_stats" b:test="not(id('window_statistics'))" b:destination="id('windowarea')" b:mode="aslastchild" />
			<s:task b:action="trigger" b:event="open" b:target="id('window_statistics')" />
			<s:task b:action="focus" b:event="open" b:target="id('window_statistics')" />
		</s:event>
		<img src="themes/{$domain_ext_info._site_templatedir}/icons/icon_stats.gif" alt="##PORTAL_BOX_MENU_ITEM4##" title="##PORTAL_BOX_MENU_ITEM4##" border="0" />
		<br />
		##PORTAL_BOX_MENU_ITEM4##
	</a>
</div>