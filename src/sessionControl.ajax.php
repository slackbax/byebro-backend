<?php

session_start();
$_BASEDIR = explode('src', dirname(__FILE__));
include_once $_BASEDIR[0] . 'src/settings.php';
$logout = false;

try {
    if (isset($_SESSION['bb_logintime'])):
        $timeout = ((time() - $_SESSION['bb_logintime']) >= SESSION_TIME) ? false : true;

        if (!$timeout):
            $logout = true;
            throw new Exception('Tu sesión ha cerrado por inactividad, debes iniciar sesión nuevamente. Redirigiendo a página de inicio...', 1);
        else:
            $_SESSION['bb_logintime'] = time();
        endif;
    endif;
} catch (Exception $e) {
    $response = array('type' => false, 'msg' => $e->getMessage(), 'code' => $e->getCode());
    echo json_encode($response);
    die();
}