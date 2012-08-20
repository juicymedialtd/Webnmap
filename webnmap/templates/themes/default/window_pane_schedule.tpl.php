<b:window
	id="window_schedule"
	b:minwidth="600px"
	b:minheight="300px"
	b:resize="none"
	b:windowbuttons="minimize,close"
	style="left:25%;top:40px;width:600px;height:300px;"
	xmlns="http://www.w3.org/1999/xhtml"
	xmlns:b="http://www.backbase.com/b"
	xmlns:s="http://www.backbase.com/s">
	<b:windowhead b:icon="none">##WINDOW_SCHEDULE_TITLE##</b:windowhead>
	<b:windowbody>
		<s:tasklist b:name="proceed_cb">
			<s:task b:on="command" b:target="id('proceed')" b:action="enable" />
			<s:task b:on="command" b:target="id('backout')" b:action="disable" />
		</s:tasklist>
		<s:tasklist b:name="domain_check">
			<s:variable b:name="valid_domain" b:select="'0'" b:scope="local"/>
			{literal}
			<s:script>
			<!--
			if (!FSfncValidateDomain(document.getElementById('host_name'))) {
				bpc.setVariable('valid_domain',['0']);
			} else {
				bpc.setVariable('valid_domain',['1']);
			};
			-->
			</s:script>
			<s:if b:test="$valid_domain = '1'">
				<s:sequential>
					<s:task b:on="command" b:target="id('gotoStepTwo')" b:action="enable" />
					<s:task b:action="trigger" b:target="ancestor::b:step[1]" b:event="next" />
				</s:sequential>
			</s:if>
			<s:if b:test="$valid_domain = '0'">
				<s:task b:on="command" b:target="id('gotoStepTwo')" b:action="disable" />
			</s:if>
			{/literal}
		</s:tasklist>
		<s:tasklist b:name="proceed_cmd">
			<s:sequential>
				<s:task b:action="load" b:url="data.php?process=msg&amp;text=Test" b:destination="id('second_form')" b:mode="replace" />
				<!--<s:task b:action="settext" b:target="id('second_form')" b:value="closing..." />-->
				<s:task b:action="submit" b:target="id('mainschedule')" />
			</s:sequential>
		</s:tasklist>
		<s:include b:url="/ajax/{$app_info.backbase_source}/Backbase/{$app_info.backbase_version}/controls/backbase/forms.xml"/>
	    <form id="mainschedule" name="mainschedule" b:destination="." b:mode="replace" action="data.php" method="post" b:behavior="form">
			<b:step b:step="1" id="step1">
			   <b:panelset b:rows="29px * 29px">
					<b:panel style="background-color: #fff; padding: 0px; overflow:hidden;">
						<strong>##WINDOW_SCHEDULE_STEP1##</strong>
					</b:panel>
					<b:panel style="background-color: #fff; padding: 0px;">
						<input type="hidden" name="user_id" id="user_id" value="{$user_info.id}" />
						<input type="hidden" name="process" value="addcommand" />
						<div align="center"><img src="/themes/{$domain_ext_info._site_templatedir}/command_computer.gif" border="0" /></div>
						<div style="position: absolute; left: 60px; top: 75px;">
							##WINDOW_SCHEDULE_STEP1_INNER1##<input type="text" name="host_name" id="host_name" b:required="true" b:onblur="domain_check" style="width:160px;" />
						</div>
						<!-- <s:execute>
							<s:task b:action="js" b:value="{literal}bpc.xpath('id(\'host_name\')/html()/'+'/input[@type=\'text\']',_current)[0].focus(){/literal}" />
						</s:execute>
						-->
				 	</b:panel>
				 	<b:panel style="background-color: #fff; padding: 0px; text-align: right;">
						<b:button b:icon="next" id="gotoStepTwo" b:onclick="domain_check">##WINDOW_SCHEDULE_NEXT##</b:button>
					</b:panel>
				</b:panelset>
			</b:step>
			<b:step b:step="2" id="step2">
			   <b:panelset b:rows="29px * 29px">
					<b:panel style="background-color: #fff; padding: 0px; overflow:hidden;">
						<strong>##WINDOW_SCHEDULE_STEP2##</strong>
					</b:panel>
					<b:panel style="background-color: #fff; padding: 5px;">
				 		<b:box>
					 		##WINDOW_SCHEDULE_STEP2_INNER3##
					 		<br />
					 		<div align="right">##WINDOW_SCHEDULE_STEP2_INNER2## <input type="checkbox" name="permission" id="permission" value="1" b:onclick="proceed_cb" b:required="true" /></div>
				 		</b:box>
				 	</b:panel>
				 	<b:panel style="background-color: #fff; padding: 0px; text-align: right;">
						<b:button b:icon="previous" id="backout" b:action="trigger" b:target="ancestor::b:step[1]" b:event="previous">##WINDOW_SCHEDULE_PREV##</b:button>
						<b:button b:icon="next" id="proceed" b:action="trigger" b:target="ancestor::b:step[1]" b:event="next">##WINDOW_SCHEDULE_NEXT##</b:button>
					    <s:execute>
					      	<s:task b:action="disable" b:target="id('proceed')" />
					    </s:execute>
					</b:panel>
				</b:panelset>
			</b:step>
			<b:step b:step="3" id="step3">
			   <b:panelset b:rows="29px * 29px">
					<b:panel style="background-color: #fff; padding: 0px; overflow:hidden;">
						<strong>##WINDOW_SCHEDULE_STEP3##</strong>
					</b:panel>
					<b:panel style="background-color: #fff; padding: 0px;">
						 <b:panelset b:cols="260px *">
						 	<b:panel style="background-color: #fff; padding: 0px;">
						 		<b:box style="width: 100%;height: 98%;">
							 		<div id="second_form">
										<form id="command_detail" name="command_detail" method="post" b:destination="id('dataview')" action="data.php">
											<input type="hidden" name="process" value="cmddetail" />
											<input type="hidden" id="idtree" name="id" value="1" />
		 							 		<div id="mtree">##WINDOW_SCHEDULE_STEP3_INNER1##</div>
										    <s:execute>
										      	<s:task b:action="load" b:url="data.php?process=cmdlist" b:destination="id('mtree')" b:mode="replace" />
										    </s:execute>
										</form>
									</div>
								</b:box>
						 	</b:panel>
						 	<b:panel style="background-color: #fff; padding-left: 5px">
								<div id="dataview" style="width: 100%;">
									<strong>##WINDOW_SCHEDULE_STEP3_INNER2##</strong>
									<input type="text" id="command_id" name="command_id" value="" b:required="true" style="border:none;" />
									<input type="text" id="command" name="command" value="" b:required="true" style="border:none;" />
									<input type="text" id="group_id" name="group_id" value="" b:required="true" style="border:none;" />
								</div>
						 	</b:panel>
						 </b:panelset>
				 	</b:panel>
				 	<b:panel style="background-color: #fff; padding: 0px; text-align: right;">
						<b:button b:icon="previous" b:action="trigger" b:target="ancestor::b:step[1]" b:event="previous">##WINDOW_SCHEDULE_PREV##</b:button>
						<b:button b:icon="next" b:action="trigger" b:target="ancestor::b:step[1]" b:event="next">##WINDOW_SCHEDULE_NEXT##</b:button>
					</b:panel>
				 </b:panelset>
			</b:step>
			<b:step b:step="4" id="step4">
			   <b:panelset b:rows="29px * 29px">
					<b:panel style="background-color: #fff; padding: 0px; overflow:hidden;">
						<strong>##WINDOW_SCHEDULE_STEP4##</strong> ##WINDOW_SCHEDULE_STEP4_EXTRA## {$smarty.now|date_format:"%H:%M:%S"}
					</b:panel>
					<b:panel style="background-color: #fff; padding: 0px;">
						<table width="100%"  border="0" cellspacing="0" cellpadding="0">
						  <tr>
						    <td height="35"><strong>##WINDOW_SCHEDULE_STEP4_INNER2##</strong></td>
						    <td>
								<input type="text" name="hour_exec" b:connect="id('hour_sel')" b:valid="number" style="width: 20px;border:none;" /> ##WINDOW_SCHEDULE_STEP4_INNER4##
								<b:slider b:snap="true" id="hour_sel" b:value="{$smarty.now|date_format:"%k"}">
								{section name=hour_sel start=0 loop=24 step=1}
								  	<b:slider-option>{$smarty.section.hour_sel.index}</b:slider-option>
								{/section}
								</b:slider>
						    </td>
						  </tr>
						  <tr>
						    <td height="35"><strong>##WINDOW_SCHEDULE_STEP4_INNER3##</strong></td>
						    <td>
								<input type="text" name="min_exec" b:connect="id('min_sel')" b:valid="number" value="{math equation="round(x,-1)" x=$smarty.now|date_format:"%M"}" style="width: 20px;border:none;" /> ##WINDOW_SCHEDULE_STEP4_INNER5##
								<b:slider b:snap="true" id="min_sel" b:value="{math equation="round(x,-1)" x=$smarty.now|date_format:"%M"}">
								{section name=min_sel start=0 loop=60 step=5}
								  	<b:slider-option>{$smarty.section.min_sel.index}</b:slider-option>
								{/section}
								</b:slider>
						    </td>
						  </tr>
						  <!--<tr>
						    <td height="35"><strong>##WINDOW_SCHEDULE_STEP4_INNER1##</strong></td>
						    <td><input type="text" name="date_exec" b:required="true" value="{$smarty.now|date_format:"%d %m %Y"}" /><b:datepicker b:type="float" b:input="../input[1]" b:format="dd MM yyyy" /></td>
						  </tr>-->
						  <tr>
						    <td valign="top"><strong>##WINDOW_SCHEDULE_STEP4_INNER6##</strong></td>
						    <td>
						    	<b:box>
							    	<!--<table width="285"  border="0" cellspacing="0" cellpadding="0">
							    	<thead>
							    		<tr><td colspan="3">##WINDOW_SCHEDULE_STEP4_INNER12##</td></tr>
							    	</thead>
							    	<tbody>
							    	<tr>
								    	<td><label><input type="checkbox" name="rec_minute" value="1" />##WINDOW_SCHEDULE_STEP4_INNER11##</label></td>
								    	<td><label><input type="checkbox" name="rec_hour" value="1" />##WINDOW_SCHEDULE_STEP4_INNER7##</label></td>
								    	<td><label><input type="checkbox" name="rec_day" value="1" />##WINDOW_SCHEDULE_STEP4_INNER8##</label></td>
							    	</tr>
								    <tr>
								    	<td><label><input type="checkbox" name="rec_month" value="1" />##WINDOW_SCHEDULE_STEP4_INNER9##</label></td>
								    	<td><label><input type="checkbox" name="rec_year" value="1" />##WINDOW_SCHEDULE_STEP4_INNER10##</label></td>
								    	<td></td>
								    </tr>
								    </tbody>
								    </table>-->
									<table width="285"  border="0" cellspacing="0" cellpadding="0">
							    	<thead>
							    		<tr><td colspan="3">##WINDOW_SCHEDULE_STEP4_INNER12##</td></tr>
							    	</thead>
							    	<tbody>
							    	<tr>
								    	<td><input type="checkbox" name="rec_minute" value="1" /><label>##WINDOW_SCHEDULE_STEP4_INNER11##</label></td>
								    	<td><input type="checkbox" name="rec_hour" value="1" /><label>##WINDOW_SCHEDULE_STEP4_INNER7##</label></td>
							    	</tr>
							    	</tbody>
								    </table>
						    	</b:box>
						    </td>
						  </tr>

						</table>
				 	</b:panel>
				 	<b:panel style="background-color: #fff; padding: 0px; text-align: right;">
						<b:button b:icon="previous" b:action="trigger" b:target="ancestor::b:step[1]" b:event="previous">##WINDOW_SCHEDULE_PREV##</b:button>
						<b:button b:icon="next" b:action="trigger" b:target="ancestor::b:step[1]" b:event="next">##WINDOW_SCHEDULE_NEXT##</b:button>
					</b:panel>
				</b:panelset>
			</b:step>
			<b:step b:step="5" class="final-step">
			   <b:panelset b:rows="29px * 29px">
					<b:panel style="background-color: #fff; padding: 0px; overflow:hidden;">
						<strong>##WINDOW_SCHEDULE_STEP5##</strong>
					</b:panel>
					<b:panel style="background-color: #fff; padding: 0px;">
				 		<b:box>
					 		##WINDOW_SCHEDULE_STEP5_INNER1##
					 		<br />
							<strong>##WINDOW_SCHEDULE_STEP5_INNER2##</strong>
							<p id="form_review"></p>
				 		</b:box>
				 	</b:panel>
				 	<b:panel style="background-color: #fff; padding: 0px; text-align: right;">
						<b:button b:icon="previous" b:action="trigger" b:target="ancestor::b:step[1]" b:event="previous">##WINDOW_SCHEDULE_PREV##</b:button>
						<b:button b:icon="next" b:onclick="proceed_cmd">##WINDOW_SCHEDULE_ADD##</b:button>
					</b:panel>
				</b:panelset>
			</b:step>
		</form>
	</b:windowbody>
	<s:event b:on="close">
		<s:task b:action="remove" b:target="id('window_schedule')" />
	</s:event>
</b:window>