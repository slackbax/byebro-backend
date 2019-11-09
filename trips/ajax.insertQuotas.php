<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include '../class/classMyDBC.php';
include '../class/classViaje.php';
include '../class/classParticipante.php';
include '../src/sessionControl.ajax.php';

if (extract($_POST)):
    $db = new myDBC();
    $par = new Participante();
    $via = new Viaje();
    $mail = new PHPMailer(true);

    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'contacto@byebro.com';
    $mail->Password = 'maosmaos';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->isHTML(true);
    $mail->Subject = 'Contacto deste sitio ByeBro.com';

    $mail->setFrom('contacto@byebro.com', 'Contacto ByeBro.com');

    try {
        $db->autoCommit(FALSE);

        $keyspace = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $length = 16;
        $max = mb_strlen($keyspace, '8bit') - 1;

        foreach ($ipart as $k => $v):
            $str = '';
            $p = $par->get($v);

            for ($i = 0; $i < $length; ++$i):
                $str .= $keyspace[random_int(0, $max)];
            endfor;

            $val = str_replace('.', '', $iamount[$k]);

            $ins = $par->setQuota($v, $val, $str, $db);

            if (!$ins['estado']):
                throw new Exception('Error al establecer la cuota. ' . $ins['msg'], 0);
            endif;

            $mail->addAddress($p->par_email, $p->par_email);
            $tmp = explode(' ', $p->par_nombres);
            $subname = $tmp[0];
            $mail->Body = utf8_decode('Hola ' . $subname . ',<br><br>' .
                'Tu cuota para el viaje ya está lista!<br>' .
                'Puedes acceder a pagar desde el siguiente enlace:<br><br>' .
                '<strong>https://www.byebro.com/pagos/?pgid=' . $str . '</strong><br><br>' .
                'Cualquier cosa, y si no estás seguro de que nos dejaste bien tu número, puedes escribirle a Martín directamente al <strong>+56 9 7648 0285</strong>.<br>' .
                'Saludos,<br><br>' .
                'El team Byebro!');

            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
        endforeach;

        $u_st = $via->setLast($iid);

        if (!$u_st['estado']):
            throw new Exception('Error al actualizar estados anteriores. ' . $u_st['msg'], 0);
        endif;

        $v = $via->setState(5, $iid, $db);

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
        $response = array('type' => false, 'msg' => $e->getMessage() . "{$mail->ErrorInfo}", 'code' => $e->getCode());
        echo json_encode($response);
    }
endif;
