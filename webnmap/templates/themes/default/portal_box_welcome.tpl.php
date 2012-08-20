<div xmlns="http://www.w3.org/1999/xhtml"
	xmlns:b="http://www.backbase.com/b"
	xmlns:s="http://www.backbase.com/s">

{literal}
	<style type="text/css">
	.portlet-welcome-outer {
		position: relative;
		width: 100%;
		height: 320px;
		background-color: #ffffff;
		background-image: url(gfx/welcome_background.gif);
		background-position: right bottom;
		background-repeat: no-repeat;
		padding: 15px 100px 15px 15px;
	}
	.portlet-welcome-inner {
		position: relative;
		width: 100%;
		height: 100%;
	}

	.portlet-welcome-inner a {
		color: #4f91bb;
	}

	.portlet-welcome-cnt,
	.portlet-welcome-content,
	.portlet-welcome-reminders {
		position: relative;
		margin-top: 12px;
		width: 100%;
		padding: 3px 0 8px 24px;
		background-repeat: no-repeat;
		background-position: left top;
	}

	.portlet-welcome-cnt {
		background-image: url(gfx/welcome_mailicon.gif);
	}

	.portlet-welcome-reminders {
		background-image: url(gfx/welcome_remindersicon.gif);
	}

	/* change fit when placed in narrow columns */
	.col-left .portlet-welcome-outer,
	.col-right .portlet-welcome-outer {
		height: auto;
		background-image: url(gfx/welcome_background.gif);
		background-position: right top;
		padding: 10px;
	}
	</style>
{/literal}

	<div class="portlet-welcome-outer">
		<div class="portlet-welcome-inner">

			<div class="portlet-welcome-greeting">
				##PORTAL_BOX_WELCOME_HEAD## <strong>{$user_info.name}</strong> [<a href="index.php?logout=1">##PORTAL_BOX_WELCOME_LOGOUT##</a>]
			</div>

			<div class="portlet-welcome-content">
				<table border="0" cellpadding="2">
				<tr>
					<td>##PORTAL_BOX_USERDETAIL_USER##</td>
					<td>{$user_info.username}</td>
				</tr>
				<tr>
					<td>##PORTAL_BOX_USERDETAIL_NAME##</td>
					<td>{$user_info.name}</td>
				</tr>
				<tr>
					<td>##PORTAL_BOX_USERDETAIL_EMAIL##</td>
					<td>{$user_info.email}</td>
				</tr>
				<tr>
					<td>##PORTAL_BOX_USERDETAIL_REG##</td>
					<td>{$user_info.registerDate}</td>
				</tr>
				<tr>
					<td>##PORTAL_BOX_USERDETAIL_LAST##</td>
					<td>{$user_info.lastvisitDate}</td>
				</tr>
				<tr>
					<td>##PORTAL_BOX_USERDETAIL_CREDITS##</td>
					<td>{$user_info.credits}</td>
				</tr>
				</table>
			</div>

			<div class="portlet-welcome-reminders">
				<strong>##PORTAL_BOX_WELCOME_SUBHEAD##</strong>
				<table border="0" cellpadding="2">
					<tr>
						<td>##PORTAL_BOX_STATUSES_PENDING##</td>
						<td>{$command_statuses.pending}</td>
					</tr>
					<tr>
						<td>##PORTAL_BOX_STATUSES_ACTIVE##</td>
						<td>{$command_statuses.active}</td>
					</tr>
					<tr>
						<td>##PORTAL_BOX_STATUSES_COMPLETED##</td>
						<td>{$command_statuses.completed}</td>
					</tr>
					<tr>
						<td>##PORTAL_BOX_STATUSES_RECURRING##</td>
						<td>{$command_statuses.recurring}</td>
					</tr>
					<tr>
						<td>##PORTAL_BOX_STATUSES_CANCELLED##</td>
						<td>{$command_statuses.cancelled}</td>
					</tr>
				</table>
			</div>
		</div>
	</div>

</div>