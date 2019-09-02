<?php

include '../../class/classMyDBC.php';
include '../../class/classExtra.php';

if (extract($_POST)):
    $e = new Extra();
    echo json_encode($e->existsExtra($name));
endif;
