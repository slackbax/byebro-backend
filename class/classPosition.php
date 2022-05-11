<?php

class Position
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

        $stmt = $db->Prepare("SELECT * FROM bb_cargo WHERE car_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $obj = new stdClass();
        $obj->car_id = $row['car_id'];
        $obj->car_nombre = utf8_encode($row['car_nombre']);
        $obj->car_descripcion = utf8_encode($row['car_descripcion']);

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

        $stmt = $db->Prepare("SELECT car_id FROM bb_cargo ORDER BY car_nombre");
        $stmt->execute();
        $result = $stmt->get_result();
        $lista = [];

        while ($row = $result->fetch_assoc()):
            $lista[] = $this->get($row['car_id'], $db);
        endwhile;

        unset($db);
        return $lista;
    }

    /**
     * @param $position
     * @param null $db
     * @return array
     */
    public function existsPosition($position, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("SELECT COUNT(car_id) AS n FROM bb_cargo WHERE car_nombre = ?");

            if (!$stmt):
                throw new Exception("La búsqueda del cargo falló en su preparación.");
            endif;

            $position = $db->clearText($position);
            $bind = $stmt->bind_param("s", $position);
            if (!$bind):
                throw new Exception("La búsqueda del cargo falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La búsqueda del cargo falló en su ejecución.");
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
     * @param $position
     * @param $desc
     * @param null $db
     * @return array
     */
    public function set($position, $desc, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("INSERT INTO bb_cargo (car_nombre, car_descripcion) VALUES (?, ?)");

            if (!$stmt):
                throw new Exception("La inserción del cargo falló en su preparación.");
            endif;

            $position = utf8_decode($db->clearText($position));
            $desc = utf8_decode($db->clearText($desc));
            $bind = $stmt->bind_param("ss", $position, $desc);

            if (!$bind):
                throw new Exception("La inserción del cargo falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La inserción del cargo falló en su ejecución.");
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
     * @param $position
     * @param $desc
     * @param null $db
     * @return array
     */
    public function mod($id, $position, $desc, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("UPDATE bb_cargo SET car_nombre = ?, car_descripcion = ?, car_registro = CURRENT_TIMESTAMP WHERE car_id = ?");

            if (!$stmt):
                throw new Exception("La modificación del cargo falló en su preparación.");
            endif;

            $position = utf8_decode($db->clearText($position));
            $desc = utf8_decode($db->clearText($desc));
            $bind = $stmt->bind_param("ssi", $position, $desc, $id);

            if (!$bind):
                throw new Exception("La modificación del cargo falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La modificación del cargo falló en su ejecución.");
            endif;

            $result = array('estado' => true, 'msg' => true);
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            return array('estado' => false, 'msg' => $e->getMessage());
        }
    }
}
