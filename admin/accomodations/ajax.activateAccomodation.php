<?php

include '../../class/classMyDBC.php';
include '../../class/classAccomodation.php';
include '../../src/sessionControl.ajax.php';

if (extract($_POST)):
    $db = new myDBC();
    $a = new Accomodation();

    try {
        $db->autoCommit(FALSE);
        $us = $a->setState($id, TRUE, $db);

        if (!$us['estado']):
            throw new Exception('Error al activar el alojamiento. ' . $us['msg'], 0);
        endif;

        $db->Commit();
        $db->autoCommit(TRUE);
        $response = array('type' => true, 'msg' => 'OK');
        echo json_encode($response);
    } catch (Exception $e) {
        $db->Rollback();
        $db->autoCommit(TRUE);
        $response = array('type' => false, 'msg' => $e->getMessage(), 'code' => $e->getCode());
        echo json_encode($response);
    }
endif;