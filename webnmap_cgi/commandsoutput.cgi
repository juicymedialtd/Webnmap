#!/usr/bin/perl -X

use DBI;
use CGI       qw (:standard);
use CGI::Carp qw (fatalsToBrowser);

require "config";
require "win.tpl";

$id=param ('id');

#**********************************************************************
($sec,$min,$hour,$day,$month,$year,$day2)=localtime(time);

if ($hour<10) {
	$hour="0".$hour;
}

if ($min<10) {
	$min="0".$min;
}

if ($sec<10) {
	$sec="0".$sec;
}

if (++$month<10) {
	$month="0".$month;
}

$curdatetime=($year+1900)."-".$month."-".$day." ".$hour.":".$min.":".$sec;

$dbh = DBI->connect("dbi:mysql:dbname=$db_name;host=$db_ip;port=3306", "$db_user", "$db_pass", { RaiseError => 0, AutoCommit => 1 }) || die "Cannot connect to database $db_name: $DBI::errstr";
if (!defined($dbh)) {
	die;
};

window_header("Command Output View $id");
my $sql="SELECT queue.command,queue_output.output,queue_output.time_stampt FROM queue_output,queue where queue_output.queue_id='$id' and queue_output.queue_id=queue.id";
my $sth = $dbh->prepare($sql);

if (defined($sth))
{
	$sth->execute();
	$cvet="#F3F9FF";
	$output="<table border=0 cellpadding=4 cellspacing=1 bgcolor=#000000><tr align=center bgcolor=#B7CAFF><td>command</td><td>output</td><td>time_stamp</td></tr>";
	while ( @row = $sth->fetchrow_array ){
		$c=$row[0];
		$o=$row[1];
		$t=$row[2];
		$o =~ s!\n!<br>!sig;
		$output.="<tr><td bgcolor=$cvet>$c</td><td bgcolor=$cvet>$o</td><td bgcolor=$cvet>$t</td></tr>";
	}
	$sth->finish();
}

$output.="</table>";
addrows("$output");
window_end();

$dbh->disconnect if defined($dbh);

