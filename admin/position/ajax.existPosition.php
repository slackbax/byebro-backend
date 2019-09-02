<?php

include '../../class/classMyDBC.php';
include '../../class/classPosition.php';

if (extract($_POST)):
    $position = new Position();
    echo json_encode($position->existsPosition($name));
endif;
