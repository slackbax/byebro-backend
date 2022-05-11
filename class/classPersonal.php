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
    public function get($id, $db = null): stdClass
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
        $obj->per_id = $id;
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
    public function getAll($db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT per_id FROM bb_personal WHERE per_activo = TRUE ORDER BY per_ap, per_am, per_nombres");
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
    public function getByString($user, $db = null): array
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
    public function getByCity($id, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT per_id
                                    FROM bb_personal_ciudad pc
                                    WHERE cid_id = ?");

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $lista = [];

        while ($row = $result->fetch_assoc()):
            $lista[] = $this->get($row['per_id']);
        endwhile;

        unset($db);
        return $lista;
    }

    /**
     * @param $id
     * @param null $db
     * @return array
     */
    public function getCities($id, $db = null): array
    {
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
     * @param $id
     * @param null $db
     * @return array
     */
    public function getByViaje($id, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT *
                                    FROM bb_personal_cargo pc
                                    JOIN bb_cargo bc on pc.car_id = bc.car_id
                                    WHERE vi_id = ?");

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $lista = [];

        while ($row = $result->fetch_assoc()):
            $obj = $this->get($row['per_id']);
            $obj->car_id = $row['car_id'];
            $obj->car_nombre = utf8_encode($row['car_nombre']);
            $obj->car_descripcion = utf8_encode($row['car_descripcion']);
            $obj->car_registro = $row['pec_registro'];
            $lista[] = $obj;
        endwhile;

        unset($db);
        return $lista;
    }

    /**
     * @param $id
     * @param $vi
     * @param $car
     * @param null $db
     * @return mixed
     */
    public function getByTrip($id, $vi, $car, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT per_id
                                    FROM bb_personal_cargo pc
                                    WHERE per_id = ? AND vi_id = ? AND car_id = ?");

        $stmt->bind_param("iii", $id, $vi, $car);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        unset($db);
        return $row['per_id'];
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
    public function set($name, $ap, $am, $email, $phone, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("INSERT INTO bb_personal (per_nombres, per_ap, per_am, per_telefono, per_email, per_activo) VALUES (?, ?, ?, ?, ?, TRUE)");

            if (!$stmt):
                throw new Exception("La inserción del usuario falló en su preparación.");
            endif;

            $name = utf8_decode($db->clearText($name));
            $ap = utf8_decode($db->clearText($ap));
            $am = utf8_decode($db->clearText($am));
            $phone = utf8_decode($db->clearText($phone));
            $email = utf8_decode($db->clearText($email));
            $bind = $stmt->bind_param("sssss", $name, $ap, $am, $phone, $email);

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
            return array('estado' => false, 'msg' => $e->getMessage());
        }
    }

    /**
     * @param $id
     * @param $user
     * @param null $db
     * @return array
     */
    public function setUser($id, $user, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("INSERT INTO bb_personal_usuario (per_id, us_id) VALUES (?, ?)");

            if (!$stmt):
                throw new Exception("La inserción del usuario del personal falló en su preparación.");
            endif;

            $id = $db->clearText($id);
            $user = $db->clearText($user);
            $bind = $stmt->bind_param("ii", $id, $user);

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
            return array('estado' => false, 'msg' => $e->getMessage());
        }
    }

    /**
     * @param $id
     * @param $state
     * @param null $db
     * @return array
     */
    public function setState($id, $state, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("UPDATE bb_personal SET per_activo = ?, per_registro = CURRENT_TIMESTAMP WHERE per_id = ?");

            if (!$stmt):
                throw new Exception("La actualización del personal falló en su preparación.");
            endif;

            $state = $db->clearText($state);
            $id = $db->clearText($id);
            $bind = $stmt->bind_param("ii", $state, $id);

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
            return array('estado' => false, 'msg' => $e->getMessage());
        }
    }

    /**
     * @param $id
     * @param $city
     * @param null $db
     * @return array
     */
    public function setCity($id, $city, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("INSERT INTO bb_personal_ciudad (per_id, cid_id) VALUES (?, ?)");

            if (!$stmt):
                throw new Exception("La inserción de la ciudad del personal falló en su preparación.");
            endif;

            $id = $db->clearText($id);
            $city = $db->clearText($city);
            $bind = $stmt->bind_param("ii", $id, $city);

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
            return array('estado' => false, 'msg' => $e->getMessage());
        }
    }

    /**
     * @param $id
     * @param $vi
     * @param $cargo
     * @param null $db
     * @return array
     */
    public function setViaje($id, $vi, $cargo, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("INSERT INTO bb_personal_cargo (car_id, vi_id, per_id) VALUES (?, ?, ?)");

            if (!$stmt):
                throw new Exception("La inserción del staff falló en su preparación.");
            endif;

            $cargo = $db->clearText($cargo);
            $vi = $db->clearText($vi);
            $id = $db->clearText($id);
            $bind = $stmt->bind_param("iii", $cargo, $vi, $id);

            if (!$bind):
                throw new Exception("La inserción del staff falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La inserción del staff falló en su ejecución.");
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
     * @param $phone
     * @param null $db
     * @return array
     */
    public function mod($id, $name, $ap, $am, $email, $phone, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("UPDATE bb_personal SET per_nombres = ?, per_ap = ?, per_am = ?, per_telefono = ?, per_email = ? WHERE per_id = ?");

            if (!$stmt):
                throw new Exception("La modificación del usuario falló en su preparación.");
            endif;

            $name = utf8_decode($db->clearText($name));
            $ap = utf8_decode($db->clearText($ap));
            $am = utf8_decode($db->clearText($am));
            $phone = utf8_decode($db->clearText($phone));
            $email = utf8_decode($db->clearText($email));
            $bind = $stmt->bind_param("sssssi", $name, $ap, $am, $phone, $email, $id);

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
     * @param $user
     * @param null $db
     * @return array
     */
    public function modUser($id, $user, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("UPDATE bb_personal_usuario SET us_id = ? WHERE per_id = ?");

            if (!$stmt):
                throw new Exception("La modificación del usuario del personal falló en su preparación.");
            endif;

            $id = $db->clearText($id);
            $user = $db->clearText($user);
            $bind = $stmt->bind_param("ii", $id, $user);

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
            return array('estado' => false, 'msg' => $e->getMessage());
        }
    }

    /**
     * @param $id
     * @param null $db
     * @return array
     */
    public function delCities($id, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("DELETE FROM bb_personal_ciudad WHERE per_id = ?");

            if (!$stmt):
                throw new Exception("La eliminación de las ciudades del personal falló en su preparación.");
            endif;

            $id = $db->clearText($id);
            $bind = $stmt->bind_param("i", $id);

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
            return array('estado' => false, 'msg' => $e->getMessage());
        }
    }
}
