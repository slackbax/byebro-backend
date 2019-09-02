<?php

class Cotizacion
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
                                    FROM bb_cotizacion c
                                    JOIN bb_cotizante co ON c.co_id = co.co_id
                                    JOIN bb_ciudad_origen bco on c.cio_id = bco.cio_id
                                    JOIN bb_ciudad_destino bcd on c.cid_id = bcd.cid_id
                                    WHERE c.cot_id = ?");

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $obj = new stdClass();
        $obj->cot_id = $row['cot_id'];
        $obj->cio_id = $row['cio_id'];
        $obj->cio_nombre = utf8_encode($row['cio_nombre']);
        $obj->cid_id = $row['cid_id'];
        $obj->cid_nombre = utf8_encode($row['cid_nombre']);
        $obj->co_id = $row['co_id'];
        $obj->co_rut = utf8_encode($row['co_rut']);
        $obj->co_nombres = utf8_encode($row['co_nombres']);
        $obj->co_ap = utf8_encode($row['co_ap']);
        $obj->co_am = utf8_encode($row['co_am']);
        $obj->co_email = utf8_encode($row['co_email']);
        $obj->co_telefono = utf8_encode($row['co_telefono']);
        $obj->alo_id = $row['alo_id'];
        $obj->cot_codigo = $row['cot_codigo'];
        $obj->cot_fecha_ini = $row['cot_fecha_ini'];
        $obj->cot_fecha_ter = $row['cot_fecha_ter'];
        $obj->cot_valor = $row['cot_valor'];
        $obj->cot_link = $row['cot_link'];
        $obj->cot_registro = $row['cot_registro'];

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

        $stmt = $db->Prepare("SELECT cot_id FROM bb_cotizacion");
        $stmt->execute();
        $result = $stmt->get_result();
        $lista = [];

        while ($row = $result->fetch_assoc()):
            $lista[] = $this->get($row['cot_id'], $db);
        endwhile;

        unset($db);
        return $lista;
    }

    /**
     * @param $cot
     * @param null $db
     * @return array
     */
    public function getByCotizante($cot, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT cot_id 
                                    FROM bb_cotizacion c
                                    WHERE c.co_id = ?");

        $stmt->bind_param("i", $cot);
        $stmt->execute();
        $result = $stmt->get_result();
        $lista = [];

        while ($row = $result->fetch_assoc()):
            $lista[] = $this->get($row['cot_id'], $db);
        endwhile;

        unset($db);
        return $lista;
    }

    /**
     * @param $state
     * @param null $db
     * @return array
     */
    public function getByState($state, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT c.cot_id 
                                    FROM bb_cotizacion c
                                    JOIN bb_cotizacion_cotestado bcc on c.cot_id = bcc.cot_id
                                    WHERE ces_id = ? AND cote_ultimo IS TRUE");

        $stmt->bind_param("i", $state);
        $stmt->execute();
        $result = $stmt->get_result();
        $lista = [];

        while ($row = $result->fetch_assoc()):
            $lista[] = $this->get($row['cot_id'], $db);
        endwhile;

        unset($db);
        return $lista;
    }

    /**
     * @param $state
     * @param $cot
     * @param null $db
     * @return array
     */
    public function getByStateCotizante($state, $cot, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT c.cot_id 
                                    FROM bb_cotizacion c
                                    JOIN bb_cotizacion_cotestado bcc on c.cot_id = bcc.cot_id
                                    WHERE ces_id = ? AND co_id = ? AND cote_ultimo IS TRUE");

        $stmt->bind_param("ii", $state, $cot);
        $stmt->execute();
        $result = $stmt->get_result();
        $lista = [];

        while ($row = $result->fetch_assoc()):
            $lista[] = $this->get($row['cot_id'], $db);
        endwhile;

        unset($db);
        return $lista;
    }

    /**
     * @param $cot
     * @param null $db
     * @return stdClass
     */
    public function getState($cot, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT ce.ces_id, ces_descripcion 
                                    FROM bb_cotizacion_estado ce
                                    JOIN bb_cotizacion_cotestado bcc on ce.ces_id = bcc.ces_id
                                    JOIN bb_cotizacion bc on bcc.cot_id = bc.cot_id
                                    WHERE bcc.cote_ultimo IS TRUE AND bc.cot_id = ?");

        $stmt->bind_param("i", $cot);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $obj = new stdClass();
        $obj->ces_id = $row['ces_id'];
        $obj->ces_descripcion = utf8_encode($row['ces_descripcion']);

        unset($db);
        return $obj;
    }

    /**
     * @param $state
     * @param null $db
     * @return stdClass
     */
    public function getStateByID($state, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT *
                                    FROM bb_cotizacion_estado
                                    WHERE ces_id = ?");

        $stmt->bind_param("i", $state);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $obj = new stdClass();
        $obj->ces_id = $row['ces_id'];
        $obj->ces_descripcion = utf8_encode($row['ces_descripcion']);

        unset($db);
        return $obj;
    }

    /**
     * @param $ci_o
     * @param $ci_d
     * @param $cot
     * @param $codigo
     * @param $f_ini
     * @param $f_ter
     * @param $link
     * @param null $db
     * @return array
     */
    public function set($ci_o, $ci_d, $cot, $codigo, $f_ini, $f_ter, $link, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("INSERT INTO bb_cotizacion (cio_id, cid_id, co_id, cot_codigo, cot_fecha_ini, cot_fecha_ter, cot_link) VALUES (?, ?, ?, ?, ?, ?, ?)");

            if (!$stmt):
                throw new Exception("La inserción de la cotización falló en su preparación.");
            endif;

            $bind = $stmt->bind_param("iiissss", $db->clearText($ci_o), $db->clearText($ci_d), $db->clearText($cot), utf8_decode($db->clearText($codigo)), utf8_decode($db->clearText($f_ini)), utf8_decode($db->clearText($f_ter)), utf8_decode($db->clearText($link)));

            if (!$bind):
                throw new Exception("La inserción de la cotización falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La inserción de la cotización falló en su ejecución.");
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
     * @param $cot
     * @param $val
     * @param null $db
     * @return array
     */
    public function setValue($cot, $val, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("UPDATE bb_cotizacion SET cot_valor = ? WHERE cot_id = ?");

            if (!$stmt):
                throw new Exception("La actualización del valor de cotización falló en su preparación.");
            endif;

            $bind = $stmt->bind_param("ii", $db->clearText($val), $db->clearText($cot));

            if (!$bind):
                throw new Exception("La actualización del valor de cotización falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La actualización del valor de cotización falló en su ejecución.");
            endif;

            $result = array('estado' => true, 'msg' => 'OK');
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            $result = array('estado' => false, 'msg' => $e->getMessage());
            return $result;
        }
    }

    /**
     * @param $cot
     * @param $state
     * @param null $db
     * @return array
     */
    public function setState($state, $cot, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("INSERT INTO bb_cotizacion_cotestado (ces_id, cot_id, cote_fecha, cote_ultimo) VALUES (?, ?, CURRENT_TIMESTAMP, TRUE)");

            if (!$stmt):
                throw new Exception("La inserción del estado de cotización falló en su preparación.");
            endif;

            $bind = $stmt->bind_param("ii", $db->clearText($state), $db->clearText($cot));

            if (!$bind):
                throw new Exception("La inserción del estado de cotización falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La inserción del estado de cotización falló en su ejecución.");
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
     * @param $cot
     * @param null $db
     * @return array
     */
    public function setLast($cot, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("UPDATE bb_cotizacion_cotestado SET cote_ultimo = FALSE WHERE cot_id = ?");

            if (!$stmt):
                throw new Exception("La actualización del estado de cotización falló en su preparación.");
            endif;

            $bind = $stmt->bind_param("i", $db->clearText($cot));

            if (!$bind):
                throw new Exception("La actualización del estado de cotización falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La actualización del estado de cotización falló en su ejecución.");
            endif;

            $result = array('estado' => true, 'msg' => 'OK');
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            $result = array('estado' => false, 'msg' => $e->getMessage());
            return $result;
        }
    }

    /**
     * @param $cot
     * @param $extra
     * @param $cant
     * @param null $db
     * @return array
     */
    public function setExtra($cot, $extra, $cant, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("INSERT INTO bb_cotizacion_adicional (cot_id, adi_id, cad_cantidad) VALUES (?, ?, ?)");

            if (!$stmt):
                throw new Exception("La inserción de los extras de cotización falló en su preparación.");
            endif;

            $bind = $stmt->bind_param("iii", $db->clearText($cot), $db->clearText($extra), $db->clearText($cant));

            if (!$bind):
                throw new Exception("La inserción de los extras de cotización falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La inserción de los extras de cotización falló en su ejecución.");
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