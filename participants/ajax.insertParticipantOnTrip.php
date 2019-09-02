<?php

include '../class/classMyDBC.php';
include '../class/classParticipante.php';
include '../class/classViaje.php';
include '../src/fn.php';
include '../src/sessionControl.ajax.php';

if (extract($_POST)):
    $db = new myDBC();
    $v = new Viaje();
    $par = new Participante();

    try {
        $db->autoCommit(FALSE);

        $via = $v->get($iidt);

        $phone = '(' . $icodpart . ')' . $iphonepart;
        $ins_part = $par->set($via->cot_id, $irutpart, $inamepart, $ilnppart, $ilnmpart, $iedadpart, $iemailpart, $phone, false, false, true, $db);

        if ($ins_part['estado'] == false):
            throw new Exception('Error al guardar los datos del participante. ' . $ins_part['msg'], 0);
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