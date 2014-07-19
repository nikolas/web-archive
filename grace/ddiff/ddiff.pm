package ddiff;

use strict;
use nginx;

sub handler {
	my $r = shift;
	$r->send_http_header("text/html");
	return OK if $r->header_only;

	my $site_path = '/ddiff/';

	my $s = '';
	$s .= '<!doctype html>
<html>
<head>
	<meta charset="utf-8">

	<title>ddiff</title>

	<link rel="stylesheet" href="'.$site_path.'blueprint/screen.css" type="text/css" media="screen, projection"> 
	<link rel="stylesheet" href="'.$site_path.'blueprint/print.css" type="text/css" media="print"> 
	<!--[if lt IE 8]>
	<link rel="stylesheet" href="'.$site_path.'blueprint/ie.css" type="text/css" media="screen, projection">
	<![endif]-->
	<style type="text/css">
	.container {
		text-align: center;
	}
	</style>
</head>
<body>
	<div class="container">
		<h1><a href="'.$site_path.'">ddiff</a></h1>
		<form method="post">
			<textarea></textarea>
			<textarea></textarea>
			<br />
			<input type="submit" value="create permadiff" />
		</form>
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
