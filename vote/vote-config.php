<?php
return [

  // as specified in your emercoin.conf configuration file
  'wallet' => [
    'url'       => "http://emccoinrpc:hidden_pass_here@127.0.0.1:6662/",
    'account'   => "vote",
    'sendfrom'	=> "Ballots",
    'minconf'	=> 1,
    'balptx'	=> 100,
    'nvsdays'	=> 100
  ],

  // Wallet SSL connection params, if used https above
  'ssl'     => [
    // 'cafile'         => ""
    'verify_peer'       => false,
    'verify_peer_name'  => false
  ],

  // MySQL db connection params
  'db'     => [
    'db_host'   => "mysql:host=localhost",
    'db_user'   => "vote",
    'db_pass'   => "hidden_pass_here",
    'db_name'   => "vote"
  ]

]; // return config

?>

