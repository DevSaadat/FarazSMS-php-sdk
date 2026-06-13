<?php

use Devsaadat\Farazsms\FarazSMS;

require __DIR__ . '/../vendor/autoload.php';

$api_key = "your_api_key";
$from_number = "3000505";
$phone_number = "09335447317";
$text = "تست";

try {
    $faraz = new FarazSMS($api_key);
    $send = $faraz->sendSimpleSMS($text, $phone_number, $from_number);
    var_dump($send);
} catch (Exception $e) {
    echo $e->getMessage();
}