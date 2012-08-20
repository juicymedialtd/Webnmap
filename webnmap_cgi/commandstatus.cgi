#!/usr/bin/perl -X

use DBI;
use CGI       qw (:standard);
use CGI::Carp qw (fatalsToBrowser);

require "config";
require "win.tpl";

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

window_header("Command Queue View");
$view="<table border=0 cellpadding=4 cellspacing=1 bgcolor=#000000><tr align=center bgcolor=#B7CAFF><td>command</td><td>status</td><td>recurring_cnt</td><td>timestamp_added</td><td>timestamp_init</td><td>timestamp_done</td><td>client</td></tr>";
my $sql="SELECT queue.*,site_users.name,statuses.status FROM queue,site_users,statuses WHERE site_users.id=queue.client_id AND statuses.id=queue.status";

my $sth = $dbh->prepare($sql);

if (defined($sth))
{
	$sth->execute();
	$cvet="#F3F9FF";
	while ( @row = $sth->fetchrow_array )
	{
		$c=$row[2];
		$r=$row[4];
		$ta=$row[5];
		$ti=$row[6];
		$td=$row[7];
		$cl=$row[14];
		$st=$row[15];
		$view.="<tr><td bgcolor=$cvet><a href=commandsoutput.cgi?id=$row[0] target=_blank>$c</a></td>
		<td bgcolor=$cvet>$st</td>
		<td bgcolor=$cvet>$r</td>
		<td bgcolor=$cvet>$ta</td>
		<td bgcolor=$cvet>$ti</td>
		<td bgcolor=$cvet>$td</td>
		<td bgcolor=$cvet>$cl</td></tr>";
	}
	$sth->finish();
}

$view.="</table>";
addrows("$view");
window_end();

$dbh->disconnect if defined($dbh);