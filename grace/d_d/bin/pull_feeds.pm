#!/usr/bin/perl -w
use strict; use warnings;
my(@feeds) = (
	# hourly
	['http://packages.gentoo.org/feed/arch/amd64', 60 * 60]
	#['http://packages.gentoo.org/feed/arch/x86', 60 * 60]
);

# globals
use vars qw( $dbh );

# libraries
use XML::RSS::TimingBotDBI;
use DBI;

# connect to DB
$dbh = DBI->connect(
	q[DBI:mysql:] .
	qq[database=d_d;] .
	qq[host=localhost;] .
	qq[port=3306],
	'd_d',   # MySQL DB username
	'wh4tevr!',  # ...and password
	{ 'RaiseError' => 0, 'AutoCommit' => 1 }
) or die qq[Aborting!  Failed to connect to database: $DBI::errstr];

foreach (@feeds) {
   my($feed) = $_;

   # check for an entry in the db corresponding to this feed
   my($row) = ( $dbh->selectrow_array(<<__SQL__, undef, $feed->[0]) )[0];
SELECT feedurl FROM feeds WHERE feedurl = ?
__SQL__

   unless ($row) { # auto-create db entry for this feed if it doesn't exist
      $dbh->do(q[INSERT INTO feeds SET feedurl = ?], undef, $feed->[0])
   }

   # grab the feed and thbbbtave it
   getfeed(@$_);
}

sub getfeed {
   my($rssurl,$maxage) = @_;

   # initialize the RSS bot!
   my($rssbot) = XML::RSS::TimingBotDBI->new;
   $rssbot->rssagent_dbh($dbh);
   $rssbot->rssagent_table('feeds');
   $rssbot->maxAge($maxage) if $maxage;
   $rssbot->maxAge($maxage) if $maxage;

   # grab the RSS feed
   my($response) = $rssbot->get($rssurl);

   # check response code
   if ($response->code == 200) {
      # save RSS feed content if it was successfully retrieved
      my($sth) =
         $dbh->prepare(q[UPDATE feeds SET content = ? WHERE feedurl = ?])
         or die q[RSSBOT: Aborting!  Problem encountered with MySQL: ]
         . $DBI::errstr;

      $sth->execute($response->content, $rssurl)
         or die q[RSSBOT: Aborting!  Problem encountered with MySQL: ]
         . $DBI::errstr;

      $sth->finish();
      print qq[RSSBOT: RSS feed "$rssurl" freshly retrieved to database\n]
   }
   elsif ($response->code == 304) {
      print qq[RSSBOT: feed "$rssurl" already up to date.  No need to refresh\n]
   }
   else {
      # report the error and abort if there was a problem getting the feed
      die qq[RSSBOT: Aborting!  Problem accessing feed "$rssurl": ]
      . $response->status_line
   }

   # have the rss bot save it's RSS lookup history...
   # $rssbot->commit; #<-- only necessary if MySQL auto-commit is off

   # ...or die trying
   die q[RSSBOT: Aborting!  Problem encountered while working with MySQL: ]
     . $DBI::errstr if $DBI::errstr;

   # update OK
   print qq[RSSBOT: update OK at ${\ scalar localtime }\n];
}

# scram
exit;

# disconnect if not already disconnected
END { $dbh->disconnect() if defined $dbh }
