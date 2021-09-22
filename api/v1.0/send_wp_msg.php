<?php

// Update the path below to your autoload.php,
// see https://getcomposer.org/doc/01-basic-usage.md
require __DIR__ . '/twilio-php-master/src/Twilio/autoload.php';

// Use the REST API Client to make requests to the Twilio REST API
use Twilio\Rest\Client;

// Find your Account Sid and Auth Token at twilio.com/console
// DANGER! This is insecure. See http://twil.io/secure
$sid    = "ACd2b8754af33c55da6e4ec1e00e429266";
$token  = "6d0000186df92bb6444f03cc7f22b655";
$twilio = new Client($sid, $token);

$message = $twilio->messages
                  ->create("whatsapp:+918489514086", // to
                           [
                               "from" => "whatsapp:+14155238886",
                               "body" => "Hello World !"
                           ]
                  );

print($message->sid);


