<?php

if (isset($_SESSION['bb_logintime'])):
    if (time() - $_SESSION['bb_logintime'] >= SESSION_TIME):
        header("Location: src/logout.php");
    else:
        $_SESSION['bb_logintime'] = time();
    endif;
endif;