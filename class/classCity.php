<?php

class City
{
    public function __construct()
    {
    }

    /**
     * @param $id
     * @param $type
     * @param null $db
     * @return stdClass
     */
    public function get($id, $type, $db = null): stdClass
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        if ($type == 'o'):
            $stmt = $db->Prepare("SELECT * FROM bb_ciudad_origen u WHERE cio_id = ?");
        else:
            $stmt = $db->Prepare("SELECT * FROM bb_ciudad_destino u WHERE cid_id = ?");
        endif;

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $obj = new stdClass();

        if ($type == 'o'):
            $obj->cio_id = $row['cio_id'];
            $obj->cio_nombre = utf8_encode($row['cio_nombre']);
            $obj->cio_codigo = utf8_encode($row['cio_codigo']);
            $obj->cio_pais = utf8_encode($row['cio_pais']);
            $obj->cio_activo = $row['cio_activo'];
        else:
            $obj->cid_id = $row['cid_id'];
            $obj->cid_nombre = utf8_encode($row['cid_nombre']);
            $obj->cid_codigo = utf8_encode($row['cid_codigo']);
            $obj->cid_pais = utf8_encode($row['cid_pais']);
            $obj->cid_activo = $row['cid_activo'];
        endif;

        unset($db);
        return $obj;
    }

    /**
     * @param $type
     * @param null $db
     * @return array
     */
    public function getAll($type, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        if ($type == 'o'):
            $stmt = $db->Prepare("SELECT cio_id FROM bb_ciudad_origen WHERE cio_activo IS TRUE ORDER BY cio_nombre");
        else:
            $stmt = $db->Prepare("SELECT cid_id FROM bb_ciudad_destino WHERE cid_activo IS TRUE ORDER BY cid_nombre");
        endif;

        $stmt->execute();
        $result = $stmt->get_result();
        $lista = [];

        if ($type == 'o'):
            while ($row = $result->fetch_assoc()):
                $lista[] = $this->get($row['cio_id'], $type, $db);
            endwhile;
        else:
            while ($row = $result->fetch_assoc()):
                $lista[] = $this->get($row['cid_id'], $type, $db);
            endwhile;
        endif;

        unset($db);
        return $lista;
    }

    /**
     * @param $city
     * @param $type
     * @param null $db
     * @return array
     */
    public function existsCity($city, $type, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            if ($type == 'o'):
                $stmt = $db->Prepare("SELECT COUNT(cio_id) AS n FROM bb_ciudad_origen WHERE cio_nombre = ?");
            else:
                $stmt = $db->Prepare("SELECT COUNT(cid_id) AS n FROM bb_ciudad_destino WHERE cid_nombre = ?");
            endif;

            if (!$stmt):
                throw new Exception("La búsqueda de la ciudad falló en su preparación.");
            endif;

            $city = $db->clearText($city);
            $bind = $stmt->bind_param("s", $city);
            if (!$bind):
                throw new Exception("La búsqueda de la ciudad falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La búsqueda de la ciudad falló en su ejecución.");
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
     * @param $city
     * @param $code
     * @param $pais
     * @param $type
     * @param null $db
     * @return array
     */
    public function set($city, $code, $pais, $type, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            if ($type == 'o'):
                $stmt = $db->Prepare("INSERT INTO bb_ciudad_origen (cio_nombre, cio_codigo, cio_pais, cio_activo) VALUES (?, ?, ?, TRUE)");
            else:
                $stmt = $db->Prepare("INSERT INTO bb_ciudad_destino (cid_nombre, cid_codigo, cid_pais, cid_activo) VALUES (?, ?, ?, TRUE)");
            endif;

            if (!$stmt):
                throw new Exception("La inserción de la ciudad falló en su preparación.");
            endif;

            $city = utf8_decode($db->clearText($city));
            $code = utf8_decode($db->clearText($code));
            $pais = utf8_decode($db->clearText($pais));
            $bind = $stmt->bind_param("sss", $city, $code, $pais);

            if (!$bind):
                throw new Exception("La inserción de la ciudad falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La inserción de la ciudad falló en su ejecución.");
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
     * @param $type
     * @param $state
     * @param null $db
     * @return array
     */
    public function setState($id, $type, $state, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            if ($type == 'o'):
                $stmt = $db->Prepare("UPDATE bb_ciudad_origen SET cio_activo = ? WHERE cio_id = ?");
            else:
                $stmt = $db->Prepare("UPDATE bb_ciudad_destino SET cid_activo = ? WHERE cid_id = ?");
            endif;

            if (!$stmt):
                throw new Exception("La actualización de la ciudad falló en su preparación.");
            endif;

            $state = $db->clearText($state);
            $id = $db->clearText($id);
            $bind = $stmt->bind_param("ii", $state, $id);

            if (!$bind):
                throw new Exception("La actualización de la ciudad falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La actualización de la ciudad falló en su ejecución.");
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
     * @param $code
     * @param $pais
     * @param $type
     * @param null $db
     * @return array
     */
    public function mod($id, $city, $code, $pais, $type, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            if ($type == 'o'):
                $stmt = $db->Prepare("UPDATE bb_ciudad_origen SET cio_nombre = ?, cio_codigo = ?, cio_pais = ? WHERE cio_id = ?");
            else:
                $stmt = $db->Prepare("UPDATE bb_ciudad_destino SET cid_nombre = ?, cid_codigo = ?, cid_pais = ? WHERE cid_id = ?");
            endif;

            if (!$stmt):
                throw new Exception("La modificación de la ciudad falló en su preparación.");
            endif;

            $city = utf8_decode($db->clearText($city));
            $code = utf8_decode($db->clearText($code));
            $pais = utf8_decode($db->clearText($pais));
            $bind = $stmt->bind_param("sssi", $city, $code, $pais, $id);

            if (!$bind):
                throw new Exception("La modificación de la ciudad falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La modificación de la ciudad falló en su ejecución.");
            endif;

            $result = array('estado' => true, 'msg' => true);
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            return array('estado' => false, 'msg' => $e->getMessage());
        }
    }
}
