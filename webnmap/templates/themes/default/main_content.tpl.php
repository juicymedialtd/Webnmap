			<s:variable b:name="ready" b:select="'false'" />

			<s:event b:on="construct">
				<s:task b:action="show" />
			</s:event>

			<s:execute>
				<s:task b:action="hide" b:target="/body/html()/div[@id='calculating']" />
			</s:execute>

			<s:include b:url="/ajax/{$app_info.backbase_source}/Backbase/{$app_info.backbase_version}/controls/backbase/b-taskbar/b-taskbar.xml"  />
			<s:include b:url="/ajax/{$app_info.backbase_source}/Backbase/{$app_info.backbase_version}/controls/backbase/b-window/b-window.xml" />
			<s:include b:url="/ajax/{$app_info.backbase_source}/Backbase/{$app_info.backbase_version}/controls/backbase/b-windowarea/b-windowarea.xml" />
			<s:include b:url="/ajax/{$app_info.backbase_source}/Backbase/{$app_info.backbase_version}/controls/backbase/b-contextmenu/b-contextmenu.xml" />

			<b:panelset b:rows="* 28px">
				<b:panel>
					<b:windowarea id="windowarea" style="height:100%;width:100%;border:0;overflow: auto;">
						{include file="portal_index.tpl.php"}
						<!--{include file="window_select.tpl.php"}-->
					</b:windowarea>
				</b:panel>
				<b:panel>
					{include file="popup_menu_taskbar.tpl.php"}
					<!-- "windowmanager" -->
					{include file="window_taskbar.tpl.php"}
				</b:panel>
			</b:panelset>