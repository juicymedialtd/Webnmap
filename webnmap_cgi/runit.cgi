#!/usr/bin/perl -X

use DBI;
use Net::SMTP;

require "/home/webnmap/public_html/webnmap_cgi/config";
require "/home/webnmap/public_html/webnmap_cgi/subs.cgi";


$id=$ARGV[0];
$dbh = DBI->connect("dbi:mysql:dbname=$db_name;host=$db_ip;port=3306", "$db_user", "$db_pass", { RaiseError => 0, AutoCommit => 1 }) || die "Cannot connect to database $db_name: $DBI::errstr";
if (!defined($dbh)) {
	die;
};

my $sql="SELECT command_id, command, timestamp_added FROM queue WHERE id='$id'";
my $sth = $dbh->prepare($sql);

if (defined($sth))
{
	$sth->execute();
	@row = $sth->fetchrow_array;

	$cid=$row[0];
	$c=$row[1];
	$t=$row[2];
	$sth->finish();
}

# put ouput here
$rez=qx($c);


($sec,$min,$hour,$day,$month,$year)=localtime(time);

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

if ($day<10) {
	$day="0".$day;
}

$curdatetime=($year+1900)."-".$month."-".$day." ".$hour.":".$min.":".$sec;

my $sqlu="UPDATE queue SET status='3', timestamp_done='$curdatetime' WHERE id='$id'";
my $sthu = $dbh->prepare($sqlu);
if (defined($sthu)){
	$sthu->execute();
	$sthu->finish();
}

if (length($rez)>0){
	my $sqlu="INSERT INTO queue_output (queue_id,output) VALUES ('$id','$rez')";
	my $sthu = $dbh->prepare($sqlu);
	if (defined($sthu)){
		$sthu->execute();
		$sthu->finish();
	}
}

my $sql="SELECT site_users.name, site_users.email FROM site_users, queue WHERE queue.id='$id' AND queue.client_id=site_users.id";
my $sth = $dbh->prepare($sql);

if (defined($sth))
{
	$sth->execute();
	@row = $sth->fetchrow_array;

	$n=$row[0];
	$e=$row[1];

	$sth->finish();
}

$subject="Command executed";
$body="$rez";
sendemail("\"$n\" <$e>",$subject,$body);

$dbh->disconnect if defined($dbh);


