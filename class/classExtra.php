<?php

class Extra
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

        $stmt = $db->Prepare("SELECT * FROM bb_adicional WHERE adi_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $obj = new stdClass();
        $obj->adi_id = $row['adi_id'];
        $obj->adi_nombre = utf8_encode($row['adi_nombre']);
        $obj->adi_descripcion = utf8_encode($row['adi_descripcion']);
        $obj->adi_grupal = $row['adi_grupal'];
        $obj->adi_activo = $row['adi_activo'];

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

        $stmt = $db->Prepare("SELECT adi_id FROM bb_adicional WHERE adi_activo IS TRUE ORDER BY adi_nombre");
        $stmt->execute();
        $result = $stmt->get_result();
        $lista = [];

        while ($row = $result->fetch_assoc()):
            $lista[] = $this->get($row['adi_id'], $db);
        endwhile;

        unset($db);
        return $lista;
    }

    /**
     * @param $t
     * @param null $db
     * @return array
     */
    public function getByType($t, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT adi_id FROM bb_adicional WHERE adi_grupal = ? AND adi_activo IS TRUE ORDER BY adi_nombre");

        $stmt->bind_param("i", $t);
        $stmt->execute();
        $result = $stmt->get_result();
        $lista = [];

        while ($row = $result->fetch_assoc()):
            $lista[] = $this->get($row['adi_id'], $db);
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

        $stmt = $db->Prepare("SELECT adi_id, cad_cantidad FROM bb_cotizacion_adicional WHERE cot_id = ?");

        $stmt->bind_param("i", $cot);
        $stmt->execute();
        $result = $stmt->get_result();
        $lista = [];

        while ($row = $result->fetch_assoc()):
            $obj = $this->get($row['adi_id'], $db);
            $obj->adi_cantidad = $row['cad_cantidad'];
            $lista[] = $obj;
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

        $stmt = $db->Prepare("SELECT adi_id, vad_cantidad FROM bb_viaje_adicional WHERE vi_id = ?");

        $stmt->bind_param("i", $cot);
        $stmt->execute();
        $result = $stmt->get_result();
        $lista = [];

        while ($row = $result->fetch_assoc()):
            $obj = $this->get($row['adi_id'], $db);
            $obj->adi_cantidad = $row['vad_cantidad'];
            $lista[] = $obj;
        endwhile;

        unset($db);
        return $lista;
    }

    /**
     * @param $name
     * @param null $db
     * @return array
     */
    public function existsExtra($name, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("SELECT COUNT(adi_id) AS n FROM bb_adicional WHERE adi_nombre = ?");

            if (!$stmt):
                throw new Exception("La búsqueda del adicional falló en su preparación.");
            endif;

            $name = $db->clearText($name);
            $bind = $stmt->bind_param("s", $name);
            if (!$bind):
                throw new Exception("La búsqueda del adicional falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La búsqueda del adicional falló en su ejecución.");
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
     * @param $name
     * @param $group
     * @param $desc
     * @param null $db
     * @return array
     */
    public function set($name, $group, $desc, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("INSERT INTO bb_adicional (adi_nombre, adi_grupal, adi_descripcion, adi_activo) VALUES (?, ?, ?, TRUE)");

            if (!$stmt):
                throw new Exception("La inserción del adicional falló en su preparación.");
            endif;

            $name = utf8_decode($db->clearText($name));
            $group = $db->clearText($group);
            $desc = utf8_decode($db->clearText($desc));
            $bind = $stmt->bind_param("sis", $name, $group, $desc);

            if (!$bind):
                throw new Exception("La inserción del adicional falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La inserción del adicional falló en su ejecución.");
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
            $stmt = $db->Prepare("UPDATE bb_adicional SET adi_activo = ?, adi_registro = CURRENT_TIMESTAMP WHERE adi_id = ?");

            if (!$stmt):
                throw new Exception("La actualización del adicional falló en su preparación.");
            endif;

            $state = $db->clearText($state);
            $id = $db->clearText($id);
            $bind = $stmt->bind_param("ii", $state, $id);

            if (!$bind):
                throw new Exception("La actualización del adicional falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La actualización del adicional falló en su ejecución.");
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
     * @param $group
     * @param $desc
     * @param null $db
     * @return array
     */
    public function mod($id, $name, $group, $desc, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("UPDATE bb_adicional SET adi_nombre = ?, adi_grupal = ?, adi_descripcion = ?, adi_registro = CURRENT_TIMESTAMP WHERE adi_id = ?");

            if (!$stmt):
                throw new Exception("La modificación del adicional falló en su preparación.");
            endif;

            $name = utf8_decode($db->clearText($name));
            $group = $db->clearText($group);
            $desc = utf8_decode($db->clearText($desc));
            $bind = $stmt->bind_param("sisi", $name, $group, $desc, $id);

            if (!$bind):
                throw new Exception("La modificación del adicional falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La modificación del adicional falló en su ejecución.");
            endif;

            $result = array('estado' => true, 'msg' => true);
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            return array('estado' => false, 'msg' => $e->getMessage());
        }
    }
}
