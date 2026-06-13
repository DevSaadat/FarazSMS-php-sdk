<?php

use Devsaadat\Farazsms\FarazSMS;

require __DIR__ . '/../vendor/autoload.php';

$api_key = "your_api_key";
$id = 3540710;

try {
    $faraz = new FarazSMS($api_key);
    $status = $faraz->sendRequestDetails($id);
    var_dump($status);
} catch (Exception $e) {
    echo $e->getMessage();
}