<?php 
require __DIR__ . '/../../eio/vendor/autoload.php';

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


$test = '{"title":"New Ticket Has Been Created By SK_MrVOIP","text":null,"notification_for":"email_ticketing","click_action":"https:\/\/assaabloyccuat.mconnectapps.com\/#\/ticketing-system-new","unique_id":"##301","sound":"default","badge":"1","host_name":"https:\/\/assaabloyccuat.mconnectapps.com"}';


    $client = new Client(new Version1X($socket,$options));
    $client->initialize();
    //$client->emit("test", ["key"=> "val"]);
    $client->emit('broadcast', ["key"=> "val"]);
    //$client->close();