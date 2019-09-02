<?php

include '../class/classMyDBC.php';
include '../class/classCotizante.php';

if (extract($_POST)):
    $c = new Cotizante();
    echo json_encode($c->getByUser($id));
endif;
