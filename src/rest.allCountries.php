<?php
require '../vendor/autoload.php';
use GuzzleHttp\Client;

$client = new Client();
$response = $client->get('https://restcountries.eu/rest/v2/all');
$data = $response->getBody();
echo $data;