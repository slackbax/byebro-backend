<?php

class Food
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

        $stmt = $db->Prepare("SELECT * FROM bb_comida WHERE com_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $obj = new stdClass();
        $obj->com_id = $row['com_id'];
        $obj->com_nombre = utf8_encode($row['com_nombre']);
        $obj->com_descripcion = utf8_encode($row['com_descripcion']);
        $obj->com_activo = $row['com_activo'];

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

        $stmt = $db->Prepare("SELECT com_id FROM bb_comida ORDER BY com_nombre ASC");
        $stmt->execute();
        $result = $stmt->get_result();
        $lista = [];

        while ($row = $result->fetch_assoc()):
            $lista[] = $this->get($row['com_id'], $db);
        endwhile;

        unset($db);
        return $lista;
    }

    /**
     * @param $food
     * @param null $db
     * @return array
     */
    public function existsFood($food, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("SELECT COUNT(com_id) AS n FROM bb_comida WHERE com_nombre = ?");

            if (!$stmt):
                throw new Exception("La búsqueda del pack falló en su preparación.");
            endif;

            $bind = $stmt->bind_param("s", $db->clearText($food));
            if (!$bind):
                throw new Exception("La búsqueda del pack falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La búsqueda del pack falló en su ejecución.");
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
            $result = array('estado' => false, 'msg' => $e->getMessage());
            return $result;
        }
    }

    /**
     * @param $food
     * @param $desc
     * @param null $db
     * @return array
     */
    public function set($food, $desc, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("INSERT INTO bb_comida (com_nombre, com_descripcion, com_activo) VALUES (?, ?, TRUE)");

            if (!$stmt):
                throw new Exception("La inserción del pack falló en su preparación.");
            endif;

            $bind = $stmt->bind_param("ss", utf8_decode($db->clearText($food)), utf8_decode($db->clearText($desc)));

            if (!$bind):
                throw new Exception("La inserción del pack falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La inserción del pack falló en su ejecución.");
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
            $stmt = $db->Prepare("UPDATE bb_comida SET com_activo = ?, com_registro = CURRENT_TIMESTAMP WHERE com_id = ?");

            if (!$stmt):
                throw new Exception("La actualización del pack falló en su preparación.");
            endif;

            $bind = $stmt->bind_param("ii", $db->clearText($state), $db->clearText($id));

            if (!$bind):
                throw new Exception("La actualización del pack falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La actualización del pack falló en su ejecución.");
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
     * @param $food
     * @param $desc
     * @param null $db
     * @return array
     */
    public function mod($id, $food, $desc, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("UPDATE bb_comida SET com_nombre = ?, com_descripcion = ?, com_registro = CURRENT_TIMESTAMP WHERE com_id = ?");

            if (!$stmt):
                throw new Exception("La modificación del pack falló en su preparación.");
            endif;

            $bind = $stmt->bind_param("ssi", utf8_decode($db->clearText($food)), utf8_decode($db->clearText($desc)), $id);

            if (!$bind):
                throw new Exception("La modificación del pack falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La modificación del pack falló en su ejecución.");
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