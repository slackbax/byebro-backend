<?php

class Viaje
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
                                    FROM bb_viaje v
                                    JOIN bb_cotizacion c ON v.cot_id = c.cot_id
                                    JOIN bb_cotizante co ON c.co_id = co.co_id
                                    JOIN bb_ciudad_origen bco on c.cio_id = bco.cio_id
                                    JOIN bb_ciudad_destino bcd on c.cid_id = bcd.cid_id
                                    WHERE v.vi_id = ?");

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $obj = new stdClass();
        $obj->vi_id = $row['vi_id'];
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
        $obj->vi_codigo = $row['vi_codigo'];
        $obj->vi_fecha_ini = $row['vi_fecha_ini'];
        $obj->vi_fecha_ter = $row['vi_fecha_ter'];
        $obj->vi_valor = $row['vi_valor'];
        $obj->vi_registro = $row['vi_registro'];

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

        $stmt = $db->Prepare("SELECT vi_id FROM bb_viaje");
        $stmt->execute();
        $result = $stmt->get_result();
        $lista = [];

        while ($row = $result->fetch_assoc()):
            $lista[] = $this->get($row['vi_id'], $db);
        endwhile;

        unset($db);
        return $lista;
    }

    /**
     * @param $cot
     * @param null $db
     * @return stdClass
     */
    public function getByCotizacion($cot, $db = null): stdClass
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT vi_id 
                                    FROM bb_viaje v
                                    WHERE cot_id = ?");

        $stmt->bind_param("i", $cot);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $obj = $this->get($row['vi_id'], $db);

        unset($db);
        return $obj;
    }

    /**
     * @param $state
     * @param null $db
     * @return array
     */
    public function getByState($state, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT v.vi_id 
                                    FROM bb_viaje v
                                    JOIN bb_viaje_viajestado ve ON v.vi_id = ve.vi_id
                                    WHERE ves_id = ? AND vie_ultimo IS TRUE");

        $stmt->bind_param("i", $state);
        $stmt->execute();
        $result = $stmt->get_result();
        $lista = [];

        while ($row = $result->fetch_assoc()):
            $lista[] = $this->get($row['vi_id'], $db);
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
    public function getByStateCotizante($state, $cot, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT v.vi_id 
                                    FROM bb_viaje v 
                                    JOIN bb_viaje_viajestado ve on v.vi_id = ve.vi_id
                                    JOIN bb_cotizacion c on v.cot_id = c.cot_id
                                    WHERE ves_id = ? AND co_id = ? AND vie_ultimo IS TRUE");

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
     * @param $vi
     * @param null $db
     * @return stdClass
     */
    public function getState($vi, $db = null): stdClass
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT ve.ves_id, ves_descripcion
                                    FROM bb_viaje_estado ve
                                    JOIN bb_viaje_viajestado vee on ve.ves_id = vee.ves_id
                                    JOIN bb_viaje v on vee.vi_id = v.vi_id
                                    WHERE vie_ultimo IS TRUE AND v.vi_id = ?");

        $stmt->bind_param("i", $vi);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $obj = new stdClass();
        $obj->ves_id = $row['ves_id'];
        $obj->ves_descripcion = utf8_encode($row['ves_descripcion']);

        unset($db);
        return $obj;
    }

    /**
     * @param $state
     * @param null $db
     * @return stdClass
     */
    public function getStateByID($state, $db = null): stdClass
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT *
                                    FROM bb_viaje_estado
                                    WHERE ves_id = ?");

        $stmt->bind_param("i", $state);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $obj = new stdClass();
        $obj->ves_id = $row['ves_id'];
        $obj->ves_descripcion = utf8_encode($row['ves_descripcion']);

        unset($db);
        return $obj;
    }

    /**
     * @param $ci_o
     * @param $ci_d
     * @param $alo
     * @param $cot
     * @param $codigo
     * @param $valor
     * @param $f_ini
     * @param $f_ter
     * @param null $db
     * @return array
     */
    public function set($ci_o, $ci_d, $alo, $cot, $codigo, $valor, $f_ini, $f_ter, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("INSERT INTO bb_viaje (cio_id, cid_id, alo_id, cot_id, vi_codigo, vi_valor, vi_fecha_ini, vi_fecha_ter) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

            if (!$stmt):
                throw new Exception("La inserción del viaje falló en su preparación.");
            endif;

            $ci_o = $db->clearText($ci_o);
            $ci_d = $db->clearText($ci_d);
            $alo = $db->clearText($alo);
            $cot = $db->clearText($cot);
            $codigo = utf8_decode($db->clearText($codigo));
            $valor = $db->clearText($valor);
            $f_ini = utf8_decode($db->clearText($f_ini));
            $f_ter = utf8_decode($db->clearText($f_ter));
            $bind = $stmt->bind_param("iiiisiss", $ci_o, $ci_d, $alo, $cot, $codigo, $valor, $f_ini, $f_ter);

            if (!$bind):
                throw new Exception("La inserción del viaje falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La inserción del viaje falló en su ejecución.");
            endif;

            $result = array('estado' => true, 'msg' => $stmt->insert_id);
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            return array('estado' => false, 'msg' => $e->getMessage());
        }
    }

    /**
     * @param $vi
     * @param $val
     * @param null $db
     * @return array
     */
    public function setValue($vi, $val, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("UPDATE bb_viaje SET vi_valor = ? WHERE vi_id = ?");

            if (!$stmt):
                throw new Exception("La actualización del valor de viaje falló en su preparación.");
            endif;

            $val = $db->clearText($val);
            $vi = $db->clearText($vi);
            $bind = $stmt->bind_param("ii", $val, $vi);

            if (!$bind):
                throw new Exception("La actualización del valor de viaje falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La actualización del valor de viaje falló en su ejecución.");
            endif;

            $result = array('estado' => true, 'msg' => 'OK');
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            return array('estado' => false, 'msg' => $e->getMessage());
        }
    }

    /**
     * @param $state
     * @param $vi
     * @param null $db
     * @return array
     */
    public function setState($state, $vi, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("INSERT INTO bb_viaje_viajestado (ves_id, vi_id, vie_fecha, vie_ultimo) VALUES (?, ?, CURRENT_TIMESTAMP, TRUE)");

            if (!$stmt):
                throw new Exception("La inserción del estado de viaje falló en su preparación.");
            endif;

            $state = $db->clearText($state);
            $vi = $db->clearText($vi);
            $bind = $stmt->bind_param("ii", $state, $vi);

            if (!$bind):
                throw new Exception("La inserción del estado de viaje falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La inserción del estado de viaje falló en su ejecución.");
            endif;

            $result = array('estado' => true, 'msg' => $stmt->insert_id);
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            return array('estado' => false, 'msg' => $e->getMessage());
        }
    }

    /**
     * @param $vi
     * @param null $db
     * @return array
     */
    public function setLast($vi, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("UPDATE bb_viaje_viajestado SET vie_ultimo = FALSE WHERE vi_id = ?");

            if (!$stmt):
                throw new Exception("La actualización del estado de viaje falló en su preparación.");
            endif;

            $vi = $db->clearText($vi);
            $bind = $stmt->bind_param("i", $vi);

            if (!$bind):
                throw new Exception("La actualización del estado de viaje falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La actualización del estado de viaje falló en su ejecución.");
            endif;

            $result = array('estado' => true, 'msg' => 'OK');
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            return array('estado' => false, 'msg' => $e->getMessage());
        }
    }

    /**
     * @param $vi
     * @param $extra
     * @param $cant
     * @param null $db
     * @return array
     */
    public function setExtra($vi, $extra, $cant, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("INSERT INTO bb_viaje_adicional (vi_id, adi_id, vad_cantidad) VALUES (?, ?, ?)");

            if (!$stmt):
                throw new Exception("La inserción de los extras de viaje falló en su preparación.");
            endif;

            $vi = $db->clearText($vi);
            $extra = $db->clearText($extra);
            $cant = $db->clearText($cant);
            $bind = $stmt->bind_param("iii", $vi, $extra, $cant);

            if (!$bind):
                throw new Exception("La inserción de los extras de viaje falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La inserción de los extras de viaje falló en su ejecución.");
            endif;

            $result = array('estado' => true, 'msg' => $stmt->insert_id);
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            return array('estado' => false, 'msg' => $e->getMessage());
        }
    }
}
