<?php

include '../class/classMyDBC.php';
include '../class/classViaje.php';
include '../class/classParticipante.php';
include '../class/classExtra.php';
include '../class/classAccomodation.php';

if (extract($_POST)):
    $db = new myDBC();
    $v = new Viaje();
    $p = new Participante();
    $e = new Extra();
    $a = new Accomodation();

    $data = [];
    $data['data'] = $v->get($id, $db);
    $data['partic'] = $p->getByViaje($id, $db);
    $data['extras'] = $e->getByViaje($id, $db);
    $data['aloja'] = $a->getByTrip($id, $db);
    echo json_encode($data);
endif;
