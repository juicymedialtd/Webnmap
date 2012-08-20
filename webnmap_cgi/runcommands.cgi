#!/usr/bin/perl -X

use DBI;
require "/home/webnmap/public_html/webnmap_cgi/config";

#**********************************************************************
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

$dbh = DBI->connect("dbi:mysql:dbname=$db_name;host=$db_ip;port=3306", "$db_user", "$db_pass", { RaiseError => 0, AutoCommit => 1 }) || die "Cannot connect to database $db_name: $DBI::errstr";
if (!defined($dbh)) {
	die;
};

my $sql="SELECT * FROM queue WHERE status <> 5";
my $sth = $dbh->prepare($sql);

if (defined($sth))
{
	$sth->execute();
	while ( @row = $sth->fetchrow_array )
	{
		$id=  $row[0];
		$cid= $row[1];
		$c=   $row[2];
		$cnt= $row[4];
		$h=   $row[9];
		$m=   $row[10];
		$d=   $row[11];
		$mt=  $row[12];
		$y=   $row[13];

		$startit=0;
		if ( ($h eq $hour) || ($h eq "*") ) {$startit++;};
		if ( ($m eq $min) || ($m eq "*") ) {$startit++;};
		if ( ($d eq $day) || ($d eq "*") ) {$startit++;};
		if ( ($mt eq $month) || ($mt eq "*") ) {$startit++;};
		if ( ($y eq $year) || ($y eq "*") ) {$startit++;};

		if ($startit == 5){
			$cnt++;
			my $sqlu="UPDATE queue SET recurring_cnt='$cnt', timestamp_init='$curdatetime', status='2' WHERE id='$id'";

			my $sthu = $dbh->prepare($sqlu);
			if (defined($sthu)){
				$sthu->execute();
				$sthu->finish();
			}

			system("/home/dev/public/Test_Projects/webnmap_cgi/runit.cgi $id &");
			#print "/home/dev/public/Test_Projects/webnmap_cgi/runit.cgi $id &\n";
		}
	}
	$sth->finish();
}

$dbh->disconnect if defined($dbh);