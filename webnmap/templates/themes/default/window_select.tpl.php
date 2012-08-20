<b:window id="window_selector" b:open="true" b:resize="none" b:windowbuttons="minimize"	style="left:40%;top:20%;width:290px;height:286px;">
		<b:windowhead>##NG_PLS_SEL##</b:windowhead>
		<b:windowbody b:innerstyle="background-image: url('gfx/bbabe.jpg')">

			<a style="position:absolute;left:65px;top:50px;">
				<s:event b:on="command">
					<s:task b:action="load" b:url="main.php?process=window&amp;id=window_pane_01" b:test="not(id('window_news'))" b:destination="id('windowarea')" b:mode="aslastchild" />
					<s:task b:action="trigger" b:event="open" b:target="id('window_news')" />
					<s:task b:action="focus" b:target="id('window_news')" />
				</s:event>
			News</a>
			<a style="position:absolute;left:65px;top:88px;">
				<s:event b:on="command">
					<s:task b:action="load" b:url="main.php?process=window&amp;id=window_pane_02" b:test="not(id('window_finance'))" b:destination="id('windowarea')" b:mode="aslastchild" />
					<s:task b:action="trigger" b:event="open" b:target="id('window_finance')" />
					<s:task b:action="focus" b:event="open" b:target="id('window_finance')" />
				</s:event>
			Finance</a>
			<a style="position:absolute;left:65px;top:127px;">
				<s:event b:on="command">
					<s:task b:action="load" b:url="main.php?process=window&amp;id=window_pane_03" b:test="not(id('window_weather'))" b:destination="id('windowarea')" b:mode="aslastchild" />
					<s:task b:action="trigger" b:event="open" b:target="id('window_weather')" />
					<s:task b:action="focus" b:event="open" b:target="id('window_weather')" />
				</s:event>
			Weather</a>
			<a style="position:absolute;left:65px;top:162px;">
				<s:event b:on="command">
					<s:task b:action="load" b:url="main.php?process=window&amp;id=window_pane_04" b:test="not(id('window_movie'))" b:destination="id('windowarea')" b:mode="aslastchild" />
					<s:task b:action="trigger" b:event="open" b:target="id('window_movie')" />
					<s:task b:action="focus" b:event="open" b:target="id('window_movie')" />
				</s:event>
			Movies</a>



		</b:windowbody>
</b:window>