				<!-- Header-->
				<div id="portal-header">
					<div id="portal-header-inner" style="background-image: url(/gfx/header_logo.gif);">
						<!-- Search box -->
						<div id="portal-header-login">
								<table cellspacing="5" cellpadding="0" border="0">
								  <tbody>
								    <tr>
								      <td>Username:</td>
								      <td>{$smarty.session.session_username} (<a href="/index.php?logout=1" target="_parent">x</a>)</td>
								    </tr>
								    <tr>
								      <td>Date:</td>
								      <td>{$smarty.now|date_format:"%d/%m/%Y"}</td>
								    </tr>
								  </tbody>
								</table>
						</div>
					</div>
				</div>