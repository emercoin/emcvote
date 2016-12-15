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

$minconf = $cfg['wallet']['minconf'];
$ballot = array();

//------------------------------------------------------------------------------
// Fetch entire Ballot table
/*
$q = "Select Ballot.TXID, Ballot.CampID from Ballot, Camp" .
     " where Ballot.CampID=Camp.CampID" .
     " and NOW() > Camp.Start - INTERVAL 1 day and NOW() < Camp.Finish + INTERVAL 1 day";
*/
$q =  "Select Ballot.TXID, Ballot.CampID from Ballot";

$stmt = $dbh->prepare($q);
$stmt->execute(array());
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $x) {
  $ballot[$x['TXID']] = $x['CampID'];
}

// Fetch last block hash
$stmt = $dbh->prepare("Select LastB from Wallet");
$stmt->execute(array());
$lb_row = $stmt->fetchAll(PDO::FETCH_ASSOC);
$lastB = $lb_row[0]['LastB'];

$lsb = $vote->emcReq('listsinceblock', array($lastB, $minconf));

// Process new transactions

$txqty = array();
foreach($lsb['transactions'] as $tx) 
  if($tx["category"] == "receive")
    $txqty[$tx['txid']]++;

foreach($lsb['transactions'] as $tx) {
  if($tx['confirmations'] < $minconf || $txqty[$tx['txid']] != 1 || $tx["category"] != "receive")
    continue;
  $ts = $tx['timereceived'];
  $q = "Select CampTo.CampID, CampTo.ToID from CampTo, Camp" .
       " where CampTo.ToAddr=? AND CampTo.CampID=Camp.CampID" . 
       " AND FROM_UNIXTIME(?) > Camp.Start AND FROM_UNIXTIME(?) < Camp.Finish";
  $stmt = $dbh->prepare($q);
  $stmt->execute(array($tx['address'], $ts, $ts));
  $res_row = $stmt->fetchAll(PDO::FETCH_ASSOC);
  if(empty($res_row))
    continue; // address is not belong to any campaign or out of time

  // Decode voting transaction
  $tx_dec = $vote->emcReq('getrawtransaction', array($tx['txid'], 1));

  // Iterate inputs and search for a ballots
  foreach($tx_dec['vin'] as $vin) {
     if($ballot[$vin['txid']] != $res_row[0]['CampID'])
       continue; // Invalid ballot
     $tx_bal_dec = $vote->emcReq('getrawtransaction', array($vin['txid'], 1));
     $who = $tx_bal_dec['vout'][$vin['vout']]['scriptPubKey']['addresses'][0];

     $stmt = $dbh->prepare("Update Votes Set TXID=?, ToID=? where WhoAddr=? AND CampID=?");
     $stmt->execute(array($tx['txid'], $res_row[0]['ToID'], $who, $res_row[0]['CampID']));
  } // Iterate VINs

} // foreach TXes

$stmt = $dbh->prepare("Update Wallet Set LastB=?");
$stmt->execute(array($lsb['lastblock']));


//------------------------------------------------------------------------------

?>
