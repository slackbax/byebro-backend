<?php

include '../class/classMyDBC.php';
include '../class/classCotizacion.php';
include '../class/classCotizante.php';
include '../class/classUser.php';
include '../class/classParticipante.php';
include '../class/classExtra.php';
include '../src/fn.php';
include '../src/sessionControl.ajax.php';

if (extract($_POST)):
    $db = new myDBC();
    $co = new Cotizante();
    $cot = new Cotizacion();
    $user = new User();
    $par = new Participante();
    $ex = new Extra();
    $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    try {
        $db->autoCommit(FALSE);

        /** Si el cotizante no es el usuario actual **/
        if (empty($iid)):
            $r = $co->getByRut($irut);

            /** Si el cotizante no está registrado **/
            if (is_null($r->co_id)):
                $length = 4;
                $psw = '';
                $max = mb_strlen($keyspace, '8bit') - 1;

                for ($i = 0; $i < $length; ++$i):
                    $psw .= $keyspace[random_int(0, $max)];
                endfor;

                $rut_san = str_replace('.', '', $irut);

                /** Se crea el usuario del cotizante **/
                $ins_user = $user->set(3, $iname, $ilastnamep, $ilastnamem, $iemail, $rut_san, $psw, $db);

                if (!$ins_user['estado']):
                    throw new Exception('Error al guardar los datos de usuario. ' . $ins_user['msg'], 0);
                endif;

                $phone = '(' . $icod . ')' . $iphone;
                /** Si crea el cotizante **/
                $ins_co = $co->set($ins_user['msg'], $irut, $iname, $ilastnamep, $ilastnamem, $iemail, $phone, $db);

                if (!$ins_co['estado']):
                    throw new Exception('Error al guardar los datos de cotizante. ' . $ins_co['msg'], 0);
                endif;

                $iid = $ins_co['msg'];

            /** Si el cotizante ya está registrado **/
            else:
                $iid = $r->co_id;
            endif;
        endif;

        $length = 10;
        $cot_code = '';
        $max = mb_strlen($keyspace, '8bit') - 1;

        for ($i = 0; $i < $length; ++$i):
            $cot_code .= $keyspace[random_int(0, $max)];
        endfor;

        $t_d = explode(' hasta ', $idates);
        $f_ini = setDateBD($t_d[0]);
        $f_ter = setDateBD($t_d[1]);

        /** Se crea la cotización **/
        $ins_cot = $cot->set($iocity, $idcity, $iid, $cot_code, $f_ini, $f_ter, $cot_code, $db);

        if (!$ins_cot['estado']):
            throw new Exception('Error al guardar los datos de la cotización. ' . $ins_cot['msg'], 0);
        endif;

        /** Se crea el estado inicial de la cotización **/
        $ins_state = $cot->setState(1, $ins_cot['msg'], $db);

        if (!$ins_state['estado']):
            throw new Exception('Error al guardar el estado de la cotización. ' . $ins_state['msg'], 0);
        endif;

        $length = 4;
        /** Se crean los participantes **/
        foreach ($irutpart as $k => $v):
            $rut_san = str_replace('.', '', $v);

            $existe_us = $user->existsUser($rut_san);
            if (!$existe_us['msg']):
                $psw = '';
                $max = mb_strlen($keyspace, '8bit') - 1;

                for ($i = 0; $i < $length; ++$i):
                  $psw .= $keyspace[random_int(0, $max)];
                endfor;

                /** Se crea el usuario del participante **/
                $ins_user = $user->set(4, $inamepart[$k], $ilnppart[$k], $ilnmpart[$k], $iemailpart[$k], $rut_san, $psw, $db);

                if (!$ins_user['estado']):
                  throw new Exception('Error al guardar los datos de usuario. ' . $ins_user['msg'], 0);
                endif;

                $part_user = $ins_user['msg'];
            else:
                $user_d = $user->getByUsername($rut_san);
                $part_user = $user_d->us_id;
            endif;

            $cargo = $k == 0;
            $phone = '(' . $icodpart[$k] . ')' . $iphonepart[$k];

            $ins_part = $par->set($ins_cot['msg'], $part_user, $v, $inamepart[$k], $ilnppart[$k], $ilnmpart[$k], $iedadpart[$k], $iemailpart[$k], $phone, $cargo, true, 0, $db);

            if (!$ins_part['estado']):
                throw new Exception('Error al guardar los datos del participante. ' . $ins_part['msg'], 0);
            endif;
        endforeach;

        /** Se crean los extras **/
        if (isset($iextra)):
            foreach ($iextra as $k => $v):
                $ins_extra = $cot->setExtra($ins_cot['msg'], $v, $icant[$k], $db);

                if (!$ins_extra['estado']):
                    throw new Exception('Error al guardar los extras de la cotización. ' . $ins_extra['msg'], 0);
                endif;
            endforeach;
        endif;

        $db->Commit();
        $db->autoCommit(TRUE);
        $response = array('type' => true, 'msg' => $cot_code);
        echo json_encode($response);
    } catch (Exception $e) {
        $db->Rollback();
        $db->autoCommit(TRUE);
        $response = array('type' => false, 'msg' => $e->getMessage(), 'code' => $e->getCode());
        echo json_encode($response);
    }
endif;
