<?php

include '../../class/classMyDBC.php';
include '../../class/classFood.php';

if (extract($_POST)):
    $f = new Food();
    echo json_encode($f->existsFood($name));
endif;
