<?php /* Smarty version 2.6.14, created on 2006-09-13 11:29:35
         compiled from window_pane_schedule.tpl.php */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'window_pane_schedule.tpl.php', 132, false),array('function', 'math', 'window_pane_schedule.tpl.php', 150, false),)), $this); ?>
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
	<b:windowhead b:icon="none">Command Queue Scheduler</b:windowhead>
	<b:windowbody>
		<s:tasklist b:name="proceed_cb">
			<s:task b:on="command" b:target="id('proceed')" b:action="enable" />
			<s:task b:on="command" b:target="id('backout')" b:action="disable" />
		</s:tasklist>
		<s:tasklist b:name="domain_check">
			<s:variable b:name="valid_domain" b:select="'0'" b:scope="local"/>
			<?php echo '
			<s:script>
			<!--
			if (!FSfncValidateDomain(document.getElementById(\'host_name\'))) {
				bpc.setVariable(\'valid_domain\',[\'0\']);
			} else {
				bpc.setVariable(\'valid_domain\',[\'1\']);
			};
			-->
			</s:script>
			<s:if b:test="$valid_domain = \'1\'">
				<s:sequential>
					<s:task b:on="command" b:target="id(\'gotoStepTwo\')" b:action="enable" />
					<s:task b:action="trigger" b:target="ancestor::b:step[1]" b:event="next" />
				</s:sequential>
			</s:if>
			<s:if b:test="$valid_domain = \'0\'">
				<s:task b:on="command" b:target="id(\'gotoStepTwo\')" b:action="disable" />
			</s:if>
			'; ?>

		</s:tasklist>
		<s:tasklist b:name="proceed_cmd">
			<s:sequential>
				<s:task b:action="load" b:url="data.php?process=msg&amp;text=Test" b:destination="id('second_form')" b:mode="replace" />
				<!--<s:task b:action="settext" b:target="id('second_form')" b:value="closing..." />-->
				<s:task b:action="submit" b:target="id('mainschedule')" />
			</s:sequential>
		</s:tasklist>
		<s:include b:url="/ajax/<?php echo $this->_tpl_vars['app_info']['backbase_source']; ?>
/Backbase/<?php echo $this->_tpl_vars['app_info']['backbase_version']; ?>
/controls/backbase/forms.xml"/>
	    <form id="mainschedule" name="mainschedule" b:destination="." b:mode="replace" action="data.php" method="post" b:behavior="form">
			<b:step b:step="1" id="step1">
			   <b:panelset b:rows="29px * 29px">
					<b:panel style="background-color: #fff; padding: 0px; overflow:hidden;">
						<strong>STEP 1: Enter the domain name of the remote server.</strong>
					</b:panel>
					<b:panel style="background-color: #fff; padding: 0px;">
						<input type="hidden" name="user_id" id="user_id" value="<?php echo $this->_tpl_vars['user_info']['id']; ?>
" />
						<input type="hidden" name="process" value="addcommand" />
						<div align="center"><img src="/themes/<?php echo $this->_tpl_vars['domain_ext_info']['_site_templatedir']; ?>
/command_computer.gif" border="0" /></div>
						<div style="position: absolute; left: 60px; top: 75px;">
							Host:<input type="text" name="host_name" id="host_name" b:required="true" b:onblur="domain_check" style="width:160px;" />
						</div>
						<!-- <s:execute>
							<s:task b:action="js" b:value="<?php echo 'bpc.xpath(\'id(\\\'host_name\\\')/html()/\'+\'/input[@type=\\\'text\\\']\',_current)[0].focus()'; ?>
" />
						</s:execute>
						-->
				 	</b:panel>
				 	<b:panel style="background-color: #fff; padding: 0px; text-align: right;">
						<b:button b:icon="next" id="gotoStepTwo" b:onclick="domain_check">Next</b:button>
					</b:panel>
				</b:panelset>
			</b:step>
			<b:step b:step="2" id="step2">
			   <b:panelset b:rows="29px * 29px">
					<b:panel style="background-color: #fff; padding: 0px; overflow:hidden;">
						<strong>STEP 2: Do you have permission to scan this host? - REQUIRED</strong>
					</b:panel>
					<b:panel style="background-color: #fff; padding: 5px;">
				 		<b:box>
					 		You accept by adding a command/task to this system that you have been given authorisation to perform the given command against the target host. You also understand that all contact details will be passed to the necessary authorities or owner(s) of the target host if used maliciously.<br />You also agree that we cannot be held liable for any damage or loss of business as a result of using this web tool.<br /><br />For further details or concerns about the use of this system, please contact pete@juicymedia.co.uk
					 		<br />
					 		<div align="right">Tick this box to agree: <input type="checkbox" name="permission" id="permission" value="1" b:onclick="proceed_cb" b:required="true" /></div>
				 		</b:box>
				 	</b:panel>
				 	<b:panel style="background-color: #fff; padding: 0px; text-align: right;">
						<b:button b:icon="previous" id="backout" b:action="trigger" b:target="ancestor::b:step[1]" b:event="previous">Previous</b:button>
						<b:button b:icon="next" id="proceed" b:action="trigger" b:target="ancestor::b:step[1]" b:event="next">Next</b:button>
					    <s:execute>
					      	<s:task b:action="disable" b:target="id('proceed')" />
					    </s:execute>
					</b:panel>
				</b:panelset>
			</b:step>
			<b:step b:step="3" id="step3">
			   <b:panelset b:rows="29px * 29px">
					<b:panel style="background-color: #fff; padding: 0px; overflow:hidden;">
						<strong>STEP 3: Simply select the required command to execute from the left, and then click next.</strong>
					</b:panel>
					<b:panel style="background-color: #fff; padding: 0px;">
						 <b:panelset b:cols="260px *">
						 	<b:panel style="background-color: #fff; padding: 0px;">
						 		<b:box style="width: 100%;height: 98%;">
							 		<div id="second_form">
										<form id="command_detail" name="command_detail" method="post" b:destination="id('dataview')" action="data.php">
											<input type="hidden" name="process" value="cmddetail" />
											<input type="hidden" id="idtree" name="id" value="1" />
		 							 		<div id="mtree">Loading command task list... Please wait.</div>
										    <s:execute>
										      	<s:task b:action="load" b:url="data.php?process=cmdlist" b:destination="id('mtree')" b:mode="replace" />
										    </s:execute>
										</form>
									</div>
								</b:box>
						 	</b:panel>
						 	<b:panel style="background-color: #fff; padding-left: 5px">
								<div id="dataview" style="width: 100%;">
									<strong>Select an item from the tree for more detail.</strong>
									<input type="text" id="command_id" name="command_id" value="" b:required="true" style="border:none;" />
									<input type="text" id="command" name="command" value="" b:required="true" style="border:none;" />
									<input type="text" id="group_id" name="group_id" value="" b:required="true" style="border:none;" />
								</div>
						 	</b:panel>
						 </b:panelset>
				 	</b:panel>
				 	<b:panel style="background-color: #fff; padding: 0px; text-align: right;">
						<b:button b:icon="previous" b:action="trigger" b:target="ancestor::b:step[1]" b:event="previous">Previous</b:button>
						<b:button b:icon="next" b:action="trigger" b:target="ancestor::b:step[1]" b:event="next">Next</b:button>
					</b:panel>
				 </b:panelset>
			</b:step>
			<b:step b:step="4" id="step4">
			   <b:panelset b:rows="29px * 29px">
					<b:panel style="background-color: #fff; padding: 0px; overflow:hidden;">
						<strong>STEP 4: Now select the date and time you want the command to execute.</strong> Server time is <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%H:%M:%S") : smarty_modifier_date_format($_tmp, "%H:%M:%S")); ?>

					</b:panel>
					<b:panel style="background-color: #fff; padding: 0px;">
						<table width="100%"  border="0" cellspacing="0" cellpadding="0">
						  <tr>
						    <td height="35"><strong>Please select an hour (0-23):</strong></td>
						    <td>
								<input type="text" name="hour_exec" b:connect="id('hour_sel')" b:valid="number" style="width: 20px;border:none;" /> hour(s)
								<b:slider b:snap="true" id="hour_sel" b:value="<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%k") : smarty_modifier_date_format($_tmp, "%k")); ?>
">
								<?php unset($this->_sections['hour_sel']);
$this->_sections['hour_sel']['name'] = 'hour_sel';
$this->_sections['hour_sel']['start'] = (int)0;
$this->_sections['hour_sel']['loop'] = is_array($_loop=24) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['hour_sel']['step'] = ((int)1) == 0 ? 1 : (int)1;
$this->_sections['hour_sel']['show'] = true;
$this->_sections['hour_sel']['max'] = $this->_sections['hour_sel']['loop'];
if ($this->_sections['hour_sel']['start'] < 0)
    $this->_sections['hour_sel']['start'] = max($this->_sections['hour_sel']['step'] > 0 ? 0 : -1, $this->_sections['hour_sel']['loop'] + $this->_sections['hour_sel']['start']);
else
    $this->_sections['hour_sel']['start'] = min($this->_sections['hour_sel']['start'], $this->_sections['hour_sel']['step'] > 0 ? $this->_sections['hour_sel']['loop'] : $this->_sections['hour_sel']['loop']-1);
if ($this->_sections['hour_sel']['show']) {
    $this->_sections['hour_sel']['total'] = min(ceil(($this->_sections['hour_sel']['step'] > 0 ? $this->_sections['hour_sel']['loop'] - $this->_sections['hour_sel']['start'] : $this->_sections['hour_sel']['start']+1)/abs($this->_sections['hour_sel']['step'])), $this->_sections['hour_sel']['max']);
    if ($this->_sections['hour_sel']['total'] == 0)
        $this->_sections['hour_sel']['show'] = false;
} else
    $this->_sections['hour_sel']['total'] = 0;
if ($this->_sections['hour_sel']['show']):

            for ($this->_sections['hour_sel']['index'] = $this->_sections['hour_sel']['start'], $this->_sections['hour_sel']['iteration'] = 1;
                 $this->_sections['hour_sel']['iteration'] <= $this->_sections['hour_sel']['total'];
                 $this->_sections['hour_sel']['index'] += $this->_sections['hour_sel']['step'], $this->_sections['hour_sel']['iteration']++):
$this->_sections['hour_sel']['rownum'] = $this->_sections['hour_sel']['iteration'];
$this->_sections['hour_sel']['index_prev'] = $this->_sections['hour_sel']['index'] - $this->_sections['hour_sel']['step'];
$this->_sections['hour_sel']['index_next'] = $this->_sections['hour_sel']['index'] + $this->_sections['hour_sel']['step'];
$this->_sections['hour_sel']['first']      = ($this->_sections['hour_sel']['iteration'] == 1);
$this->_sections['hour_sel']['last']       = ($this->_sections['hour_sel']['iteration'] == $this->_sections['hour_sel']['total']);
?>
								  	<b:slider-option><?php echo $this->_sections['hour_sel']['index']; ?>
</b:slider-option>
								<?php endfor; endif; ?>
								</b:slider>
						    </td>
						  </tr>
						  <tr>
						    <td height="35"><strong>Please select a minute (0-59):</strong></td>
						    <td>
								<input type="text" name="min_exec" b:connect="id('min_sel')" b:valid="number" value="<?php echo smarty_function_math(array('equation' => "round(x,-1)",'x' => ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%M") : smarty_modifier_date_format($_tmp, "%M"))), $this);?>
" style="width: 20px;border:none;" /> minute(s)
								<b:slider b:snap="true" id="min_sel" b:value="<?php echo smarty_function_math(array('equation' => "round(x,-1)",'x' => ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%M") : smarty_modifier_date_format($_tmp, "%M"))), $this);?>
">
								<?php unset($this->_sections['min_sel']);
$this->_sections['min_sel']['name'] = 'min_sel';
$this->_sections['min_sel']['start'] = (int)0;
$this->_sections['min_sel']['loop'] = is_array($_loop=60) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['min_sel']['step'] = ((int)5) == 0 ? 1 : (int)5;
$this->_sections['min_sel']['show'] = true;
$this->_sections['min_sel']['max'] = $this->_sections['min_sel']['loop'];
if ($this->_sections['min_sel']['start'] < 0)
    $this->_sections['min_sel']['start'] = max($this->_sections['min_sel']['step'] > 0 ? 0 : -1, $this->_sections['min_sel']['loop'] + $this->_sections['min_sel']['start']);
else
    $this->_sections['min_sel']['start'] = min($this->_sections['min_sel']['start'], $this->_sections['min_sel']['step'] > 0 ? $this->_sections['min_sel']['loop'] : $this->_sections['min_sel']['loop']-1);
if ($this->_sections['min_sel']['show']) {
    $this->_sections['min_sel']['total'] = min(ceil(($this->_sections['min_sel']['step'] > 0 ? $this->_sections['min_sel']['loop'] - $this->_sections['min_sel']['start'] : $this->_sections['min_sel']['start']+1)/abs($this->_sections['min_sel']['step'])), $this->_sections['min_sel']['max']);
    if ($this->_sections['min_sel']['total'] == 0)
        $this->_sections['min_sel']['show'] = false;
} else
    $this->_sections['min_sel']['total'] = 0;
if ($this->_sections['min_sel']['show']):

            for ($this->_sections['min_sel']['index'] = $this->_sections['min_sel']['start'], $this->_sections['min_sel']['iteration'] = 1;
                 $this->_sections['min_sel']['iteration'] <= $this->_sections['min_sel']['total'];
                 $this->_sections['min_sel']['index'] += $this->_sections['min_sel']['step'], $this->_sections['min_sel']['iteration']++):
$this->_sections['min_sel']['rownum'] = $this->_sections['min_sel']['iteration'];
$this->_sections['min_sel']['index_prev'] = $this->_sections['min_sel']['index'] - $this->_sections['min_sel']['step'];
$this->_sections['min_sel']['index_next'] = $this->_sections['min_sel']['index'] + $this->_sections['min_sel']['step'];
$this->_sections['min_sel']['first']      = ($this->_sections['min_sel']['iteration'] == 1);
$this->_sections['min_sel']['last']       = ($this->_sections['min_sel']['iteration'] == $this->_sections['min_sel']['total']);
?>
								  	<b:slider-option><?php echo $this->_sections['min_sel']['index']; ?>
</b:slider-option>
								<?php endfor; endif; ?>
								</b:slider>
						    </td>
						  </tr>
						  <!--<tr>
						    <td height="35"><strong>Please select a date:</strong></td>
						    <td><input type="text" name="date_exec" b:required="true" value="<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d %m %Y") : smarty_modifier_date_format($_tmp, "%d %m %Y")); ?>
" /><b:datepicker b:type="float" b:input="../input[1]" b:format="dd MM yyyy" /></td>
						  </tr>-->
						  <tr>
						    <td valign="top"><strong>Or, select any recurring options:</strong></td>
						    <td>
						    	<b:box>
							    	<!--<table width="285"  border="0" cellspacing="0" cellpadding="0">
							    	<thead>
							    		<tr><td colspan="3">Command recurrence every (tick applicable):</td></tr>
							    	</thead>
							    	<tbody>
							    	<tr>
								    	<td><label><input type="checkbox" name="rec_minute" value="1" />minute</label></td>
								    	<td><label><input type="checkbox" name="rec_hour" value="1" />hour</label></td>
								    	<td><label><input type="checkbox" name="rec_day" value="1" />day</label></td>
							    	</tr>
								    <tr>
								    	<td><label><input type="checkbox" name="rec_month" value="1" />month</label></td>
								    	<td><label><input type="checkbox" name="rec_year" value="1" />year</label></td>
								    	<td></td>
								    </tr>
								    </tbody>
								    </table>-->
									<table width="285"  border="0" cellspacing="0" cellpadding="0">
							    	<thead>
							    		<tr><td colspan="3">Command recurrence every (tick applicable):</td></tr>
							    	</thead>
							    	<tbody>
							    	<tr>
								    	<td><input type="checkbox" name="rec_minute" value="1" /><label>minute</label></td>
								    	<td><input type="checkbox" name="rec_hour" value="1" /><label>hour</label></td>
							    	</tr>
							    	</tbody>
								    </table>
						    	</b:box>
						    </td>
						  </tr>

						</table>
				 	</b:panel>
				 	<b:panel style="background-color: #fff; padding: 0px; text-align: right;">
						<b:button b:icon="previous" b:action="trigger" b:target="ancestor::b:step[1]" b:event="previous">Previous</b:button>
						<b:button b:icon="next" b:action="trigger" b:target="ancestor::b:step[1]" b:event="next">Next</b:button>
					</b:panel>
				</b:panelset>
			</b:step>
			<b:step b:step="5" class="final-step">
			   <b:panelset b:rows="29px * 29px">
					<b:panel style="background-color: #fff; padding: 0px; overflow:hidden;">
						<strong>STEP 5: Here is the command overview:</strong>
					</b:panel>
					<b:panel style="background-color: #fff; padding: 0px;">
				 		<b:box>
					 		You accept by adding a command/task to this system that you have been given authorisation to perform the given command against the target host. You also understand that all contact details will be passed to the necessary authorities or owner(s) of the target host if used maliciously.<br />You also agree that we cannot be held liable for any damage or loss of business as a result of using this web tool.<br /><br />For further details or concerns about the use of this system, please contact pete@juicymedia.co.uk
					 		<br />
							<strong>Click "Add Command" to continue</strong>
							<p id="form_review"></p>
				 		</b:box>
				 	</b:panel>
				 	<b:panel style="background-color: #fff; padding: 0px; text-align: right;">
						<b:button b:icon="previous" b:action="trigger" b:target="ancestor::b:step[1]" b:event="previous">Previous</b:button>
						<b:button b:icon="next" b:onclick="proceed_cmd">Add Command</b:button>
					</b:panel>
				</b:panelset>
			</b:step>
		</form>
	</b:windowbody>
	<s:event b:on="close">
		<s:task b:action="remove" b:target="id('window_schedule')" />
	</s:event>
</b:window>