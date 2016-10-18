#!/usr/bin/php
<?php
//--------------------------------------------------------------
// emcVOTE - EmerCoin voting system system
// Distributed under BSD license
// https://en.wikipedia.org/wiki/BSD_licenses
// Designed by olegarch, EmerCoin group
// WEB: http://www.emercoin.com
// Contact: team@emercoin.com

require_once('./vote-core.php');

$vote = new emcVOTE();
$dbh = $vote->GetDbh();
$cfg = $vote->GetCoinfig();

//------------------------------------------------------------------------------
$candidates = array();
//------------------------------------------------------------------------------

$fn_in  = $argv[1];

if(empty($fn_in)) {
  echo "Run:\n\t $argv[0] in_file\n";
  exit;
}

$fh_in  = fopen($fn_in, "r");
if(!$fh_in) {
   echo "Unable open $fn_in for read\n";
   exit;
}

while (($line = fgets($fh_in)) !== false) {
  // process the line read.
  $line = trim($line);
  if(empty($line) || $line[0] == '#')
    continue;
  if($line[0] == '.') {
    $section = $line;
    continue;
  }

  if(preg_match('/^(\S+)\s*=\s*(.+)\s*/', $line, $tok)) {
    try {
      $rc = $vote->emcReq('name_show', array("Xvote:" . $tok[1]));
      echo "ERROR: name already exists: $tok[1]\n";
      print_r($rc);
      exit;
    }catch(Exception $ex) {
       $candidates[$tok[1]] = $tok[2];
       echo "OK available: $tok[1]\n";
    }
  }
} // while

foreach($candidates as $name => $value) {
  echo "Register: $name => $value\n";
  $txid = $vote->emcReq('name_new', array("vote:$name", $value, $cfg['wallet']['nvsdays']));
}

?>
