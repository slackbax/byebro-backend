<?php

include '../../class/classMyDBC.php';
include '../../class/classCity.php';

if (extract($_POST)):
    $city = new City();
    echo json_encode($city->existsCity($name, $type));
endif;
