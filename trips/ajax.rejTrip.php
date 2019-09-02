<?php

include '../class/classMyDBC.php';
include '../class/classViaje.php';
include '../src/sessionControl.ajax.php';

if (extract($_POST)):
    $db = new myDBC();
    $via = new Viaje();

    try {
        $db->autoCommit(FALSE);

        $u_st = $via->setLast($id);

        if (!$u_st['estado']):
            throw new Exception('Error al actualizar el viaje. ' . $u_st['msg'], 0);
        endif;

        $v = $via->setState(4, $id, $db);

        if (!$v['estado']):
            throw new Exception('Error al actualizar el viaje. ' . $v['msg'], 0);
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