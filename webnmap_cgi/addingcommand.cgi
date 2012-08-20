#!/usr/bin/perl -X

use DBI;
use CGI       qw (:standard);
use CGI::Carp qw (fatalsToBrowser);

require "config";
require "win.tpl";

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

$client=param ('clients');
$hours=param ('hours');
$mins=param ('mins');
$days=param ('days');
$monthes=param ('monthes');
$years=param ('years');
$command=param ('command');
$host=param ('host');

if ( ($hours<10) && ($hours ne "*") ) {
	$hours="0".$hours;
}

if ( ($mins<10) && ($mins ne "*") ) {
	$mins="0".$mins;
}

if ( ($monthes<10) && ($monthes ne "*") ) {
	$monthes="0".$monthes;
}

$dbh = DBI->connect("dbi:mysql:dbname=$db_name;host=$db_ip;port=3306", "$db_user", "$db_pass", { RaiseError => 0, AutoCommit => 1 }) || die "Cannot connect to database $db_name: $DBI::errstr";
if (!defined($dbh)) {
	die;
};

if ($host eq ""){
	$host = "localhost";
}

my $sql="SELECT * FROM command_list WHERE command_id='$command'";
my $sth = $dbh->prepare($sql);

if (defined($sth))
{
	$sth->execute();
	@row = $sth->fetchrow_array;
	$command_value = $row[2];
	$command_value =~ s/%%host%%/$host/gi;
	$sth->finish();
}

my $sql="INSERT INTO queue (command_id,command,client_id,hour,min,day,month,year,timestamp_added) VALUES ('$command','$command_value','$client','$hours','$mins','$days','$monthes','$years','$curdatetime')";

my $sth = $dbh->prepare($sql);
if (defined($sth)){
	$sth->execute();
	$sth->finish();
}

$dbh->disconnect if defined($dbh);

window_header("Command Queue Adding");
addrows("Command added succesfully!");
window_end();
