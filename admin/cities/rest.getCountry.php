<?php
require '../../vendor/autoload.php';
use GuzzleHttp\Client;
extract($_POST);

$client = new Client();
$response = $client->get('https://restcountries.com/v2/alpha/' . $code);
$data = $response->json();
