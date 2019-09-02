<?php

class Participante
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
                                    FROM bb_participante p
                                    WHERE p.par_id = ?");

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $obj = new stdClass();
        $obj->par_id = $row['par_id'];
        $obj->cot_id = $row['cot_id'];
        $obj->par_rut = utf8_encode($row['par_rut']);
        $obj->par_nombres = utf8_encode($row['par_nombres']);
        $obj->par_ap = utf8_encode($row['par_ap']);
        $obj->par_am = utf8_encode($row['par_am']);
        $obj->par_edad = $row['par_edad'];
        $obj->par_email = utf8_encode($row['par_email']);
        $obj->par_telefono = utf8_encode($row['par_telefono']);
        $obj->par_encargado = $row['par_encargado'];
        $obj->par_registro = $row['par_registro'];

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

        $stmt = $db->Prepare("SELECT par_id FROM bb_participante");
        $stmt->execute();
        $result = $stmt->get_result();
        $lista = [];

        while ($row = $result->fetch_assoc()):
            $lista[] = $this->get($row['par_id'], $db);
        endwhile;

        unset($db);
        return $lista;
    }

    /**
     * @param $cot
     * @param null $db
     * @return array
     */
    public function getByCotizacion($cot, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT par_id 
                                    FROM bb_participante c
                                    WHERE c.cot_id = ?");

        $stmt->bind_param("i", $cot);
        $stmt->execute();
        $result = $stmt->get_result();
        $lista = [];

        while ($row = $result->fetch_assoc()):
            $lista[] = $this->get($row['par_id'], $db);
        endwhile;

        unset($db);
        return $lista;
    }

    /**
     * @param $cot
     * @param null $db
     * @return array
     */
    public function getByViaje($cot, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT par_id 
                                    FROM bb_participante c
                                    JOIN bb_cotizacion bc on c.cot_id = bc.cot_id
                                    JOIN bb_viaje bv on bc.cot_id = bv.cot_id
                                    WHERE bv.vi_id = ? AND par_viaja IS TRUE");

        $stmt->bind_param("i", $cot);
        $stmt->execute();
        $result = $stmt->get_result();
        $lista = [];

        while ($row = $result->fetch_assoc()):
            $lista[] = $this->get($row['par_id'], $db);
        endwhile;

        unset($db);
        return $lista;
    }

    /**
     * @param $cot
     * @param $rut
     * @param null $db
     * @return stdClass
     */
    public function getByRutCotizacion($cot, $rut, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT * 
                                    FROM bb_participante p
                                    WHERE p.cot_id = ? AND p.par_rut = ?");

        $stmt->bind_param("is", $cot, $rut);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $obj = new stdClass();
        $obj->par_id = $row['par_id'];
        $obj->cot_id = $row['cot_id'];
        $obj->par_rut = utf8_encode($row['par_rut']);
        $obj->par_nombres = utf8_encode($row['par_nombres']);
        $obj->par_ap = utf8_encode($row['par_ap']);
        $obj->par_am = utf8_encode($row['par_am']);
        $obj->par_email = utf8_encode($row['par_email']);
        $obj->par_telefono = utf8_encode($row['par_telefono']);
        $obj->par_encargado = $row['par_encargado'];
        $obj->par_registro = $row['par_registro'];

        unset($db);
        return $obj;
    }

    /**
     * @param $cot
     * @param $rut
     * @param $name
     * @param $ap
     * @param $am
     * @param $edad
     * @param $email
     * @param $phone
     * @param $cargo
     * @param $cotiza
     * @param $viaje
     * @param null $db
     * @return array
     */
    public function set($cot, $rut, $name, $ap, $am, $edad, $email, $phone, $cargo, $cotiza, $viaje, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("INSERT INTO bb_participante (cot_id, par_rut, par_nombres, par_ap, par_am, par_edad, par_email, par_telefono, par_encargado, par_cotiza, par_viaja) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            if (!$stmt):
                throw new Exception("La inserción del participante falló en su preparación.");
            endif;

            $bind = $stmt->bind_param("issssississ", $db->clearText($cot), utf8_decode($db->clearText($rut)), utf8_decode($db->clearText($name)), utf8_decode($db->clearText($ap)),
                utf8_decode($db->clearText($am)), $db->clearText($edad), utf8_decode($db->clearText($email)), utf8_decode($db->clearText($phone)), $cargo, $cotiza, $viaje);

            if (!$bind):
                throw new Exception("La inserción del participante falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La inserción del participante falló en su ejecución.");
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
     * @param $rut
     * @param $name
     * @param $ap
     * @param $am
     * @param $edad
     * @param $email
     * @param $phone
     * @param $viaje
     * @param null $db
     * @return array
     */
    public function mod($id, $rut, $name, $ap, $am, $edad, $email, $phone, $viaje, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("UPDATE bb_participante SET par_rut = ?, par_nombres = ?, par_ap = ?, par_am = ?, par_edad = ?, par_email = ?, par_telefono = ?, par_viaja = ? WHERE par_id = ?");

            if (!$stmt):
                throw new Exception("La modificación del participante falló en su preparación.");
            endif;

            $bind = $stmt->bind_param("ssssissii", utf8_decode($db->clearText($rut)), utf8_decode($db->clearText($name)), utf8_decode($db->clearText($ap)),
                utf8_decode($db->clearText($am)), $db->clearText($edad), utf8_decode($db->clearText($email)), utf8_decode($db->clearText($phone)), $viaje, $id);

            if (!$bind):
                throw new Exception("La modificación del participante falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La modificación del participante falló en su ejecución.");
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