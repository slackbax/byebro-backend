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
    public function get($id, $db = null): stdClass
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
        $obj->par_codigo = $row['par_codigo'];
        $obj->par_cuota = $row['par_cuota'];
        $obj->par_cotiza = $row['par_cotiza'];
        $obj->par_viaja = $row['par_viaja'];
        $obj->par_paga = $row['par_paga'];
        $obj->par_registro = $row['par_registro'];

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
    public function getByCotizacion($cot, $db = null): array
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
    public function getByViaje($cot, $db = null): array
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
    public function getByRutCotizacion($cot, $rut, $db = null): stdClass
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
     * @param $code
     * @param null $db
     * @return stdClass
     */
    public function getByCode($code, $db = null): stdClass
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT par_id 
                                    FROM bb_participante p
                                    WHERE p.par_codigo = ?");

        $stmt->bind_param("s", $code);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $obj = $this->get($row['par_id']);

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
    public function set($cot, $rut, $name, $ap, $am, $edad, $email, $phone, $cargo, $cotiza, $viaje, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("INSERT INTO bb_participante (cot_id, par_rut, par_nombres, par_ap, par_am, par_edad, par_email, par_telefono, par_encargado, par_cotiza, par_viaja) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            if (!$stmt):
                throw new Exception("La inserción del participante falló en su preparación.");
            endif;

            $cot = $db->clearText($cot);
            $rut = utf8_decode($db->clearText($rut));
            $name = utf8_decode($db->clearText($name));
            $ap = utf8_decode($db->clearText($ap));
            $am = utf8_decode($db->clearText($am));
            $edad = $db->clearText($edad);
            $email = utf8_decode($db->clearText(mb_strtolower($email)));
            $phone = utf8_decode($db->clearText($phone));
            $bind = $stmt->bind_param("issssississ", $cot, $rut, $name, $ap, $am, $edad, $email, $phone, $cargo, $cotiza, $viaje);

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
            return array('estado' => false, 'msg' => $e->getMessage());
        }
    }

    /**
     * @param $id
     * @param $cuota
     * @param $codigo
     * @param null $db
     * @return array
     */
    public function setQuota($id, $cuota, $codigo, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("UPDATE bb_participante SET par_codigo = ?, par_cuota = ? WHERE par_id = ?");

            if (!$stmt):
                throw new Exception("La inserción de la cuota falló en su preparación.");
            endif;

            $cuota = $db->clearText($cuota);
            $bind = $stmt->bind_param("sii", $codigo, $cuota, $id);

            if (!$bind):
                throw new Exception("La inserción de la cuota falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La inserción de la cuota falló en su ejecución.");
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
    public function setPago($id, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("UPDATE bb_participante SET par_paga = TRUE WHERE par_id = ?");

            if (!$stmt):
                throw new Exception("La inserción del estado de pago falló en su preparación.");
            endif;

            $bind = $stmt->bind_param("i", $id);

            if (!$bind):
                throw new Exception("La inserción del estado de pago falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La inserción del estado de pago falló en su ejecución.");
            endif;

            $result = array('estado' => true, 'msg' => 'OK');
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            return array('estado' => false, 'msg' => $e->getMessage());
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
    public function mod($id, $rut, $name, $ap, $am, $edad, $email, $phone, $viaje, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("UPDATE bb_participante SET par_rut = ?, par_nombres = ?, par_ap = ?, par_am = ?, par_edad = ?, par_email = ?, par_telefono = ?, par_viaja = ? WHERE par_id = ?");

            if (!$stmt):
                throw new Exception("La modificación del participante falló en su preparación.");
            endif;

            $rut = utf8_decode($db->clearText($rut));
            $name = utf8_decode($db->clearText($name));
            $ap = utf8_decode($db->clearText($ap));
            $am = utf8_decode($db->clearText($am));
            $edad = $db->clearText($edad);
            $email = utf8_decode($db->clearText(mb_strtolower($email)));
            $phone = utf8_decode($db->clearText($phone));
            $bind = $stmt->bind_param("ssssissii", $rut, $name, $ap, $am, $edad, $email, $phone, $viaje, $id);

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
            return array('estado' => false, 'msg' => $e->getMessage());
        }
    }
}
