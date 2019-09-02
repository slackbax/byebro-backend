<?php

include '../class/classMyDBC.php';
include '../class/classViaje.php';
include '../class/classCotizacion.php';
include '../class/classCotizante.php';
include '../class/classUser.php';
include '../class/classParticipante.php';
include '../class/classExtra.php';
include '../src/fn.php';
include '../src/sessionControl.ajax.php';

if (extract($_POST)):
    $db = new myDBC();
    $vi = new Viaje();
    $co = new Cotizante();
    $cot = new Cotizacion();
    $user = new User();
    $par = new Participante();
    $ex = new Extra();

    try {
        $db->autoCommit(FALSE);

        /** Se actualiza el cotizante */
        $phone = '(' . $icod . ')' . $iphone;
        $act_co = $co->modViaje($coid, $iemail, $phone, $db);

        if ($act_co['estado'] == false):
            throw new Exception('Error al guardar los datos del cotizante. ' . $act_co['msg'], 0);
        endif;

        $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $length = 10;
        $vi_code = '';
        $max = mb_strlen($keyspace, '8bit') - 1;

        for ($i = 0; $i < $length; ++$i):
            $vi_code .= $keyspace[random_int(0, $max)];
        endfor;

        $t_d = explode(' hasta ', $idates);
        $f_ini = setDateBD($t_d[0]);
        $f_ter = setDateBD($t_d[1]);

        /** Se crea el viaje **/
        $ins_vi = $vi->set($iocity, $idcity, $ialoja, $iid, $vi_code, $icost, $f_ini, $f_ter, $db);

        if ($ins_vi['estado'] == false):
            throw new Exception('Error al guardar los datos del viaje. ' . $ins_vi['msg'], 0);
        endif;

        /** Se actualiza el estado de la cotización **/
        $mod_state = $cot->setLast($iid, $db);

        if ($mod_state['estado'] == false):
            throw new Exception('Error al modificar estados anteriores de la cotización. ' . $mod_state['msg'], 0);
        endif;

        $mod_state = $cot->setState(4, $iid, $db);

        if ($mod_state['estado'] == false):
            throw new Exception('Error al guardar el estado de la cotización. ' . $mod_state['msg'], 0);
        endif;

        /** Se crea el estado inicial del viaje **/
        $ins_state = $vi->setState(1, $ins_vi['msg'], $db);

        if ($ins_state['estado'] == false):
            throw new Exception('Error al guardar el estado del viaje. ' . $ins_state['msg'], 0);
        endif;

        /** Se crean los participantes **/
        foreach ($irutpart as $k => $v):
            if (isset($iidpart[$k])):
                $act_part = $par->mod($iidpart[$k], $v, $inamepart[$k], $ilnppart[$k], $ilnmpart[$k], $iedadpart[$k], $iemailpart[$k], $phone, true, $db);

                if ($act_part['estado'] == false):
                    throw new Exception('Error al guardar los datos del participante. ' . $act_part['msg'], 0);
                endif;
            else:
                $cargo = ($k == 0) ? true : false;
                $phone = '(' . $icodpart[$k] . ')' . $iphonepart[$k];

                $ins_part = $par->set($iid, $v, $inamepart[$k], $ilnppart[$k], $ilnmpart[$k], $iedadpart[$k], $iemailpart[$k], $phone, $cargo, false, true, $db);

                if ($ins_part['estado'] == false):
                    throw new Exception('Error al guardar los datos del participante. ' . $ins_part['msg'], 0);
                endif;
            endif;
        endforeach;

        /** Se crean los extras **/
        if (isset($iextra)):
            foreach ($iextra as $k => $v):
                $ins_extra = $vi->setExtra($ins_vi['msg'], $v, $icant[$k], $db);

                if ($ins_extra['estado'] == false):
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
