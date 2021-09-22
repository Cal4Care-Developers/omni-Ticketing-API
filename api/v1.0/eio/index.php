<?php 

require __DIR__ . '/vendor/autoload.php';

use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X as Version1X;
$socket = "https://myscoket.mconnectapps.com:4027";
      $options = [
          'context' => [
              'ssl' => [
                  'verify_peer' => false,
                   'verify_peer_name' => false
              ]
          ]
      ];

    $client = new Client(new Version1X($socket,$options));
    $client->initialize();
    //$client->emit("test", ["key"=> "val"]);
    $client->emit('broadcast', ['type' => 'notification', 'text' => 'Hello There! Succesvs']);
    //$client->close();
?>