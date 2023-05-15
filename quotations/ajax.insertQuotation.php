<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include '../class/classMyDBC.php';
include '../class/classCotizacion.php';
include '../class/classCotizante.php';
include '../class/classUser.php';
include '../class/classParticipante.php';
include '../class/classExtra.php';
include '../src/fn.php';
include '../src/sessionControl.ajax.php';

$mail = new PHPMailer();
$mail->SMTPDebug = 0;
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = MAIL_USERNAME;
$mail->Password = MAIL_PASSWORD;
$mail->SMTPSecure = 'tls';
$mail->Port = 587;
$mail->isHTML(true);
$mail->Subject = 'Gracias por elegir a Bye Company para tu viaje!';

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
                    throw new Exception('Error al guardar los datos de usuario cotizante. ' . $ins_user['msg'], 0);
                endif;

                $phone = '(' . $icod . ')' . $iphone;
                /** Si crea el cotizante **/
                $ins_co = $co->set($ins_user['msg'], $irut, $iname, $ilastnamep, $ilastnamem, $iemail, $phone, $db);

                if (!$ins_co['estado']):
                    throw new Exception('Error al guardar los datos de cotizante cotizante. ' . $ins_co['msg'], 0);
                endif;

                $iid = $ins_co['msg'];
                $user_cotizante = $rut_san;

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

            if ($user_cotizante != $rut_san):
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

            $mail->setFrom(MAIL_USERNAME, 'Plataforma Bye Company');
            $mail->addAddress($iemailpart[$k], $iemailpart[$k]);
            $mail->Body = 'Estimado/a ' . $inamepart[$k] . ',<br><br>' .
              'Gracias por elegir a <strong>Bye Company</strong> como proveedor de tu próximo viaje. Esperamos brindarte una experiencia única.<br>
Te hemos creado una cuenta en nuestro sitio web, donde podrás encontrar toda la información sobre tu viaje, incluyendo invitados, items incluidos y extras para adquirir.<br><br>' .
              '<strong>Datos de ingreso</strong><br>' .
              'URL: <a href="https://www.byebro.cl/mis-viajes" target="_blank">https://www.byebro.cl/mis-viajes</a><br>' .
              '<i>(Se recomienda utilizar tu PC/Mac para entrar a la plataforma, aún en versión BETA)</i><br>' .
              'Usuario: '. $rut_san .'<br>Contraseña: ' . $psw . '<br><br>' .
              'Estamos siempre a tu disposición para cualquier consulta o necesidad. Esperamos verte pronto!<br><br>' .
              'Atentamente,<br>' .
              '<strong>El equipo de Bye Company</strong>';

            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            if (!$mail->send())
                error_log('Error al enviar el correo de notificación. ' . $mail->ErrorInfo);
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
