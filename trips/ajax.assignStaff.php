<?php

include '../class/classMyDBC.php';
include '../class/classPersonal.php';
include '../src/fn.php';
include '../src/sessionControl.ajax.php';

if (extract($_POST)):
    $db = new myDBC();
    $p = new Personal();

    try {
        $db->autoCommit(FALSE);

        $chk = $p->getByTrip($iname, $id, $icargo, $db);

        if ($chk != '')
            throw new Exception('El staff seleccionado ya se encuentra asignado en el viaje con ese cargo.', 0);

        $ins = $p->setViaje($iname, $id, $icargo, $db);

        if (!$ins['estado']):
            throw new Exception('Error al asignar el staff al viaje. ' . $ins['msg'], 0);
        endif;

        $db->Commit();
        $db->autoCommit(TRUE);
        $response = array('type' => true, 'msg' => $stmt->insert_id);
        echo json_encode($response);
    } catch (Exception $e) {
        $db->Rollback();
        $db->autoCommit(TRUE);
        $response = array('type' => false, 'msg' => $e->getMessage(), 'code' => $e->getCode());
        echo json_encode($response);
    }
endif;
