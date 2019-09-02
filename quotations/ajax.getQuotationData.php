<?php

include '../class/classMyDBC.php';
include '../class/classCotizacion.php';
include '../class/classParticipante.php';
include '../class/classExtra.php';

if (extract($_POST)):
    $db = new myDBC();
    $c = new Cotizacion();
    $p = new Participante();
    $e = new Extra();

    $data = [];
    $data['data'] = $c->get($id, $db);
    $data['partic'] = $p->getByCotizacion($id, $db);
    $data['extras'] = $e->getByCotizacion($id, $db);
    echo json_encode($data);
endif;
