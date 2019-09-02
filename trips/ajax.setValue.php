<?php

include '../class/classMyDBC.php';
include '../class/classViaje.php';
include '../src/sessionControl.ajax.php';

if (extract($_POST)):
    $db = new myDBC();
    $via = new Viaje();

    try {
        $db->autoCommit(FALSE);

        $v = $via->setValue($id, $val, $db);

        if (!$v['estado']):
            throw new Exception('Error al revalorizar el viaje. ' . $v['msg'], 0);
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
