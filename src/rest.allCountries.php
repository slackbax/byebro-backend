<?php
require '../vendor/autoload.php';
use GuzzleHttp\Client;

$client = new Client();
$response = $client->get('https://restcountries.com/v2/all');
$data = $response->getBody();
echo $data;
