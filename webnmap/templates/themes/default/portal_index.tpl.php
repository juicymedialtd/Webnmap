<div style="position:relative;margin:0 auto;text-align:left;width:760px;" xmlns="http://www.w3.org/1999/xhtml" xmlns:b="http://www.backbase.com/b" xmlns:s="http://www.backbase.com/s">
	<!-- Include the portal specific behaviors, scripts and styles -->

	{literal}
	<!-- portlet container ("window") -->
	<s:htmlstructure b:name="b:portlet">
		<div>
			<div class="b-portlet-outer">
				<div class="b-portlet-inner">
					<s:innercontent/>
				</div>
			</div>
		</div>
	</s:htmlstructure>

	<s:behavior b:name="portlet">
		<s:state b:on="deselect" b:normal="b-portlet-outer2" />
		<s:state b:on="select" b:normal="b-portlet-outer2 b-portlet-sel" />

		<s:event b:on="construct">
			<s:setatt b:drag="portal" b:state="selected"/>
			<s:task b:action="trigger" b:event="init" />
		</s:event>
		<s:event b:on="init">
			<s:choose>
				<s:when b:test="@b:open='false'">
					<!-- portlet is currently closed -->
					<s:task b:action="trigger" b:event="close"/>
				</s:when>
				<s:otherwise>
					<!-- assume portlet is open by default -->
					<s:task b:action="trigger" b:event="open"/>
				</s:otherwise>
			</s:choose>
		</s:event>
		<s:event b:on="close">
			<s:setatt b:open="false"/>
			<!-- hide portlet -->
			<s:task b:action="hide"/>
		</s:event>

		<s:event b:on="open">
			<s:setatt b:open="true"/>
			<!-- show & select portlet -->
			<s:task b:action="show"/>
			<s:task b:action="select"/>
			<!-- toggle column display to fix rendering bugs -->
			<s:task b:action="hide" b:target="../.."/>
			<s:task b:action="show" b:target="../.."/>
		</s:event>

	</s:behavior>

	<s:htmlstructure b:name="b:portlet-head">
		<div>
			<div class="b-portlet-head-inner">
				<s:innercontent/>
			</div>
		</div>
	</s:htmlstructure>

	<s:behavior b:name="portlet-head">
		<s:state b:on="deselect" b:normal="b-portlet-head-outer"/>
		<s:event b:on="construct">
			<s:render b:destination="." b:mode="asfirstchild">
				<s:textnode b:label="{@b:caption}"/>
			</s:render>
			<s:render b:destination="." b:mode="aslastchild">
				<div class="portlet-head-controls">
					<div b:behavior="portlet-control-shade" b:tooltiptext="Toggle shade"></div>
					<div b:behavior="portlet-control-close" b:tooltiptext="Close portlet"></div>
				</div>
			</s:render>
		</s:event>
	</s:behavior>

	<s:htmlstructure b:name="b:portlet-body">
		<div>
			<div class="b-portlet-body-inner">
				<s:innercontent/>
			</div>
		</div>
	</s:htmlstructure>

	<s:behavior b:name="portlet-body">
		<s:state b:on="deselect" b:normal="b-portlet-body-outer b-portlet-body-outer-sel" />
		<s:state b:on="select" b:normal="b-portlet-body-outer" />
	</s:behavior>

	<s:default b:attribute="b:behavior" b:value="portlet" b:tag="b:portlet" />
	<s:default b:attribute="b:behavior" b:value="portlet-head" b:tag="b:portlet-head" />
	<s:default b:attribute="b:behavior" b:value="portlet-body" b:tag="b:portlet-body" />

	<!-- portlet head controls -->
	<s:behavior b:name="portlet-control">
		<s:event b:on="mouseenter" b:action="addclass" b:value="b-portlet-control-hov" />
		<s:event b:on="mouseleave" b:action="removeclass" b:value="b-portlet-control-hov" />
		<s:event b:on="mousedown" b:action="addclass" b:value="b-portlet-control-pres" />
		<s:event b:on="mouseup" b:action="removeclass" b:value="b-portlet-control-pres" />
	</s:behavior>

	<s:behavior b:name="portlet-control-shade" b:behavior="portlet-control">
		<s:event b:on="construct">
			<s:task b:action="addclass" b:value="b-portlet-control" />
			<s:task b:action="addclass" b:value="b-portlet-control-shade" />
			<s:super/>
		</s:event>
		<!-- select/deselect main portlet widget -->
		<s:event b:on="command" b:action="select-deselect" b:target="../../.." />
	</s:behavior>

	<s:behavior b:name="portlet-control-close" b:behavior="portlet-control">
		<s:event b:on="construct">
			<s:task b:action="addclass" b:value="b-portlet-control b-portlet-control-close"/>
			<s:super/>
		</s:event>
		<!-- close button action -->
		<s:event b:on="command" b:action="trigger" b:event="close" b:target="../../.." />
	</s:behavior>
	{/literal}

	{include file="portal_header.tpl.php"}
	{include file="portal_main.tpl.php"}
</div>