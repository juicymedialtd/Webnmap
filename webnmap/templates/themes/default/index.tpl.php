<!-- -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title>{$domain_ext_info._site_title}</title>
	<meta http-equiv="Content-Type" content="text/html" />
	<meta http-equiv="pragma" content="no-cache" />
	<meta name="robots" content="index,follow" />
	<meta name="author" content="{$domain_client_info._site_client_name}" />
	<meta name="description" content="{$domain_meta.description}" />
	<meta name="keywords" content="{$domain_meta.keywords}" />
	<meta name="abstract" content="{$domain_meta.abtract}" />
	<meta name="subject" content="{$domain_meta.subject}" />
	<meta name="revisit-after" content="7 Days" />
	<meta name="DC.Title" content="{$domain_ext_info._site_title}" />
	<meta name="DC.Creator" content="Juicy Media Ltd" />
	<meta name="DC.Description" content="{$domain_meta.description}" />
	<meta name="DC.Language" content="en" />
	<link rel="shortcut icon" href="http://{$domain_info._fqdn}/themes/{$domain_ext_info._site_templatedir}/favicon.ico" />
    <link rel="icon" href="http://{$domain_info._fqdn}/themes/{$domain_ext_info._site_templatedir}/animated_favicon1.gif" type="image/gif" >
	<style type="text/css">
	@import url(themes/{$domain_ext_info._site_templatedir}/admin_login.css);
	</style>
	{literal}
	<script language="javascript" type="text/javascript">
		function setFocus() {
			document.loginForm.usrname.select();
			document.loginForm.usrname.focus();
		}
	</script>
	{/literal}
</head>
<body onload="{literal}setFocus();{/literal}">
	<div id="wrapper">
		<div id="header">
				<div id="joomla"><img src="themes/{$domain_ext_info._site_templatedir}/header_text.png" alt="Webnmap Logo" /></div>
		</div>
	</div>
	<div id="ctr" align="center">
			{if $smarty.request.msg <> ""}
				<div class="message">
					{$smarty.request.msg}
				</div>
			{/if}
		<div class="login">
			<div class="login-form">
				<img src="themes/{$domain_ext_info._site_templatedir}/login.gif" alt="Login" />
				<form action="index.php" method="post" name="loginForm" id="loginForm">
				<div class="form-block">
					<div class="inputlabel">##LOGIN_USERNAME##</div>
					<div><input name="usrname" type="text" class="inputbox" size="15" /></div>
					<div class="inputlabel">##LOGIN_PASSNAME##</div>
					<div><input name="pass" type="password" class="inputbox" size="15" /></div>
					<div align="left"><input type="submit" name="submit" class="button" value="##LOGIN_SUBMIT##" /></div>
				</div>
				</form>
			</div>
			<div class="login-text">
				<div class="ctr"><img src="themes/{$domain_ext_info._site_templatedir}/security.png" width="64" height="64" alt="security" /></div>
				<p>{$domain_site_content.title}</p>
				<p>##LOGIN_USE_VALID##</p>
			</div>
			<div class="clr"><br /></div>
			<div class="form-block">
				<div align="left">
					<div style="float:right;display:none;">
						<a href="http://www.juicymedia.co.uk"><img src="/gfx/logos/juicymedia_button.gif" border="0" /></a><br />
						<a href="http://www.backbase.com"><img src="/gfx/logos/backbase.gif" border="0" /></a><br />
						<a href="http://adodb.sourceforge.net"><img src="/gfx/logos/adodb.gif" border="0" /></a><br />
						<a href="http://smarty.php.net"><img src="/gfx/logos/smarty_icon.gif" border="0" /></a><br />
						<a href="http://phpgacl.sourceforge.net"><img src="/gfx/logos/phpgacl.gif" border="0" /></a>
					</div>
					<strong><u>{$domain_site_content.title}</u></strong>
					{$domain_site_content.content}
					<p><a href="register.php">##LOGIN_REGISTER##</a></p>
					<p>##LOGIN_ABUSE##</p>
				</div>
			</div>
		</div>
		<!--<p>Copyright <a href="http://www.pkdavies.co.uk" target="_blank">Peter Davies</a>, <a href="http://www.juicymedia.co.uk" target="_blank">Juicy Media Ltd</a></p>-->
	</div>
	<div id="break"></div>
	<noscript>
	##REQ_JAVASCRIPT##
	</noscript>
	<div class="footer" align="center">
		<div align="center">
		</div>
	</div>
</body>
</html>