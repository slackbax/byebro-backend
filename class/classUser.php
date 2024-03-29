<?php

class User
{
    public function __construct()
    {
    }

    /**
     * @param $id
     * @param null $db
     * @return stdClass
     */
    public function get($id, $db = null): stdClass
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT * FROM bb_usuario u JOIN bb_perfil p ON u.perf_id = p.perf_id WHERE us_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $obj = new stdClass();
        $obj->us_id = $row['us_id'];
        $obj->perf_id = $row['perf_id'];
        $obj->perf_descripcion = utf8_encode($row['perf_descripcion']);
        $obj->us_nombres = utf8_encode($row['us_nombres']);
        $obj->us_ap = utf8_encode($row['us_ap']);
        $obj->us_am = utf8_encode($row['us_am']);
        $obj->us_email = $row['us_email'];
        $obj->us_username = $row['us_username'];
        $obj->us_password = $row['us_password'];
        $obj->us_pic = $row['us_pic'];
        $obj->us_registro = $row['us_registro'];
        $obj->us_activo = $row['us_activo'];

        unset($db);
        return $obj;
    }

    /**
     * @param null $db
     * @return array
     */
    public function getAll($db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT us_id FROM bb_usuario WHERE us_activo = TRUE ORDER BY us_ap, us_am, us_nombres");
        $stmt->execute();
        $result = $stmt->get_result();
        $lista = [];

        while ($row = $result->fetch_assoc()):
            $lista[] = $this->get($row['us_id'], $db);
        endwhile;

        unset($db);
        return $lista;
    }

    /**
     * @param $str
     * @param null $db
     * @return stdClass
     */
    public function getByUsername($str, $db = null): stdClass
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT us_id FROM bb_usuario WHERE us_username = ?");
        $str = utf8_decode($db->clearText($str));
        $stmt->bind_param("s", $str);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $obj = $this->get($row['us_id'], $db);

        unset($db);
        return $obj;
    }

    /**
     * @param $user
     * @param null $db
     * @return array
     */
    public function getByString($user, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $str = '%' . utf8_decode($db->clearText($user)) . '%';

        $stmt = $db->Prepare("SELECT u.us_id, u.us_username, u.us_nombres, u.us_ap, u.us_am
                                    FROM bb_usuario u
                                    WHERE us_activo = TRUE AND (u.us_nombres LIKE ? OR u.us_ap LIKE ? OR u.us_am LIKE ? OR CONCAT(u.us_nombres, ' ', u.us_ap) LIKE ? OR u.us_username LIKE ?)
                                    GROUP BY u.us_id");

        $stmt->bind_param("sssss", $str, $str, $str, $str, $str);
        $stmt->execute();
        $result = $stmt->get_result();
        $lista = [];

        while ($row = $result->fetch_assoc()):
            $lista[] = array('id' => $row['us_id'], 'value' => utf8_encode('[' . $row['us_id'] . '] ' . $row['us_nombres'] . ' ' . $row['us_ap'] . ' (' . $row['us_username'] . ')'));
        endwhile;

        unset($db);
        return $lista;
    }

    /**
     * @param $user
     * @param null $db
     * @return array
     */
    public function existsUser($user, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("SELECT COUNT(us_id) AS n FROM bb_usuario WHERE us_username = ?");

            if (!$stmt):
                throw new Exception("La búsqueda del usuario falló en su preparación.");
            endif;

            $user = $db->clearText($user);
            $bind = $stmt->bind_param("s", $user);
            if (!$bind):
                throw new Exception("La búsqueda del usuario falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La búsqueda del usuario falló en su ejecución.");
            endif;

            $result = $stmt->get_result();
            $tnum = $result->fetch_assoc();

            if ($tnum['n'] > 0):
                $result = array('estado' => true, 'msg' => true);
            else:
                $result = array('estado' => true, 'msg' => false);
            endif;

            $stmt->close();
            return $result;
        } catch (Exception $e) {
            return array('estado' => false, 'msg' => $e->getMessage());
        }
    }

    /**
     * @param $profile
     * @param $name
     * @param $ap
     * @param $am
     * @param $email
     * @param $user
     * @param $pass
     * @param null $db
     * @return array
     */
    public function set($profile, $name, $ap, $am, $email, $user, $pass, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("INSERT INTO bb_usuario (perf_id, us_nombres, us_ap, us_am, us_email, us_username, us_password, us_psw, us_activo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, TRUE)");

            if (!$stmt):
                throw new Exception("La inserción del usuario falló en su preparación.");
            endif;

            $profile = $db->clearText($profile);
            $name = utf8_decode($db->clearText($name));
            $ap = utf8_decode($db->clearText($ap));
            $am = utf8_decode($db->clearText($am));
            $email = utf8_decode($db->clearText($email));
            $user = utf8_decode($db->clearText($user));
            $pass_enc = md5(utf8_decode($db->clearText($pass)));
            $bind = $stmt->bind_param("isssssss", $profile, $name, $ap, $am, $email, $user, $pass_enc, $pass);

            if (!$bind):
                throw new Exception("La inserción del usuario falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La inserción del usuario falló en su ejecución. " . $stmt->error);
            endif;

            $result = array('estado' => true, 'msg' => $stmt->insert_id);
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            return array('estado' => false, 'msg' => $e->getMessage());
        }
    }

    /**
     * @param $id
     * @param $pic
     * @param null $db
     * @return array
     */
    public function setPicture($id, $pic, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("UPDATE bb_usuario SET us_pic = ? WHERE us_id = ?");

            if (!$stmt):
                throw new Exception("La inserción de la imagen falló en su preparación.");
            endif;

            $bind = $stmt->bind_param("si", $pic, $id);
            if (!$bind):
                throw new Exception("La inserción de la imagen falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La inserción de la imagen falló en su ejecución.");
            endif;

            $result = array('estado' => true, 'msg' => true);
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            return array('estado' => false, 'msg' => $e->getMessage());
        }
    }

    /**
     * @param $id
     * @param null $db
     * @return array
     */
    public function del($id, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("UPDATE bb_usuario SET us_activo = FALSE WHERE us_id = ?");

            if (!$stmt):
                throw new Exception("La desactivación del usuario falló en su preparación.");
            endif;

            $bind = $stmt->bind_param("i", $id);
            if (!$bind):
                throw new Exception("La desactivación del usuario falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La desactivación del usuario falló en su ejecución.");
            endif;

            $result = array('estado' => true, 'msg' => true);
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            return array('estado' => false, 'msg' => $e->getMessage());
        }
    }

    /**
     * @param $id
     * @param null $db
     * @return array
     */
    public function activate($id, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("UPDATE bb_usuario SET us_activo = TRUE WHERE us_id = ?");

            if (!$stmt):
                throw new Exception("La activación del usuario falló en su preparación.");
            endif;

            $bind = $stmt->bind_param("i", $id);
            if (!$bind):
                throw new Exception("La activación del usuario falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La activación del usuario falló en su ejecución.");
            endif;

            $result = array('estado' => true, 'msg' => true);
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            return array('estado' => false, 'msg' => $e->getMessage());
        }
    }

    /**
     * @param $id
     * @param $profile
     * @param $name
     * @param $ap
     * @param $am
     * @param $email
     * @param $pass
     * @param $active
     * @param null $db
     * @return array
     */
    public function mod($id, $profile, $name, $ap, $am, $email, $pass, $active, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        if ($pass != ''):
            $txt_p = md5(utf8_decode($db->clearText($pass)));
        else:
            $res = $db->runQuery("SELECT us_password FROM bb_usuario WHERE us_id = '$id'");
            $row = $res->fetch_assoc();
            $txt_p = $row['us_password'];
        endif;

        try {
            $stmt = $db->Prepare("UPDATE bb_usuario SET perf_id = ?, us_nombres = ?, us_ap = ?, us_am = ?, us_email = ?, us_password = ?, us_activo = ? WHERE us_id = ?");

            if (!$stmt):
                throw new Exception("La modificación del usuario falló en su preparación.");
            endif;

            $profile = $db->clearText($profile);
            $name = utf8_decode($db->clearText($name));
            $ap = utf8_decode($db->clearText($ap));
            $am = utf8_decode($db->clearText($am));
            $email = $db->clearText($email);
            $bind = $stmt->bind_param("issssssi", $profile, $name, $ap, $am, $email, $txt_p, $active, $id);

            if (!$bind):
                throw new Exception("La modificación del usuario falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La modificación del usuario falló en su ejecución.");
            endif;

            $result = array('estado' => true, 'msg' => true);
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            return array('estado' => false, 'msg' => $e->getMessage());
        }
    }

    /**
     * @param $id
     * @param $name
     * @param $ap
     * @param $am
     * @param $email
     * @param null $db
     * @return array
     */
    public function modProfile($id, $name, $ap, $am, $email, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("UPDATE bb_usuario SET us_nombres = ?, us_ap = ?, us_am = ?, us_email = ? WHERE us_id = ?");

            if (!$stmt):
                throw new Exception("La modificación del usuario falló en su preparación.");
            endif;

            $name = utf8_decode($db->clearText($name));
            $ap = utf8_decode($db->clearText($ap));
            $am = utf8_decode($db->clearText($am));
            $email = $db->clearText($email);
            $bind = $stmt->bind_param("ssssi", $name, $ap, $am, $email, $id);
            if (!$bind):
                throw new Exception("La modificación del usuario falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La modificación del usuario falló en su ejecución.");
            endif;

            $result = array('estado' => true, 'msg' => true);
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            return array('estado' => false, 'msg' => $e->getMessage());
        }
    }

    /**
     * @param $id
     * @param $pass
     * @param null $db
     * @return array
     */
    public function modPass($id, $pass, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $txt_p = md5(utf8_decode($db->clearText($pass)));

        try {
            $stmt = $db->Prepare("UPDATE bb_usuario SET us_password = ? WHERE us_id = ?");

            if (!$stmt):
                throw new Exception("La modificación del usuario falló en su preparación.");
            endif;

            $bind = $stmt->bind_param("si", $txt_p, $id);
            if (!$bind):
                throw new Exception("La modificación del usuario falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La modificación del usuario falló en su ejecución.");
            endif;

            $result = array('estado' => true, 'msg' => $txt_p);
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            return array('estado' => false, 'msg' => $e->getMessage());
        }
    }
}
