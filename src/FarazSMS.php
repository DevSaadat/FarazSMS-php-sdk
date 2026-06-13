<?php

namespace Devsaadat\Farazsms;

use Exception;

class FarazSMS
{
    private string $api_key;
    private string $base_uri = "api.iranpayamak.com";
    private string $from_number;

    /**
     * @throws Exception
     */
    public function __construct(string $api_key, ?string $from_number = null)
    {

        if(preg_match("/^[a-z0-9_-]+$/i", $api_key)) {
            $this->api_key = $api_key;
        } else {
            throw new \Exception("Invalid API key");
        }

        if($from_number) {
            if(is_numeric($from_number)) {
                $this->from_number = $from_number;
            } else {
                throw new \Exception("Invalid From number");
            }
        }
    }

    /**
     * @throws Exception
     */
    public function setFromNumber(string $from_number): void
    {
        if(is_numeric($from_number)) {
            $this->from_number = $from_number;
        } else {
            throw new \Exception("Invalid From number");
        }
    }

    /**
     * @throws Exception
     */
    private function request(string $path, array $data = []) : array
    {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://'. $this->base_uri . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Api-Key: ' . $this->api_key,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        if ($response === false) {
            $error = curl_error($curl);
            throw new Exception($error);
        }

        $decoded = json_decode($response, true);

        if (!is_array($decoded)) {
            throw new Exception('Invalid JSON response: ' . $response);
        }

        /*
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($httpCode >= 400) {
            throw new Exception(
                'API Error (' . $httpCode . '): ' . json_encode($decoded)
            );
        }*/

        return $decoded;

    }

    /**
     * @throws Exception
     */
    public function sendSimpleSMS(string $text, array|string $numbers, ?string $from_number = null): array
    {
        if(!isset($this->from_number) and !$from_number) {
            throw new Exception("Invalid From number");
        }

        if(!is_array($numbers)) $numbers = [$numbers];

        $data = [
            'text' => $text,
            'line_number' => $from_number ?? $this->from_number,
            'recipients' => $numbers,
            'number_format' => 'english'
        ];

        return $this->request('/ws/v1/sms/simple', $data);
    }

    /**
     * @throws Exception
     */
    public function sendByPattern(array|string $number, string $pattern, array $attributes, ?string $from_number = null): array
    {
        if(!isset($this->from_number) and !$from_number) {
            throw new Exception("Invalid From number");
        }

        $data = [
            'code' => $pattern,
            'line_number' => $from_number ?? $this->from_number,
            'attributes' => $attributes,
            'recipient' => $number,
            'number_format' => 'english'
        ];

        return $this->request('/ws/v1/sms/pattern', $data);
    }


}