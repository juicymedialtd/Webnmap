#!/usr/bin/perl -w
#----------------------------------------------------------
# Syntax check for PERL programs in current directory
# Prints diagnostic output formatted as CGI Response
#----------------------------------------------------------
#
use strict;
use CGI;
#
#-----------------------------------------------------
# MAIN
#-----------------------------------------------------
sub perlmain($)
{
my $q;
my $linebuf="";
my $thisprog="";
my $errlist="";
$q = new CGI;
print $q->header ("text/html");
print $q->start_html( -title => "Check CGI program syntax" );
### Invoke LINUX "ls" for all perl files in this directory
### Redirect its output to PERL filehandle
open MAP1,("ls *.cgi |");
while (<MAP1>)
{
$linebuf = $_;
chomp $linebuf;
$thisprog = $linebuf;
print "<b>checking $thisprog:</b>\n";
$errlist = `/usr/bin/perl -cw $thisprog 2>&1`;
print "$errlist\n";
print "<br>\n";
}
close MAP1;
print $q->end_html;
}
my $globex = perlmain(1);
exit 0;