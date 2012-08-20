#!/usr/bin/perl -X

use DBI;
use CGI       qw (:standard);
use CGI::Carp qw (fatalsToBrowser);

require "config";
require "win.tpl";

$dbh = DBI->connect("dbi:mysql:dbname=$db_name;host=$db_ip;port=3306", "$db_user", "$db_pass", { RaiseError => 0, AutoCommit => 1 }) || die "Cannot connect to database $db_name: $DBI::errstr";
if (!defined($dbh)) {
	die;
};

my $sql="SELECT * FROM site_users ORDER BY name";
my $sth = $dbh->prepare($sql);

if (defined($sth))
{
	$sth->execute();
	$clients="<option value=\"0\">Select Client</option>";
	while ( @row = $sth->fetchrow_array )
	{
		$clients.="<option value=\"$row[0]\">$row[1]</option>";
	}
	$sth->finish();
}

my $sql="SELECT * FROM command_list ORDER BY command";
my $sth = $dbh->prepare($sql);

if (defined($sth))
{
	$sth->execute();
	$cmds="<option value=\"0\">Select Command</option>";
	while ( @row = $sth->fetchrow_array )
	{
		$cmds.="<option value=\"$row[0]\">$row[2]</option>";
	}
	$sth->finish();
}

$dbh->disconnect if defined($dbh);


window_header("Command Queue Add");
addrows("<form method=\"POST\" action=\"addingcommand.cgi\">");
addrows("Please Select Client");
addrows("<select name=\"clients\">$clients</select></td></tr>");
addrows("Command");
addrows("<select name=\"command\">$cmds</select> Host: <input type=\"text\" size=20 name=\"host\">");

addrows("<table width=100% bgcolor=$select_color><TR><TD>Select Time and Date of Event:</TD></TR></table>");

$hours="<option value=\"*\">Every hour</option>";
for ($i=0;$i<25;$i++){
	$hours.="<option value=\"$i\">$i</option>";
}

$mins="<option value=\"*\">Every minute</option>";
for ($i=1;$i<61;$i++){
	$mins.="<option value=\"$i\">$i</option>";
}

$days="<option value=\"*\">Every day</option>";
for ($i=1;$i<32;$i++){
	$days.="<option value=\"$i\">$i</option>";
}

$monthes="<option value=\"*\">Every month</option>";
for ($i=1;$i<13;$i++){
	$monthes.="<option value=\"$i\">$i</option>";
}

$years="<option value=\"*\">Every year</option>";
for ($i=2006;$i<2021;$i++){
	$years.="<option value=\"$i\">$i</option>";
}

addrows("<table width=100%><TR><TD nowrap><select name=\"hours\">$hours</select></TD><TD nowrap><select name=\"mins\">$mins</select></TD><TD nowrap><select name=\"days\">$days</select></TD><td nowrap><select name=\"monthes\">$monthes</select></TD><TD nowrap><select name=\"years\">$years</select></TD></TR></table>");

addrows("<center><input type=\"submit\" value=\"Add\"></center></form>");
window_end();
