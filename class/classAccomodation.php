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
    public function get($id, $db = null): stdClass
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
        $obj->alo_beds1p = $row['alo_beds_1p'];
        $obj->alo_beds2p = $row['alo_beds_2p'];
        $obj->alo_pool = $row['alo_pool'];
        $obj->alo_barbecue = $row['alo_barbecue'];
        $obj->alo_descripcion = utf8_encode($row['alo_descripcion']);
        $obj->alo_direccion = utf8_encode($row['alo_direccion']);
        $obj->alo_pic = $row['alo_pic'];
        $obj->alo_url = $row['alo_url'];
        $obj->alo_registro = $row['alo_registro'];
        $obj->alo_activo = $row['alo_activo'];

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

        $stmt = $db->Prepare("SELECT alo_id FROM bb_alojamiento WHERE alo_activo = TRUE ORDER BY alo_nombre");
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
    public function getByCity($city, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT alo_id FROM bb_alojamiento WHERE cid_id = ? AND alo_activo = TRUE ORDER BY alo_nombre");
        $city = $db->clearText($city);
        $stmt->bind_param('i', $city);
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
    public function getByTrip($tr, $db = null): stdClass
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT a.alo_id 
                                    FROM bb_alojamiento a
                                    JOIN bb_viaje v on a.alo_id = v.alo_id
                                    WHERE vi_id = ?");
        $tr = $db->clearText($tr);
        $stmt->bind_param('i', $tr);
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
     * @param $beds1p
     * @param $beds2p
     * @param $pool
     * @param $barb
     * @param $url
     * @param $db
     * @return array
     */
    public function set($city, $name, $description, $direccion, $rooms, $baths, $beds1p, $beds2p, $pool, $barb, $url, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("INSERT INTO bb_alojamiento (cid_id, alo_nombre, alo_descripcion, alo_direccion, alo_rooms, alo_baths, alo_beds_1p, alo_beds_2p, alo_pool, alo_barbecue, alo_url, alo_activo) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, TRUE)");

            if (!$stmt):
                throw new Exception("La inserción del alojamiento falló en su preparación.");
            endif;

            $city = $db->clearText($city);
            $name = utf8_decode($db->clearText($name));
            $description = utf8_decode($db->clearText($description));
            $direccion = utf8_decode($db->clearText($direccion));
            $rooms = utf8_decode($db->clearText($rooms));
            $baths = utf8_decode($db->clearText($baths));
            $beds1p = utf8_decode($db->clearText($beds1p));
            $beds2p = utf8_decode($db->clearText($beds2p));
            $pool = utf8_decode($db->clearText($pool));
            $barb = utf8_decode($db->clearText($barb));
            $url = utf8_decode($db->clearText($url));
            $bind = $stmt->bind_param("isssiiiiiis", $city, $name, $description, $direccion, $rooms, $baths, $beds1p, $beds2p, $pool, $barb, $url);

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
            return array('estado' => false, 'msg' => $e->getMessage());
        }
    }

    /**
     * @param $id
     * @param $pic
     * @param null $db
     * @return array
     */
    public function setPicture($id, $pic, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("UPDATE bb_alojamiento_pic SET ap_path = ? WHERE alo_id = ?");

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
            return array('estado' => false, 'msg' => $e->getMessage());
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
     * @param $beds1p
     * @param $beds2p
     * @param $pool
     * @param $barb
     * @param $url
     * @param $db
     * @return array|bool[]
     */
    public function mod($id, $city, $name, $description, $direccion, $rooms, $baths, $beds1p, $beds2p, $pool, $barb, $url, $db = null): array
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("UPDATE bb_alojamiento SET cid_id = ?, alo_nombre = ?, alo_descripcion = ?, alo_direccion = ?, alo_rooms = ?, alo_baths = ?, alo_beds_1p = ?, alo_beds_2p = ?, alo_pool = ?, alo_barbecue = ?, alo_url = ?  
                                        WHERE alo_id = ?");

            if (!$stmt):
                throw new Exception("La modificación del alojamiento falló en su preparación.");
            endif;

            $city = $db->clearText($city);
            $name = utf8_decode($db->clearText($name));
            $description = utf8_decode($db->clearText($description));
            $direccion = utf8_decode($db->clearText($direccion));
            $rooms = utf8_decode($db->clearText($rooms));
            $baths = utf8_decode($db->clearText($baths));
            $beds1p = utf8_decode($db->clearText($beds1p));
            $beds2p = utf8_decode($db->clearText($beds2p));
            $pool = utf8_decode($db->clearText($pool));
            $barb = utf8_decode($db->clearText($barb));
            $url = utf8_decode($db->clearText($url));
            $bind = $stmt->bind_param("isssiiiiiisi", $city, $name, $description, $direccion, $rooms, $baths, $beds1p, $beds2p, $pool, $barb, $url, $id);

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
            return array('estado' => false, 'msg' => $e->getMessage());
        }
    }
}
