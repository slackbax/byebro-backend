<?php

include '../class/classMyDBC.php';
include '../class/classViaje.php';
include '../class/classPersonal.php';

if (extract($_POST)):
    $db = new myDBC();
    $v = new Viaje();
    $p = new Personal();

    $vi = $v->get($id);
    echo json_encode($p->getByCity($vi->cid_id));
endif;