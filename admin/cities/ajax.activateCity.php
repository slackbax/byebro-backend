<?php

include '../../class/classMyDBC.php';
include '../../class/classCity.php';
include '../../src/sessionControl.ajax.php';

if (extract($_POST)):
    $db = new myDBC();
    $c = new City();

    try {
        $db->autoCommit(FALSE);
        $us = $c->setState($id, $type, TRUE, $db);

        if (!$us['estado']):
            throw new Exception('Error al activar la ciudad. ' . $us['msg'], 0);
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