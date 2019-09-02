<?php

session_start();
include '../class/classMyDBC.php';
include '../class/classSession.php';
include '../class/classCounter.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../bower_components/phpmailer/src/Exception.php';
require '../bower_components/phpmailer/src/PHPMailer.php';
require '../bower_components/phpmailer/src/SMTP.php';

$qr = new myDBC();
$ses = new Session();

extract($_POST);
$isActive = false;

try {
    $stmt = $qr->Prepare("SELECT COUNT(*) AS num FROM bb_usuario WHERE us_username = ?");

    if (!$stmt):
        throw new Exception("El usuario ingresado no es correcto. Fallo en la preparación de la consulta de usuario.");
    endif;

    $cl_user = $qr->clearText(strtolower($user));
    $bind = $stmt->bind_param("s", $cl_user);

    if (!$bind):
        throw new Exception("El usuario ingresado no es correcto. Fallo en el binding de los parámetros de usuario.");
    endif;

    if (!$stmt->execute()):
        throw new Exception("El usuario ingresado no es correcto. Fallo en la ejecución de la consulta de usuario.");
    endif;

    $query = $stmt->get_result();
    $q_user = $query->fetch_assoc();

    if ($q_user['num'] == 0):
        throw new Exception("El usuario ingresado no existe.");
    endif;

    $stmt = $qr->Prepare("SELECT us_password FROM bb_usuario WHERE us_username = ?");

    if (!$stmt):
        throw new Exception("El usuario ingresado no es correcto. Fallo en la preparación de la consulta de contraseña.");
    endif;

    $cl_user = $qr->clearText($user);
    $bind = $stmt->bind_param("s", $cl_user);

    if (!$bind):
        throw new Exception("El usuario ingresado no es correcto. Fallo en el binding de los parámetros de contraseña.");
    endif;

    if (!$stmt->execute()):
        throw new Exception("El usuario ingresado no es correcto. Fallo en la ejecución de la consulta de contraseña.");
    endif;

    $query = $stmt->get_result();
    $q_pass = $query->fetch_assoc();

    if (md5($passwd) !== $q_pass['us_password']):
        throw new Exception("La contraseña ingresada no es correcta.");
    endif;

    $stmt = $qr->Prepare("SELECT us_activo FROM bb_usuario WHERE us_username = ?");

    if (!$stmt):
        throw new Exception("El usuario ingresado no es correcto. Fallo en la preparación de la consulta de activación.");
    endif;

    $cl_user = $qr->clearText($user);
    $bind = $stmt->bind_param("s", $cl_user);

    if (!$bind):
        throw new Exception("El usuario ingresado no es correcto. Fallo en el binding de los parámetros de activación.");
    endif;

    if (!$stmt->execute()):
        throw new Exception("El usuario ingresado no es correcto. Fallo en la ejecución de la consulta de activación.");
    endif;

    $query = $stmt->get_result();
    $q_active = $query->fetch_assoc();

    if ($q_active['us_activo'] !== 1):
        throw new Exception("El usuario ingresado no tiene permisos de acceso.");
    endif;

    $stmt = $qr->Prepare("SELECT * FROM bb_usuario u JOIN bb_perfil p ON u.perf_id = p.perf_id WHERE us_username = ?");

    if (!$stmt):
        throw new Exception("El usuario ingresado no es correcto. Fallo en la preparación de la consulta de datos de usuario.");
    endif;

    $cl_user = $qr->clearText($user);
    $bind = $stmt->bind_param("s", $cl_user);

    if (!$bind):
        throw new Exception("El usuario ingresado no es correcto. Fallo en el binding de los parámetros de datos de usuario.");
    endif;

    if (!$stmt->execute()):
        throw new Exception("El usuario ingresado no es correcto. Fallo en la ejecución de la consulta de datos de usuario.");
    endif;

    $query = $stmt->get_result();
    $q_data = $query->fetch_assoc();

    $_SESSION['bb_logintime'] = time();
    $_SESSION['bb_userid'] = $q_data['us_id'];
    $_SESSION['bb_username'] = $user;
    $_SESSION['bb_userfname'] = utf8_encode($q_data['us_nombres']);
    $_SESSION['bb_userlnamep'] = utf8_encode($q_data['us_ap']);
    $_SESSION['bb_userlnamem'] = utf8_encode($q_data['us_am']);
    $_SESSION['bb_useremail'] = $q_data['us_email'];
    $_SESSION['bb_userpic'] = utf8_encode($q_data['us_pic']);
    $_SESSION['bb_userprofile'] = $q_data['perf_id'];
    $_SESSION['bb_usergroup'] = $q_data['perf_descripcion'];

    if ($q_data['perf_id'] === 1):
        $_SESSION['bb_useradmin'] = true;
    endif;

    $set_last = $ses->setLast($q_data['us_id'], $qr);
    $set_session = $ses->set($q_data['us_id'], $_SERVER['REMOTE_ADDR'], $qr);

    //$q_ip = $ses->getCount($q_data['us_id'], $qr);
    //$q_ipcur = $ses->getCountIP($q_data['us_id'], $_SERVER['REMOTE_ADDR'], $qr);
    /*
    $tolerance = $q_ipcur / $q_ip;

    if ($tolerance < 0.05):
        $mail = new PHPMailer(true);

        $mail->IsSMTP();                            // telling the class to use SMTP
        $mail->SMTPDebug = 0;                        // enables SMTP debug information (for testing)
        $mail->SMTPAuth = true;                    // enable SMTP authentication
        $mail->SMTPSecure = "ssl";                    // sets the prefix to the servier
        $mail->Host = "smtp.gmail.com";            // sets GMAIL as the SMTP server
        $mail->Port = 465;                        // set the SMTP port for the GMAIL server
        $mail->Username = "ti.hggb@gmail.com";    // GMAIL username
        $mail->Password = "svr1503_root";           // GMAIL password

        $mail->SetFrom('soportedesarrollo@ssconcepcion.cl', 'Sistema De Gestion Documental');

        $mail->Subject = "Aviso de ingreso";
        $mail->AltBody = "Para visualizar el mensaje, por favor utilice un visor de correos compatible con HTML!"; // optional, comment out and test

        $html = "Estimado usuario:<br><br>Se ha hecho un ingreso a la Plataforma SISGDOC el " . date('d-m-Y') . " a las ". date('H:i') ." en IP ".  $_SERVER['REMOTE_ADDR'] . " usando sus datos de acceso, desde un lugar que no coincide con sus ingresos habituales.";
        $html .= "<br>Si este ingreso no fue realizado por usted, le recomendamos que cambie su contraseña dentro de la plataforma, usando el menu de usuario emergente";
        $html .= " al posicionar el cursor sobre su nombre en la esquina superior derecha, a un costado del boton de inicio.";
        $html .= "<br><br>Saludos cordiales,";
        $html .= "<br>Soporte Plataforma SISGDOC";
        $mail->MsgHTML(utf8_decode($html));

        $mail->AddAddress($q_data['us_email'], utf8_decode($q_data['us_nombres'] . ' ' . $q_data['us_ap']));

        if (!$mail->Send()):
            throw new Exception('Error al enviar correo de consulta. ' . $mail->ErrorInfo);
        endif;
    endif;
    */

    if ($set_session):
        //$c = new Counter();
        //$count = $c->set();
        echo "true";
        return;
    else:
        throw new Exception("Hubo un problema al guardar la sesión.");
    endif;

} catch (Exception $e) {
    echo $e->getMessage();
    return;
}
