<?php

session_start();
include '../../class/classMyDBC.php';
include '../../class/classPosition.php';
include '../../src/fn.php';
include '../../src/sessionControl.ajax.php';

if (extract($_POST)):
    $db = new myDBC();
    $pos = new Position();

    try {
        $db->autoCommit(FALSE);

        $ins = $pos->mod($id, $iname, $idetalle, $db);

        if (!$ins['estado']):
            throw new Exception('Error al guardar los datos del cargo. ' . $ins['msg'], 0);
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
