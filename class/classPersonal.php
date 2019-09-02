<?php

class Personal
{
    public function __construct()
    {
    }

    /**
     * @param $id
     * @param null $db
     * @return stdClass
     */
    public function get($id, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT * 
                                    FROM bb_personal p 
                                    LEFT JOIN bb_personal_usuario pu ON p.per_id = pu.per_id
                                    LEFT JOIN bb_usuario u ON pu.us_id = u.us_id
                                    WHERE p.per_id = ?");

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $obj = new stdClass();
        $obj->per_id = $row['per_id'];
        $obj->us_id = $row['us_id'];
        $obj->us_username = utf8_encode($row['us_username']);
        $obj->per_nombres = utf8_encode($row['per_nombres']);
        $obj->per_ap = utf8_encode($row['per_ap']);
        $obj->per_am = utf8_encode($row['per_am']);
        $obj->per_email = utf8_encode($row['per_email']);
        $obj->per_telefono = utf8_encode($row['per_telefono']);
        $obj->per_pic = $row['per_pic'];
        $obj->per_registro = $row['per_registro'];
        $obj->per_activo = $row['per_activo'];

        unset($db);
        return $obj;
    }

    /**
     * @param null $db
     * @return array
     */
    public function getAll($db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT per_id FROM bb_personal WHERE per_activo = TRUE ORDER BY per_ap ASC, per_am ASC, per_nombres ASC");
        $stmt->execute();
        $result = $stmt->get_result();
        $lista = [];

        while ($row = $result->fetch_assoc()):
            $lista[] = $this->get($row['per_id'], $db);
        endwhile;

        unset($db);
        return $lista;
    }

    /**
     * @param $user
     * @param null $db
     * @return array
     */
    public function getByString($user, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $str = '%' . utf8_decode($db->clearText($user)) . '%';

        $stmt = $db->Prepare("SELECT u.per_id, u.per_nombres, u.per_ap, u.per_am
                                    FROM bb_personal u
                                    WHERE per_activo = TRUE AND (u.per_nombres LIKE ? OR u.per_ap LIKE ? OR u.per_am LIKE ? OR CONCAT(u.per_nombres, ' ', u.per_ap) LIKE ?)
                                    GROUP BY u.per_id");

        $stmt->bind_param("ssss", $str, $str, $str, $str);
        $stmt->execute();
        $result = $stmt->get_result();
        $lista = [];

        while ($row = $result->fetch_assoc()):
            $lista[] = array('id' => $row['per_id'], 'value' => utf8_encode('[' . $row['per_id'] . '] ' . $row['per_nombres'] . ' ' . $row['per_ap']));
        endwhile;

        unset($db);
        return $lista;
    }

    /**
     * @param $id
     * @param null $db
     * @return array
     */
    public function getCities($id, $db = null) {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT * 
                                    FROM bb_personal_ciudad pc
                                    JOIN bb_ciudad_destino bcd on pc.cid_id = bcd.cid_id
                                    WHERE per_id = ?");

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $lista = [];

        while ($row = $result->fetch_assoc()):
            $obj = new stdClass();
            $obj->cid_id = $row['cid_id'];
            $obj->cid_nombre = utf8_encode($row['cid_nombre']);
            $lista[] = $obj;
        endwhile;

        unset($db);
        return $lista;
    }

    /**
     * @param $name
     * @param $ap
     * @param $am
     * @param $email
     * @param $phone
     * @param null $db
     * @return array
     */
    public function set($name, $ap, $am, $email, $phone, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("INSERT INTO bb_personal (per_nombres, per_ap, per_am, per_telefono, per_email, per_activo) VALUES (?, ?, ?, ?, ?, TRUE)");

            if (!$stmt):
                throw new Exception("La inserción del usuario falló en su preparación.");
            endif;

            $bind = $stmt->bind_param("sssss", utf8_decode($db->clearText($name)), utf8_decode($db->clearText($ap)), utf8_decode($db->clearText($am)), utf8_decode($db->clearText($phone)), utf8_decode($db->clearText($email)));

            if (!$bind):
                throw new Exception("La inserción del usuario falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La inserción del usuario falló en su ejecución.");
            endif;

            $result = array('estado' => true, 'msg' => $stmt->insert_id);
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            $result = array('estado' => false, 'msg' => $e->getMessage());
            return $result;
        }
    }

    /**
     * @param $id
     * @param $user
     * @param null $db
     * @return array
     */
    public function setUser($id, $user, $db = null) {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("INSERT INTO bb_personal_usuario (per_id, us_id) VALUES (?, ?)");

            if (!$stmt):
                throw new Exception("La inserción del usuario del personal falló en su preparación.");
            endif;

            $bind = $stmt->bind_param("ii", $db->clearText($id), $db->clearText($user));

            if (!$bind):
                throw new Exception("La inserción del usuario del personal falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La inserción del usuario del personal falló en su ejecución.");
            endif;

            $result = array('estado' => true, 'msg' => $stmt->insert_id);
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            $result = array('estado' => false, 'msg' => $e->getMessage());
            return $result;
        }
    }

    /**
     * @param $id
     * @param $state
     * @param null $db
     * @return array
     */
    public function setState($id, $state, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("UPDATE bb_personal SET per_activo = ?, per_registro = CURRENT_TIMESTAMP WHERE per_id = ?");

            if (!$stmt):
                throw new Exception("La actualización del personal falló en su preparación.");
            endif;

            $bind = $stmt->bind_param("ii", $db->clearText($state), $db->clearText($id));

            if (!$bind):
                throw new Exception("La actualización del personal falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La actualización del personal falló en su ejecución.");
            endif;

            $result = array('estado' => true, 'msg' => true);
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            $result = array('estado' => false, 'msg' => $e->getMessage());
            return $result;
        }
    }

    /**
     * @param $id
     * @param $city
     * @param null $db
     * @return array
     */
    public function setCity($id, $city, $db = null) {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("INSERT INTO bb_personal_ciudad (per_id, cid_id) VALUES (?, ?)");

            if (!$stmt):
                throw new Exception("La inserción de la ciudad del personal falló en su preparación.");
            endif;

            $bind = $stmt->bind_param("ii", $db->clearText($id), $db->clearText($city));

            if (!$bind):
                throw new Exception("La inserción de la ciudad del personal falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La inserción de la ciudad del personal falló en su ejecución.");
            endif;

            $result = array('estado' => true, 'msg' => true);
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            $result = array('estado' => false, 'msg' => $e->getMessage());
            return $result;
        }
    }

    /**
     * @param $id
     * @param $pic
     * @param null $db
     * @return array
     *
    public function setPicture($id, $pic, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("UPDATE bb_personal SET per_pic = ? WHERE per_id = ?");

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
            $result = array('estado' => false, 'msg' => $e->getMessage());
            return $result;
        }
    } */

    /**
     * @param $id
     * @param $name
     * @param $ap
     * @param $am
     * @param $email
     * @param $phone
     * @param $active
     * @param null $db
     * @return array
     */
    public function mod($id, $name, $ap, $am, $email, $phone, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("UPDATE bb_personal SET per_nombres = ?, per_ap = ?, per_am = ?, per_telefono = ?, per_email = ? WHERE per_id = ?");

            if (!$stmt):
                throw new Exception("La modificación del usuario falló en su preparación.");
            endif;

            $bind = $stmt->bind_param("sssssi", utf8_decode($db->clearText($name)), utf8_decode($db->clearText($ap)), utf8_decode($db->clearText($am)), utf8_decode($db->clearText($phone)), utf8_decode($db->clearText($email)), $id);

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
            $result = array('estado' => false, 'msg' => $e->getMessage());
            return $result;
        }
    }

    /**
     * @param $id
     * @param $user
     * @param null $db
     * @return array
     */
    public function modUser($id, $user, $db = null) {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("UPDATE bb_personal_usuario SET us_id = ? WHERE per_id = ?");

            if (!$stmt):
                throw new Exception("La modificación del usuario del personal falló en su preparación.");
            endif;

            $bind = $stmt->bind_param("ii", $db->clearText($id), $db->clearText($user));

            if (!$bind):
                throw new Exception("La modificación del usuario del personal falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La modificación del usuario del personal falló en su ejecución.");
            endif;

            $result = array('estado' => true, 'msg' => $stmt->insert_id);
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            $result = array('estado' => false, 'msg' => $e->getMessage());
            return $result;
        }
    }

    /**
     * @param $id
     * @param null $db
     * @return array
     */
    public function delCities($id, $db = null) {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("DELETE FROM bb_personal_ciudad WHERE per_id = ?");

            if (!$stmt):
                throw new Exception("La eliminación de las ciudades del personal falló en su preparación.");
            endif;

            $bind = $stmt->bind_param("i", $db->clearText($id));

            if (!$bind):
                throw new Exception("La eliminación de las ciudades del usuario del personal falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La eliminación de las ciudades del usuario del personal falló en su ejecución.");
            endif;

            $result = array('estado' => true, 'msg' => $stmt->insert_id);
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            $result = array('estado' => false, 'msg' => $e->getMessage());
            return $result;
        }
    }
}