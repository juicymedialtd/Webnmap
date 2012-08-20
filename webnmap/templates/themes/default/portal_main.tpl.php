				<!-- Main portal view -->
				<div id="portal-main">
					<table class="portal-columns-table" cellspacing="0" cellpadding="0" border="0">
						<tbody>
							<tr>
								<td class="portal-column col-left">
									<div class="portal-column-inner">
										<!-- Left column contents -->
										<div class="portal-column-content" id="portal-column-left" b:dragreceive="portal">
											<b:portlet id="portlet-mainmenu">
												<b:portlet-head b:caption="##PORTAL_BOX_MENU_TITLE##"/>
												<b:portlet-body>
													<s:include b:url="main.php?process=portal&amp;id=portal_box_menu"/>
												</b:portlet-body>
											</b:portlet>
											<b:portlet id="portlet-serverstatus">
												<b:portlet-head b:caption="##PORTAL_BOX_SERVER_STATUS_TITLE##"/>
												<b:portlet-body id="box-server-status">
													<s:include b:url="main.php?process=portal&amp;id=portal_box_server_status"/>
												</b:portlet-body>
											</b:portlet>
										</div>
									</div>
								</td>
								<td class="portal-column col-middle">
									<div class="portal-column-inner">
										<!-- Middle column contents -->
										<div class="portal-column-content" id="portal-column-middle" b:dragreceive="portal">
											<b:portlet id="portlet-welcome">
												<b:portlet-head b:caption="##PORTAL_BOX_WELCOME_TITLE##"/>
												<b:portlet-body>
													<s:include b:url="main.php?process=portal&amp;id=portal_box_welcome"/>
												</b:portlet-body>
											</b:portlet>
											<b:portlet id="portlet-tasks">
												<b:portlet-head b:caption="##PORTAL_BOX_TASKS_TITLE##"/>
												<b:portlet-body>
													<s:include b:url="main.php?process=portal&amp;id=portal_box_tasks"/>
												</b:portlet-body>
											</b:portlet>
										</div>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>