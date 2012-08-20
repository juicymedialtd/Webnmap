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
	<style type="text/css">
	@import url(themes/{$domain_ext_info._site_templatedir}/admin_login.css);
	</style>
	{literal}
	<script language="javascript" type="text/javascript">
		function setFocus() {
			document.regForm.usrname.select();
			document.regForm.usrname.focus();
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
		<div class="login">
			<div class="login-form">
				<img src="themes/{$domain_ext_info._site_templatedir}/register.gif" alt="Register" />
				<form action="register.php" method="post" name="regForm" id="regForm">
				<div class="form-block">
					<div class="inputlabel">##REGISTER_NAME##</div>
					<div><input name="fullname" type="text" class="inputbox" size="15" /></div>
					<div class="inputlabel">##REGISTER_EMAIL##</div>
					<div><input name="email" type="text" class="inputbox" size="15" /></div>
					<div class="inputlabel">##REGISTER_USERNAME##</div>
					<div><input name="usrname" type="text" class="inputbox" size="15" /></div>
					<div class="inputlabel">##REGISTER_PASS1##</div>
					<div><input name="pass" type="password" class="inputbox" size="15" /></div>
					<div class="inputlabel">##REGISTER_PASS2##</div>
					<div><input name="pass2" type="password" class="inputbox" size="15" /></div>
					<div align="left"><input type="submit" name="submit" class="button" value="##REGISTER_SUBMIT##" /></div>
				</div>
				</form>
			</div>
			<div class="login-text">
				<div class="ctr"><img src="themes/{$domain_ext_info._site_templatedir}/security.png" width="64" height="64" alt="security" /></div>
				<p>##REGISTER_SIDETEXT##</p>
			</div>
			<div class="clr"><br /></div>
			<div class="form-block">
				<div align="left">
					<strong><u>##REGISTER_TITLE##</u></strong>
					<p>##REGISTER_BODY##</p>
					<p><a href="index.php">##REGISTER_LINK##</a></p>
				</div>
			</div>
		</div>
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