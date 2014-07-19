package d_d;

use strict;

# globals
use vars qw( $dbh );

# libraries
use nginx;
use DBI;
use XML::RSS::Parser;

$dbh = DBI->connect(
	q[DBI:mysql:] .
	qq[database=d_d;] .
	qq[host=localhost;] .
	qq[port=3306],
	'd_d',   # MySQL DB username
	'wh4tevr!',  # ...and password
	{ 'RaiseError' => 0, 'AutoCommit' => 1 }
) or die qq[Aborting!  Failed to connect to database: $DBI::errstr];

my $sth = $dbh->prepare('SELECT content FROM feeds');
$sth->execute();
my $result = $sth->fetchrow_hashref();

# parse rss
my $p = XML::RSS::Parser->new;
#or die qq[could not create parser];

#my $feed = $p->parse_string($result);
#	or die qq[could not parse xml: $p->errstr];
#my $feed = $p->parse_uri('http://packages.gentoo.org/feed/arch/amd64');

sub handler {
	my $r = shift;
	$r->send_http_header("text/html");
	return OK if $r->header_only;

	my $site_path = '/d_d/';

#	my $feed = $_;
#	my $row = ( $dbh->selectrow_array(<<__SQL__, undef, $feed->[0]) )[0];
#SELECT content FROM feeds
#__SQL__


	#my $feed_item_count = $feed->item_count;
	#my $feed_item_count = 3;

	my $s = '';
	$s .= '<!doctype html>
<html>
<head>
	<meta charset="utf-8">

	<title>Gentoo packages d_d</title>

	<link rel="shortcut icon" href="resources/favicon.ico">
	<link rel="stylesheet" href="'.$site_path.'blueprint/screen.css" type="text/css" media="screen, projection"> 
	<link rel="stylesheet" href="'.$site_path.'blueprint/print.css" type="text/css" media="print"> 
	<!--[if lt IE 8]>
	<link rel="stylesheet" href="'.$site_path.'blueprint/ie.css" type="text/css" media="screen, projection">
	<![endif]-->
	<style type="text/css">
	body {
		background: url("resources/londnon.png");
		background-attachment: fixed;
		background-position: center;
	}
	a {
		text-decoration: none;
	}
	h1.header {
		text-align: left;
		margin: 0.5em;
	}
	.container {
		text-align: center;
	}
	</style>
</head>
<body>
	<div class="container">
		<h1 class="header"><a href="'.$site_path.'">Gentoo packages</a></h1>
		<pre>'.''.'</pre>
		<pre>'.$result->{content}.'</pre>
	</div>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script> 
</body>
</html>
';

	$r->print($s);
	$r->rflush;
 
	return OK;
}
 
1;
__END__
