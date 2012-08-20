#!/usr/bin/perl -w

$command="ping %%host%% -t";
$host="localhost";

$command =~ s/%%host%%/$host/gi;

print $command."\n";

