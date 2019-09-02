<?php

include '../class/classMyDBC.php';
include '../class/classParticipante.php';

if (extract($_POST)):
    $p = new Participante();
    echo json_encode($p->getByRutCotizacion($rut, $cot));
endif;
