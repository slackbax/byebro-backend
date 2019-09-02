<?php

class Accomodation
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

        $stmt = $db->Prepare("SELECT * FROM bb_alojamiento u JOIN bb_ciudad_destino c ON u.cid_id = c.cid_id WHERE alo_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $obj = new stdClass();
        $obj->alo_id = $row['alo_id'];
        $obj->cid_id = $row['cid_id'];
        $obj->cid_nombre = utf8_encode($row['cid_nombre']);
        $obj->alo_nombre = utf8_encode($row['alo_nombre']);
        $obj->alo_rooms = $row['alo_rooms'];
        $obj->alo_baths = $row['alo_baths'];
        $obj->alo_descripcion = utf8_encode($row['alo_descripcion']);
        $obj->alo_direccion = utf8_encode($row['alo_direccion']);
        $obj->alo_pic = $row['alo_pic'];
        $obj->alo_registro = $row['alo_registro'];
        $obj->alo_activo = $row['alo_activo'];

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

        $stmt = $db->Prepare("SELECT alo_id FROM bb_alojamiento WHERE alo_activo = TRUE ORDER BY alo_nombre ASC");
        $stmt->execute();
        $result = $stmt->get_result();
        $lista = [];

        while ($row = $result->fetch_assoc()):
            $lista[] = $this->get($row['alo_id'], $db);
        endwhile;

        unset($db);
        return $lista;
    }

    /**
     * @param $city
     * @param null $db
     * @return array
     */
    public function getByCity($city, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT alo_id FROM bb_alojamiento WHERE cid_id = ? AND alo_activo = TRUE ORDER BY alo_nombre ASC");
        $stmt->bind_param('i', $db->clearText($city));
        $stmt->execute();
        $result = $stmt->get_result();
        $lista = [];

        while ($row = $result->fetch_assoc()):
            $lista[] = $this->get($row['alo_id'], $db);
        endwhile;

        unset($db);
        return $lista;
    }

    /**
     * @param $tr
     * @param null $db
     * @return stdClass
     */
    public function getByTrip($tr, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT a.alo_id 
                                    FROM bb_alojamiento a
                                    JOIN bb_viaje v on a.alo_id = v.alo_id
                                    WHERE vi_id = ?");
        $stmt->bind_param('i', $db->clearText($tr));
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $obj = $this->get($row['alo_id'], $db);

        unset($db);
        return $obj;
    }

    /**
     * @param $city
     * @param $name
     * @param $description
     * @param $direccion
     * @param $rooms
     * @param $baths
     * @param null $db
     * @return array
     */
    public function set($city, $name, $description, $direccion, $rooms, $baths, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("INSERT INTO bb_alojamiento (cid_id, alo_nombre, alo_descripcion, alo_direccion, alo_rooms, alo_baths, alo_activo) VALUES (?, ?, ?, ?, ?, ?, TRUE)");

            if (!$stmt):
                throw new Exception("La inserción del alojamiento falló en su preparación.");
            endif;

            $bind = $stmt->bind_param("isssii", $db->clearText($city), utf8_decode($db->clearText($name)), utf8_decode($db->clearText($description)), utf8_decode($db->clearText($direccion)),
                utf8_decode($db->clearText($rooms)), utf8_decode($db->clearText($baths)));

            if (!$bind):
                throw new Exception("La inserción del alojamiento falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La inserción del alojamiento falló en su ejecución.");
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
     * @param $pic
     * @param null $db
     * @return array
     */
    public function setPicture($id, $pic, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("UPDATE bb_alojamiento SET alo_pic = ? WHERE alo_id = ?");

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
            $stmt = $db->Prepare("UPDATE bb_alojamiento SET alo_activo = ? WHERE alo_id = ?");

            if (!$stmt):
                throw new Exception("La actualización del alojamiento falló en su preparación.");
            endif;

            $bind = $stmt->bind_param("ii", $state, $id);
            if (!$bind):
                throw new Exception("La actualización del alojamiento falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La actualización del alojamiento falló en su ejecución.");
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
     * @param $name
     * @param $description
     * @param $direccion
     * @param $rooms
     * @param $baths
     * @param null $db
     * @return array
     */
    public function mod($id, $city, $name, $description, $direccion, $rooms, $baths, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("UPDATE bb_alojamiento SET cid_id = ?, alo_nombre = ?, alo_descripcion = ?, alo_direccion = ?, alo_rooms = ?, alo_baths = ? WHERE alo_id = ?");

            if (!$stmt):
                throw new Exception("La modificación del alojamiento falló en su preparación.");
            endif;

            $bind = $stmt->bind_param("isssiii", $db->clearText($city), utf8_decode($db->clearText($name)), utf8_decode($db->clearText($description)), utf8_decode($db->clearText($direccion)),
                utf8_decode($db->clearText($rooms)), utf8_decode($db->clearText($baths)), $id);

            if (!$bind):
                throw new Exception("La modificación del alojamiento falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La modificación del alojamiento falló en su ejecución.");
            endif;

            $result = array('estado' => true, 'msg' => true);
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            $result = array('estado' => false, 'msg' => $e->getMessage());
            return $result;
        }
    }
}
