<?php /* Smarty version 2.6.14, created on 2006-09-13 11:29:07
         compiled from portal_box_server_status.tpl.php */ ?>
<div class="mainmenu" xmlns="http://www.w3.org/1999/xhtml"
	xmlns:b="http://www.backbase.com/b"
	xmlns:s="http://www.backbase.com/s">
	<p style="padding:5px;">The server status is presently:	</p>
	<div align="center" style="padding-bottom:10px;">
		<?php if ($this->_tpl_vars['page_data']['load'] <= 0.2): ?>
			<img src="themes/default/icons/header_icon_start.gif" alt="Idle" title="Idle" /><br /><strong>Idle @ </strong>
		<?php else: ?>
			<img src="themes/default/icons/header_icon_stop.gif" alt="Busy" title="Busy" /><br /><strong>Busy @ </strong>
		<?php endif; ?>
		<?php echo $this->_tpl_vars['page_data']['load']; ?>

	</div>
</div>