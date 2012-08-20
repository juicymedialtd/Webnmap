print "Content-type: text/html; charset=windows-1251\n\n";
print qq~
<style type="text/css">
<!--
body {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 9px;
	color: #000000;

}

A:link    { color: #000000; text-decoration:underline }    /* unvisited links */
A:visited { color: #000000; text-decoration:underline }   /* visited links   */
A:hover   { text-decoration:none } /* user hovers     */
A:active  { text-decoration:none }   /* active links    */
A.mail:link    { color: #FFFFCC; text-decoration:underline }    /* unvisited links */
A.mail:visited { color: #FFFFCC; text-decoration:underline }   /* visited links   */

.table_fon {background:#99CCFF}
.tr_fon {background:#D7E8FF}
.menu {background:#336699}
file:///var/www/cgi-bin/alertinform/start.cgi
.zag {font-family: "Arial",Arial; font-style: mono; font-weight: normal; font-size: 10pt;  color: #000000;}
td {font-family: "Arial",Arial; font-style: mono; font-weight: normal; font-size: 10pt;  color: #000000;}

.podfon {background:#003399;font-family: "Arial",Arial; font-style: mono; font-weight: normal; font-size: 9pt; color: #FFFFCC}
.bezfon {font-family: "Arial",Arial; font-style: mono; font-weight: normal; font-size: 9pt; color: #FFFFCC}

-->
</style>
~;
sub window_header
{
print qq~
<table border=0 cellpadding=0 cellspacing=0>
<tr>
<td colspan=2>

<table class=table_fon border=0 cellpadding=0 cellspacing=0>
<tr>
<td class=zag>&nbsp;<b>@_</b>&nbsp;</td>
</tr>
</table>

</td>
</tr>
<tr>
<td>
<table class=table_fon align=center cellpadding=2 cellspacing=2>
<tr class=tr_fon><td>
<table class=table_fon align=center cellpadding=3 cellspacing=0>
~;
}


sub addrows
{
print qq~
<tr>
<td>@_</td>
</tr>
~;
}


sub window_end
{
print qq~
</table>

</td></tr>
</table>

</td></tr>
</table>
~;
}
return true;
