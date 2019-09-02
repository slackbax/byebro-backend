<?php

class Cotizante
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
                                    FROM bb_cotizante c
                                    LEFT JOIN bb_usuario u ON c.us_id = u.us_id
                                    WHERE c.co_id = ?");

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $obj = new stdClass();
        $obj->co_id = $row['co_id'];
        $obj->us_id = $row['us_id'];
        $obj->co_rut = utf8_encode($row['co_rut']);
        $obj->co_nombres = utf8_encode($row['co_nombres']);
        $obj->co_ap = utf8_encode($row['co_ap']);
        $obj->co_am = utf8_encode($row['co_am']);
        $obj->co_email = utf8_encode($row['co_email']);
        $obj->co_telefono = utf8_encode($row['co_telefono']);
        $obj->co_registro = $row['co_registro'];

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

        $stmt = $db->Prepare("SELECT co_id FROM bb_cotizante");
        $stmt->execute();
        $result = $stmt->get_result();
        $lista = [];

        while ($row = $result->fetch_assoc()):
            $lista[] = $this->get($row['co_id'], $db);
        endwhile;

        unset($db);
        return $lista;
    }

    /**
     * @param $rut
     * @param null $db
     * @return stdClass
     */
    public function getByRut($rut, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT co_id 
                                    FROM bb_cotizante c
                                    WHERE c.co_rut = ?");

        $stmt->bind_param("s", $rut);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $this->get($row['co_id'], $db);
    }

    /**
     * @param $user
     * @param null $db
     * @return stdClass
     */
    public function getByUser($user, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT co_id 
                                    FROM bb_cotizante c
                                    WHERE c.us_id = ?");

        $stmt->bind_param("i", $user);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $this->get($row['co_id'], $db);
    }

    /**
     * @param $user
     * @param $rut
     * @param $name
     * @param $ap
     * @param $am
     * @param $email
     * @param $phone
     * @param null $db
     * @return array
     */
    public function set($user, $rut, $name, $ap, $am, $email, $phone, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("INSERT INTO bb_cotizante (us_id, co_rut, co_nombres, co_ap, co_am, co_telefono, co_email) VALUES (?, ?, ?, ?, ?, ?, ?)");

            if (!$stmt):
                throw new Exception("La inserción del cotizante falló en su preparación.");
            endif;

            $bind = $stmt->bind_param("issssss", $db->clearText($user), utf8_decode($db->clearText($rut)), utf8_decode($db->clearText($name)), utf8_decode($db->clearText($ap)),
                utf8_decode($db->clearText($am)), utf8_decode($db->clearText($phone)), utf8_decode($db->clearText($email)));

            if (!$bind):
                throw new Exception("La inserción del cotizante falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La inserción del cotizante falló en su ejecución.");
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
     * @param $email
     * @param $phone
     * @param null $db
     * @return array
     */
    public function modViaje($id, $email, $phone, $db = null) {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("UPDATE bb_cotizante SET co_email = ?, co_telefono = ? WHERE co_id = ?");

            if (!$stmt):
                throw new Exception("La actualización del cotizante falló en su preparación.");
            endif;

            $bind = $stmt->bind_param("ssi", utf8_decode($db->clearText($email)), utf8_decode($db->clearText($phone)), $db->clearText($id));

            if (!$bind):
                throw new Exception("La actualización del cotizante falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La actualización del cotizante falló en su ejecución.");
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