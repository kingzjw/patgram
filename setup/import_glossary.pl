#!/usr/bin/perl

use 5.010;
use DBI;

open SOURCE, "<", "output.txt"
  or die "Cannot open the file: $!";

my $db = "patgram";
my $user = "admin";
my $password = "123456";
my $dbh = DBI->connect("DBI:mysql:database=$db;host=localhost", $user, $password, {'RaiseError'=>1});

open VERBFILE, ">", "../glossary/verb.txt"
  or die "Cannot open the file: $!";

say "Start...";

my $insertVerbSth = $dbh->prepare("INSERT INTO verb (value) VALUES (?);");
my $insertMeaningGroupsSth = $dbh->prepare("INSERT INTO meaning_group (name, pattern, meaning, verbId) VALUES (?, ?, ?, ?);");
my $insertExampleVerbSth = $dbh->prepare("INSERT INTO example_verb (value, meaningGroupId) VALUES (?, ?);");
my $insertExampleSentenceSth = $dbh->prepare("INSERT INTO example_sentence (value, meaningGroupId) VALUES (?, ?);");

my $sth = $dbh->prepare("SELECT MAX(verbId) AS verbId FROM verb;");
$sth->execute();
my $row = $sth->fetchrow_hashref();
my $numVerb = $row->{"verbId"};

$sth = $dbh->prepare("SELECT MAX(meaningGroupId) AS meaningGroupId FROM meaning_group;");
$sth->execute();
$row = $sth->fetchrow_hashref();
my $numMeaningGroup = $row->{"meaningGroupId"};

undef $/;
my $content = <SOURCE>;
my $first = 1;

$dbh->begin_work();

while($content =~ /'(?<verb>[\w_]*)'\s*=>\s*\[(?<desc>[\w\W]*?)\]/g){
    $numVerb++;
    say "Inserting ", $numVerb, ". ", $+{verb}, "...";

    my $verb = $+{verb};
    $verb =~ s/^\s+|\s+$//g;

    if($first == 0) {
        print VERBFILE " ";
    }
    else {
        $first = 0;
    }
    print VERBFILE $verb;

    $insertVerbSth->execute($verb);

    $desc = $+{desc};
    while($desc =~ /<vp>(?<vp>[\w\W]*?)<\/vp>[\s\x95]*<mg>(?<mg>[\w\W]*?)<\/mg>[\s\x95]*<m4mg>(?<m4mg>[\w\W]*?)<\/m4mg>[\s\x95]*<ev>(?<ev>[\w\W]*?)<\/ev>[\s\x95]*<es>(?<es>[\w\W]*?)<\/es>/g){
        $numMeaningGroup++;

        my $mg = $+{mg};
        my $vp = $+{vp};
        my $m4mg = $+{m4mg};
        my $ev = $+{ev};
        my $es = $+{es};

        $mg =~ s/^\s+|\s+$//g;
        $mg =~ s/[\x91\x92`]|(\\')/'/g;
        $vp =~ s/^\s+|\s+$//g;
        $vp =~ s/\x96/\-/g;
        $vp =~ s/(\\')/'/g;
        $m4mg =~ s/^\s+|\s+$//g;
        $m4mg =~ s/\x95/\-/g;

        $insertMeaningGroupsSth->execute($mg, $vp, $m4mg, $numVerb);

        while($ev =~ /(?<exampleVerb>[a-zA-z_]+)/g) {
            $insertExampleVerbSth->execute($+{exampleVerb}, $numMeaningGroup);
        }

        while($es =~ /(?<exampleSentence>[^\$]+)/g) {
            $exampleSentence = $+{exampleSentence};
            $exampleSentence =~ s/^\s+|\s+$|\x95//g;
            $exampleSentence =~ s/`|(\\')/'/g;
            $insertExampleSentenceSth->execute($exampleSentence, $numMeaningGroup);
        }
    }
}

say "Batch inserting...";
$dbh->commit();

close VERBFILE;
close SOURCE;

