<?php
//--------------------------------------------------------------
// emcVOTE - EmerCoin voting system system
// Distributed under BSD license
// https://en.wikipedia.org/wiki/BSD_licenses
// Designed by olegarch, EmerCoin group
// WEB: http://www.emercoin.com
// Contact: team@emercoin.com


// error_reporting(E_ALL);
error_reporting(E_ERROR | E_PARSE);
//------------------------------------------------------------------------------

class emcVOTE {
  protected $config;
  protected $dbh;

  public function __construct() {
    // Return ERR 403 at pre-fetch attempt, do nothing
    if (
         (isset($_SERVER['HTTP_X_MOZ'])) && ($_SERVER['HTTP_X_MOZ'] == 'prefetch')
      || (isset($_SERVER['HTTP_X_PURPOSE'])) && ($_SERVER['HTTP_X_PURPOSE'] == 'preview')
    ) {
      // This is a prefetch request. Block it.
      header('HTTP/1.0 403 Forbidden');
      echo '403: Forbidden<br><br>Prefetching not allowed here.';
      die();
    }

    // Load config
    $this->config = require('vote-config.php');

    //Connecting to MySQL db
    $db_opt = array( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION );

    $this->dbh = new PDO(
      $this->config['db']['db_host'] . ";dbname=" . $this->config['db']['db_name'],
      $this->config['db']['db_user'],
      $this->config['db']['db_pass'],
      $db_opt);
    $this->dbh->query('SET NAMES "utf8"');
  } // constructor

  //------------------------------------------------------------------------------
  // Get functions for emcLNX adm-utils
  public function GetCoinfig() { return $this->config; }
  public function GetDbh()     { return $this->dbh;    }

  //------------------------------------------------------------------------------
  // Performs request to EMC wallet
  public function emcReq($cmd, $params) {
    // Prepares the request
    $request = json_encode(array(
      'method' => $cmd,
      'params' => $params,
      'id' => '1'
    ));
    // Prepare and performs the HTTP POST
    $opts = array (
        'http' => array (
                'method'  => 'POST',
                'header'  => 'Content-type: application/json',
                'content' => $request
        ),
        'ssl'  => $this->config['ssl']
    );
    $fp = fopen($this->config['wallet']['url'], 'rb', false, stream_context_create($opts));
    if(!$fp)
      throw new Exception('emcReq: Unable to connect to EMC-wallet');

    $rc = json_decode(stream_get_contents($fp), true);
    $er = $rc['error'];
    if(!is_null($er))
      throw new Exception('emcReq: Wallet response error: ' . $er);

    return $rc['result'];
  } // emcLNX__req

  //------------------------------------------------------------------------------

} // class emcVOTE

?>
