<b:window
	b:resize="none"
	b:windowbuttons="close minimize"
	id="window_weather"
	style="left:30px;top:30px;width:180px;height:240px;"
	xmlns="http://www.w3.org/1999/xhtml"
	xmlns:b="http://www.backbase.com/b"
	xmlns:s="http://www.backbase.com/s">
	<b:windowhead>Weather</b:windowhead>
	<b:windowbody>
		{literal}
			<style type="text/css">
				.portlet-weather {
					position: relative;
				}
				/* alternate background when in wide column */
				.col-middle .portlet-weather {
					background-image: url(gfx/weather_background_wide.gif);
					background-repeat: no-repeat;
					background-position: right bottom;
				}
				.portlet-weather table {
					table-layout: fixed;
					width: 100%;
				}
				.portlet-weather caption {
					padding: 4px;
				}
				.portlet-weather tr {
					height: 80px;
				}

				.portlet-weather caption,
				.portlet-weather th,
				.portlet-weather td {
					font-family: Tahoma, Verdana;
					font-size: 11px;
					color: #000000;
				}
				.portlet-weather th {
					font-weight: bold;
					text-align: center;
					width: 70px;
				}
				.portlet-weather td {
					text-align: left;
				}
				.portlet-weather td div.portlet-weather-temperature {
					background-image: url(gfx/weather_temp.gif);
					background-repeat: no-repeat;
					padding-left: 14px;
					font-size: 14px;
					font-weight: bold;
					line-height: 33px;
				}
			</style>
		{/literal}

		<table class="portlet-weather">
			<caption>
				Amsterdam, The Netherlands
			</caption>
			<tbody>
				<tr>
					<th>Today <img src="gfx/weather_iconsun.gif"/></th>
					<td>Sunny, clear skies<div class="portlet-weather-temperature">26&#176;C</div></td>
				</tr>
				<tr>
					<th>Tomorrow<img src="gfx/weather_iconcloudy.gif"/></th>
					<td>Sunny, some clouds<div class="portlet-weather-temperature">22&#176;C</div></td>
				</tr>
			</tbody>
		</table>
	</b:windowbody>
</b:window>