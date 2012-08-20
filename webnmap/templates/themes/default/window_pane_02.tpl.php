<b:window
	id="window_finance"
	style="left:50px;top:50px;width:540px;height:580px;"
	xmlns="http://www.w3.org/1999/xhtml"
	xmlns:b="http://www.backbase.com/b"
	xmlns:s="http://www.backbase.com/s">

	<b:windowhead>Finance</b:windowhead>
	<b:windowbody>
			{literal}
			<style type="text/css">
				.windows-stocktable {
					width: 100%;
				}
				.windows-stocktable caption,
				.windows-stocktable th,
				.windows-stocktable td {
					font-family: Tahoma, Verdana;
					font-size: 11px;
					color: #000000;
				}
				.windows-stocktable caption {
					padding: 2px 4px 0 4px;
					margin-bottom: 4px;
					text-align: left;
					font-weight: normal;
				}
				.windows-stocktablerow td {
					background-color: #ffffff;
				}
				.windows-stocktablerow-hov td {
					background-color: #eaf9ff;
				}
				.windows-stocktable th {
					background-color: #ff0000;
					color:#ffffff;
					padding: 2px 0;
				}
				.windows-stocktable-header-hov {
					text-decoration: underline;
				}
				.windows-stocktable td {
					border: 1px solid #b9cfd8;
					text-align: center;
				}
				.windows-stocktable td.windows-stocktable-firstcell {
					text-align: left;
				}
				</style>
			{/literal}

			<s:behavior b:name="windows-stocktableheader">
				<s:state
					b:on="deselect"
					b:normal="windows-stocktable-header"
					b:hover="windows-stocktable-header windows-stocktable-header-hov" />

				<s:event b:on="command" b:action="sort" />
			</s:behavior>

			<s:behavior b:name="windows-stocktablerow">
				<s:state
					b:on="deselect"
					b:normal="windows-stocktablerow"
					b:hover="windows-stocktablerow windows-stocktablerow-hov"/>
			</s:behavior>

			<s:behavior b:name="windows-stocktable">
				<s:state b:on="deselect" b:normal="windows-stocktable"/>
			</s:behavior>

				<table b:behavior="windows-stocktable" cellspacing="2">
					<caption style="color:red;font-size:12px;font-weight:bold;">Click the column header to sort the table</caption>
					<thead>
						<tr>
							<th b:behavior="windows-stocktableheader">Symbol</th>
							<th b:behavior="windows-stocktableheader">Last</th>
							<th b:behavior="windows-stocktableheader">%</th>
							<th b:behavior="windows-stocktableheader">Vol</th>
						</tr>
					</thead>
					<tbody>
						<tr b:behavior="windows-stocktablerow">
							<td class="windows-stocktable-firstcell">SP500</td>
							<td>44</td>
							<td>+40%</td>
							<td>32</td>
						</tr>
						<tr b:behavior="windows-stocktablerow">
							<td class="windows-stocktable-firstcell">NSDAQ</td>
							<td>534</td>
							<td>+16%</td>
							<td>16</td>
						</tr>
						<tr b:behavior="windows-stocktablerow">
							<td class="windows-stocktable-firstcell">TRLV</td>
							<td>644</td>
							<td>+3%</td>
							<td>8</td>
						</tr>
						<tr b:behavior="windows-stocktablerow">
							<td class="windows-stocktable-firstcell">KO</td>
							<td>63</td>
							<td>-4%</td>
							<td>21</td>
						</tr>
						<tr b:behavior="windows-stocktablerow">
							<td class="windows-stocktable-firstcell">INTC</td>
							<td>23</td>
							<td>-18%</td>
							<td>44</td>
						</tr>
						<tr>
							<td class="windows-stocktable-firstcell">DJIA</td>
							<td>78</td>
							<td>+40%</td>
							<td>13</td>
						</tr>
						<tr>
							<td class="windows-stocktable-firstcell">MSFT</td>
							<td>553</td>
							<td>-36%</td>
							<td>46</td>
						</tr>
					</tbody>
				</table>
				<br/>
				<center>
					<img src="gfx/Nasdaq-graph.gif" width="512" height="289" />
				</center>

	</b:windowbody>
</b:window>