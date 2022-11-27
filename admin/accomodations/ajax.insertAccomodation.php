<?php

include '../../class/classMyDBC.php';
include '../../class/classAccomodation.php';
include '../../src/sessionControl.ajax.php';
include '../../src/fn.php';
$_BASEDIR = explode('admin', dirname(__FILE__));

if (extract($_POST)):
    $db = new myDBC();
    $a = new Accomodation();

    try {
        $db->autoCommit(FALSE);
        $ins = $a->set($icity, $iname, $idetalle, $iaddr, $irooms, $ibaths, $ibeds1p, $ibeds2p, $ipool, $ibarb, $iurl, $db);

        if (!$ins['estado']):
            throw new Exception('Error al guardar los datos del alojamiento. ' . $ins['msg'], 0);
        endif;

        if (!empty($_FILES)):
            $targetFolder = 'dist/img/places/';
            $targetPath = $_BASEDIR[0] . $targetFolder;

            foreach ($_FILES as $aux => $file):
                $tempFile = $file['tmp_name'][0];
                $fileName = removeAccents(str_replace(' ', '_', $file['name'][0]));
                $targetFile = rtrim($targetPath, '/') . '/' . $ins['msg'] . '_' . $fileName;
                move_uploaded_file($tempFile, $targetFile);
                $pic_route = 'dist/img/places/' . $ins['msg'] . '_' . $fileName;
            endforeach;
        else:
            $pic_route = 'dist/img/places/no-photo.png';
        endif;

        $ins_p = $a->setPicture($ins['msg'], $pic_route, $db);

        if (!$ins_p['estado']):
            throw new Exception('Error al guardar la imagen. ' . $ins_p['msg'], 0);
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