<?php

include '../class/classMyDBC.php';
include '../class/classAccomodation.php';

if (extract($_POST)):
    $a = new Accomodation();
    echo json_encode($a->get($id));
endif;
