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
/*
    Insert into Votes   (CampID, TXID, WhoAddr, ToID) values (1, 'No_Vote', 'EQE1C2fJVZBVCbm6KkE8zPyib5iR9HdecP', 0);

    [vout] => Array
        (
            [0] => Array
                (
                    [value] => 1
                    [n] => 0
                    [scriptPubKey] => Array
                        (
                            [asm] => OP_DUP OP_HASH160 692041e20e3821e70af4436c854ad8db7c6de64d OP_EQUALVERIFY OP_CHECKSIG
                            [hex] => 76a914692041e20e3821e70af4436c854ad8db7c6de64d88ac
                            [reqSigs] => 1
                            [type] => pubkeyhash
                            [addresses] => Array
                                (
                                    [0] => ESjm61LyTWWU5FfvZAXCWzFtTVg4Y4vAWd
                                )

                        )

                )




  Create table Ballot (
        CampID  int NOT NULL default 0,
        TXID    char(64),
        primary key (CampID)
    );

listsinceblock ( "blockhash" target-confirmations includeWatchonly)
    Create table Votes (
        CampID  int NOT NULL default 0,
        TXID    char(64),
        WhoAddr char(35) NOT NULL,
        ToID    int NOT NULL default 0,
        Qty     int NOT NULL default 0,
        primary key (WhoAddr)
    );



    Insert into Camp    (CampID, Start, Finish, Ballots, Txt) values(1, '2016-01-01', '2016-01-01', 1, 'Develop Campaign');
    Insert into Ballot  (CampID, TXID) values (1, '8598ee1eb286d1cfab0ad0033169fcf21d10d3d805041339c957e214b0343f55');
    Insert into CampTo  (CampID, ToID, ToAddr) values (1, 1, 'EXsNNqW2pS4MRADtQF8PdZBEMdRgaM5q79');

(CampID, ToID, ToAddr) values (1, 1, 'EXsNNqW2pS4MRADtQF8PdZBEMdRgaM5q79');

    [account] => ""
    [address] => EXsNNqW2pS4MRADtQF8PdZBEMdRgaM5q79
    [category] => receive
    [amount] => 1.25
    [vout] => 0
    [confirmations] => 234
    [blockhash] => 3cf84b49f9d547cb10ce3bb0f1d9263dbe44c68b2a3606f3deaa1cfbfce2c544
    [blockindex] => 2
    [blocktime] => 1475787872
    [txid] => 31e3807f1895cb62c8941e4721f99096d36b0f30720c9ffbc022f4e4c8771b3e
    [walletconflicts] => Array
        (
        )

    [time] => 1475787868
    [timereceived] => 1475787868
*/



?>
