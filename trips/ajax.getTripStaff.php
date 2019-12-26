<?php

include '../class/classMyDBC.php';
include '../class/classPersonal.php';

if (extract($_POST)):
    $db = new myDBC();
    $p = new Personal();

    echo json_encode($p->getByViaje($id));
endif;