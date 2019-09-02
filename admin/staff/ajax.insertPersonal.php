<?php

include '../../class/classMyDBC.php';
include '../../class/classPersonal.php';
include '../../src/fn.php';
include '../../src/sessionControl.ajax.php';

if (extract($_POST)):
    $db = new myDBC();
    $p = new Personal();

    try {
        $db->autoCommit(FALSE);
        $ins = $p->set($iname, $ilastnamep, $ilastnamem, $iemail, $iphone, $db);

        if ($ins['estado'] == false):
            throw new Exception('Error al guardar los datos del personal. ' . $ins['msg'], 0);
        endif;

        if (!empty($iusernameid)):
            $ins_us = $p->setUser($ins['msg'], $iusernameid, $db);

            if ($ins_us['estado'] == false):
                throw new Exception('Error al guardar el usuario del personal. ' . $ins_us['msg'], 0);
            endif;
        endif;

        foreach ($iid as $i => $val):
            if (!empty($val)):
                $ins_c = $p->setCity($ins['msg'], $val, $db);

                if (!$ins_c['estado']):
                    throw new Exception('Error al guardar los datos de las ciudades del personal. ' . $ins_c['msg'], 0);
                endif;
            endif;
        endforeach;

        /*
        if (!empty($_FILES)):
            $targetFolder = 'dist/img/users/';
            $targetPath = $_BASEDIR[0] . $targetFolder;

            foreach ($_FILES as $aux => $file):
                $tempFile = $file['tmp_name'][0];
                $fileName = removeAccents(str_replace(' ', '_', $file['name'][0]));
                $targetFile = rtrim($targetPath, '/') . '/' . $ins['msg'] . '_' . $fileName;
                move_uploaded_file($tempFile, $targetFile);
                $pic_route = 'users/' . $ins['msg'] . '_' . $fileName;
            endforeach;
        else:
            $pic_route = 'users/no-photo.png';
        endif;

        $ins_p = $user->setPicture($ins['msg'], $pic_route, $db);

        if (!$ins_p['estado']):
            throw new Exception('Error al guardar la imagen. ' . $ins_p['msg'], 0);
        endif;
        */

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
