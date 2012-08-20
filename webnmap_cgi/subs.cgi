

sub sendemail
{
	my ($t,$s,$b) = @_;

	 $data{from}="noreply\@webnmap.co.uk";
	 $value = $t;
	 $value =~ s/\@/\\@/g;
	 $data{to}=$value;
	 $data{subject}=$s;
	 $data{text}=$b;

	$smtp = Net::SMTP->new("mail.juicymedia.co.uk", Timeout => 60, Debug => 1,);

	$smtp->mail($data{to});
	$smtp->recipient($data{to});
	$smtp->data;
	$smtp->datasend("From: ".$data{from}."\n");
	$smtp->datasend("To: ".$data{to}."\n");
	$smtp->datasend("Subject: " .$data{subject}."\n");
	$smtp->datasend("\n");
	$smtp->datasend($data{text});
	#$smtp->datasend("Operation Complete...");
	$smtp->dataend;
	$smtp->quit;


};
return "true";

