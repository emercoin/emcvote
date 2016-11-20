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

$section  = "undef";
$camp = array();
$sel_qty  = 0;
$vote_qty = 0;

$addr_buf = array();

//------------------------------------------------------------------------------
function SendBallots() {
  global $vote, $cfg, $addr_buf, $camp, $dbh;
  if(empty($addr_buf) || $cfg['wallet']['balptx'] == 0)
    return;
return; // 
//  $txid = '7ce751694be7d53db19a99c0e55dee170e504289f246ba87edc5593391e37d3f'; // 3 dself
//  $txid = '5cb34c0cdf0dc02262ecb879544b68da9b94efe1009f0cd64fe4be3426d4eff7'; // oleg wallet - voted to 5ks AgAll
  $txid = $vote->emcReq('sendmany', array($cfg['wallet']['sendfrom'], $addr_buf));
  $addr_buf = array();
  $stmt = $dbh->prepare("Insert into Ballot (CampID, TXID) values (?, ?)");
  $stmt->execute(array($camp['ID'], $txid));
}

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

  if($section == '.Camp') {
    if(preg_match('/^(\w+)\s*=\s*(.+)\s*/', $line, $tok)) {
      $camp[$tok[1]] = $tok[2];
    }
  } 
  else
  if($section == '.Select') {
    $sel_qty++;
    $stmt = $dbh->prepare("Insert into CampTo (CampID, ToID, ToAddr, ToName, ToDesc) values(?,?,?,?,?)");
    if(preg_match('/^(\S+)\s*=\s*(.+)\s*/', $line, $tok)) {
      $rc = $vote->emcReq('name_show', array("vote:" . $tok[1]));
      $stmt->execute(array($camp['ID'], $sel_qty, $rc['address'], $rc['name'], $tok[2]));
    } else {    
      $rc = $vote->emcReq('name_show', array("vote:$line"));
      $stmt->execute(array($camp['ID'], $sel_qty, $rc['address'], $rc['name'], $rc['value']));
    }
  } 
  else
  if($section == '.Votes') {
    // Verify address
    $validres = $vote->emcReq('validateaddress', array($line));
    if($validres['isvalid']) {
      $vote_qty++;
      $addr_buf[$line] = 0.02;
      if(count($addr_buf) >= $cfg['wallet']['balptx'])
        SendBallots();
      
      $stmt = $dbh->prepare("Insert into Votes (CampID, TXID, WhoAddr, ToID) values(?,?,?,?)");
      $stmt->execute(array($camp['ID'], 'No_Vote', $line, 0));
    }
    else
      echo "ERROR: Invalid address in Votes: $line\n";
  } 
} // while

SendBallots();

echo "SelQty=$sel_qty; VoteQty=$vote_qty\n";
fclose($fn_in);

$stmt = $dbh->prepare("Insert into Camp (CampID, Start, Finish, Ballots, Descr) values(?,?,?,?,?)");
$stmt->execute(array($camp['ID'], $camp['Start'], $camp['Finish'], $vote_qty, $camp['Descr']));

$stmt = $dbh->prepare("Select TXID from Ballot where CampID=?");
$stmt->execute(array($camp['ID']));

foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $x) {
  echo $x['TXID'] . "\n";
}

/*
(
    [name] => vote:dev-01:AgAll
    [value] => Test candidate Against_All
    [txid] => 845511592194ee36670ef15bc4ec352105e81ff2b235d94c9281231a22d5c026
    [address] => EMHVNcwPtrVzvXoat2CJMor6UX6FrVz5kS
    [expires_in] => 17497
    [expires_at] => 209926
    [time] => 1476097834
)

*/

?>
