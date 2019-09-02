<?php

include '../class/classMyDBC.php';
include '../class/classCotizacion.php';
include '../src/sessionControl.ajax.php';

if (extract($_POST)):
    $db = new myDBC();
    $cot = new Cotizacion();

    try {
        $db->autoCommit(FALSE);

        $c = $cot->setValue($id, $val, $db);

        if (!$c['estado']):
            throw new Exception('Error al valorizar la cotización. ' . $c['msg'], 0);
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
