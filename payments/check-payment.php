<?php

require '../lib/FlowApi.class.php';

try {
    $params = array(
        'flowOrder' => 191387
    );

    $serviceName = 'payment/getStatusByFlowOrder';
    $flowApi = new FlowApi();
    $response = $flowApi->send($serviceName, $params, 'GET');
    $r = json_decode(json_encode($response), FALSE);

    print_r($r);
} catch (Exception $e) {
    echo 'Error: ' . $e->getCode() . ' - ' . $e->getMessage();
}
