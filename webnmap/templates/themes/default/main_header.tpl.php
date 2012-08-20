<!-- -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:b="http://www.backbase.com/b" xmlns:s="http://www.backbase.com/s">
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
	<meta name="DC.Creator" content="Peter Davies - Juicy Media Ltd" />
	<meta name="DC.Description" content="{$domain_meta.description}" />
	<meta name="DC.Language" content="en" />
	<link rel="stylesheet" type="text/css" href="/themes/{$domain_ext_info._site_templatedir}/css/application.css" />
	<script type="text/javascript" src="/ajax/{$app_info.backbase_source}/Backbase/{$app_info.backbase_version}/bpc/boot.js" ></script>
	<script type="text/javascript" src="/themes/{$domain_ext_info._site_templatedir}/js/portal.js"></script>
	<script type="text/javascript" src="/themes/{$domain_ext_info._site_templatedir}/js/FScheckDomain.js"></script>
	{literal}
	<script type="text/javascript">
		function runClock(){
			var oDate = new Date();
			var s = oDate.getHours() + ":" + oDate.getMinutes() + ":" + oDate.getSeconds();
			document.getElementById('clock').innerHTML = s;
		   	setTimeout(runClock, 1000);
		}
	</script>
	{/literal}
</head>
<body onload="bpc.boot('/ajax/{$app_info.backbase_source}/Backbase/{$app_info.backbase_version}/');" b:controlpath="/ajax/{$app_info.backbase_source}/Backbase/{$app_info.backbase_version}/controls/backbase">

<div id="calculating">
	<table class="s-loading" cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<td class="s-loading-left"></td>
				<td class="s-loading-mid">
					<div class="s-loading-content">
						##LOADING_INITIALISING##
					</div>
				</td>
				<td class="s-loading-right"></td>
			</tr>
		</tbody>
	</table>
</div>

<div id="loading" style="display:none;">
	<table class="s-loading" cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<td class="s-loading-left"></td>
				<td class="s-loading-mid">
					<div class="s-loading-content">
						##LOADING_DATA##
					</div>
				</td>
				<td class="s-loading-right"></td>
			</tr>
		</tbody>
	</table>
</div>

<xmp b:backbase="true" style="display:none;">