<?php

use Devsaadat\Farazsms\FarazSMS;

require __DIR__ . '/../vendor/autoload.php';

$api_key = "your_api_key";
$from_number = "3000505";
$phone_number = "09335447317";
$pattern = "glz00TCv6X";

try {
    $faraz = new FarazSMS($api_key);
    $send = $faraz->sendByPattern($phone_number, $pattern, ['code' => "123456"], $from_number);
    var_dump($send);
} catch (Exception $e) {
    echo $e->getMessage();
}